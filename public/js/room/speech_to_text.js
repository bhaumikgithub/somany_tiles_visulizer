let mediaRecorder;
let audioChunks = [];
let micStream = null;

document.getElementById('startRecording').addEventListener('click', async () => {
    const popup = document.getElementById('aiPopup');
    const status = document.getElementById('aiStatus');

    popup.style.display = 'block';
    status.classList.remove('processing');
	
	document.getElementById('voiceBars').style.display = 'flex';
    status.textContent = '';

    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm' });
    audioChunks = [];

    mediaRecorder.ondataavailable = e => audioChunks.push(e.data);

    mediaRecorder.onstop = async () => {
		// Fully stop the mic
		if (stream && stream.getTracks) {
			stream.getTracks().forEach(track => track.stop());
		}

		document.getElementById('voiceBars').style.display = 'none';
        status.textContent = '⏳ Processing...';
        status.classList.add('processing');

        const blob = new Blob(audioChunks, { type: 'audio/webm' });
        const wavBlob = await convertWebmToWav(blob);

        const formData = new FormData();
        formData.append('audio', wavBlob, 'speech.wav');

        try {
            const response = await fetch('/ai/azure-transcribe', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            });

            const result = await response.json();
            const spokenText = result.text || '⚠️ Could not understand';

            status.classList.remove('processing');
            status.textContent = '🗣️ You said: ' + spokenText;

            if (spokenText && spokenText !== '⚠️ Could not understand') {
                processCommand(spokenText);
            }
        } catch (err) {
            status.classList.remove('processing');
            status.textContent = '❌ Error: ' + err.message;
        }

        setTimeout(() => {
            popup.style.display = 'none';
        }, 3000);
    };

    mediaRecorder.start();
    setTimeout(() => mediaRecorder.stop(), 5000);
});

// Converts webm audio to wav using Web Audio API (Azure accepts only PCM/wav)
async function convertWebmToWav(webmBlob) {
  const arrayBuffer = await webmBlob.arrayBuffer();
  const audioContext = new (window.AudioContext || window.webkitAudioContext)();
  const audioBuffer = await audioContext.decodeAudioData(arrayBuffer);

  const wavBuffer = encodeWAV(audioBuffer);
  return new Blob([wavBuffer], { type: 'audio/wav' });
}

function encodeWAV(audioBuffer) {
  const numChannels = audioBuffer.numberOfChannels;
  const sampleRate = audioBuffer.sampleRate;
  const format = 1; // PCM

  const samples = audioBuffer.getChannelData(0);
  const buffer = new ArrayBuffer(44 + samples.length * 2);
  const view = new DataView(buffer);

  function writeString(view, offset, string) {
    for (let i = 0; i < string.length; i++) {
      view.setUint8(offset + i, string.charCodeAt(i));
    }
  }

  // WAV header
  writeString(view, 0, 'RIFF');
  view.setUint32(4, 36 + samples.length * 2, true);
  writeString(view, 8, 'WAVE');
  writeString(view, 12, 'fmt ');
  view.setUint32(16, 16, true); // Subchunk1Size
  view.setUint16(20, format, true); // PCM
  view.setUint16(22, numChannels, true);
  view.setUint32(24, sampleRate, true);
  view.setUint32(28, sampleRate * numChannels * 2, true);
  view.setUint16(32, numChannels * 2, true);
  view.setUint16(34, 16, true); // bits per sample
  writeString(view, 36, 'data');
  view.setUint32(40, samples.length * 2, true);

  // PCM samples
  let offset = 44;
  for (let i = 0; i < samples.length; i++) {
    const s = Math.max(-1, Math.min(1, samples[i]));
    view.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
    offset += 2;
  }

  return buffer;
}


function processCommand(text) {
    const cleaned = text.toLowerCase();
    document.getElementById("aiStatus").textContent = "You said: " + text;

    // Define commands and their trigger phrases
    const commands = [
        {
            action: cmdOpenDrawer,
            phrases: ["open drawer", "show drawer", "drawer open", "open",
                    "please open the drawer", "can you open the drawer", "open up the drawer",
                    "bring up the drawer", "display drawer", "start drawer", "launch drawer",
                    "bring drawer", "reveal drawer", "i want to see drawer", "access drawer", "activate drawer", "turn on drawer"
            ]
        },
        {
            action: cmdCloseDrawer,
            phrases: ["close drawer", "hide drawer", "drawer close", "close it", "close",
					"please close the drawer", "can you close the drawer", "shut the drawer",
					"put away the drawer", "remove drawer", "collapse drawer", "exit drawer",
					"drawer hide", "turn off drawer", "dismiss drawer", "drawer down",
					"drawer off", "stop drawer", "conceal drawer"
				]
        },   
    ];

     // Try to match one of the command phrases
    const matched = commands.find(cmd =>
        cmd.phrases.some(phrase => cleaned.includes(phrase))
    );

    if (matched) {
        matched.action();
    } else {
        document.getElementById("aiStatus").textContent = "Unrecognized command: " + cleaned;
    }

    // Matching apply tile command: "apply Aaren Dark on Wall A"
	let applyTileMatch = cleaned.match(/(?:apply|put|place)?\s*([\w\s]+?)\s*(?:tile|tiles)?\s*(?:on|to)?\s*(wall|floor|paint|counter)\s+([a-c])/i);
	//let applyTileMatch = cleaned.match(/(?:apply|put|place)?\s*([\w\s]+?)\s*(?:tile|tiles)?\s*(?:on|to)?\s+([\w\s]+)/i);
    if (applyTileMatch) {
		console.log(applyTileMatch);
        // const spokenTile = capitalizeWords(applyTileMatch[1].trim());
        // const rawSurface = applyTileMatch[2].trim(); // e.g., "Vol A", "Wall 8", "Paint B"
        // const surface = fuzzyMatchSurface(rawSurface); // dynamic matching here
        // console.log(spokenTile + ", " + rawSurface + ", " + surface);
        //return false;

		const spokenTile = capitalizeWords(applyTileMatch[1].trim());
		let rawSurface = `${applyTileMatch[2]} ${applyTileMatch[3]}`;
        const surface = fuzzyMatchSurface(rawSurface);

		const matchedTile = fuzzyMatchTile(spokenTile);
		if (matchedTile) {
			document.getElementById("aiStatus").textContent = `🧱 Applying ${matchedTile} to ${surface}…`;
			cmdAppliedTiles(matchedTile, surface);
		} else {
			document.getElementById("aiStatus").textContent = `❌ No tile matched "${spokenTile}"`;
		}
	}

	// const showOptionsMatch = cleaned.match(/(?:show|any)?\s*(?:tile)?\s*options?\s*(?:for)?\s*(wall|floor|paint|counter)\s+([a-c])/i);
	// if (showOptionsMatch) {
	// 	const surface = `${showOptionsMatch[1]} ${showOptionsMatch[2]}`.toUpperCase(); // e.g., "FLOOR A"
	// 	cmdShowTilesOptions(surface);
	// 	return;
	// }

    let command = "show me white tiles for floor A";
    let { surface, color } = parseFilterCommand(command);
    console.log("Parsed:", { color, surface });

    checkColorFilterInPanel(color);

}

function capitalizeWords(str) {
  return str.replace(/\b\w/g, char => char.toUpperCase());
}


function getAllSurfaceNames() {
    return Array.from(document.querySelectorAll('#selectd-data .-caption'))
        .map(el => el.textContent.trim().toLowerCase())
        .filter(text => text.length > 0);
}

function fuzzyMatchSurface(spoken) {
    const surfaces = getAllSurfaceNames(); // From DOM
    const fuse = new Fuse(surfaces, {
        includeScore: true,
        threshold: 0.4
    });
    const result = fuse.search(spoken.toLowerCase().trim());
    return result.length ? capitalizeWords(result[0].item) : null;
}

/*** All Commands starts from here */
function cmdOpenDrawer() {
   openTopPanel();
}

function cmdCloseDrawer(){
    closeTopPanel();
}

const getAllTileNames = () => {
    return Array.from(document.querySelectorAll('#topPanelTilesListUl li .-caption'))
        .map(el => el.textContent.trim());
}

const fuzzyMatchTile = (spokenTileName) => {
    const tiles = getAllTileNames();
    const fuse = new Fuse(tiles, {
        includeScore: true,
        threshold: 0.4 // adjust sensitivity
    });

    const result = fuse.search(spokenTileName);
    return result.length ? result[0].item : null;
}

function cmdAppliedTiles(tileName,surfaceName) {
	console.log("======================== Consoling AI TOOLS STARTS =================================");
	console.log(tileName , surfaceName);
	console.log("======================== Consoling AI TOOLS ENDS ===================================");
    // // Open drawer if not open
    // const drawer = $('#topPanel');
    // const isDrawerOpen = drawer.is(':visible') && drawer.css('right') === '0px';
    // if (!isDrawerOpen) {
    //     drawer.show().animate({ right: '0px' }, 200);
    //     console.log('Drawer opened');
    // }

  	// openTileSelectionPanel(surfaceName.replace(" ", "_")); // e.g., Wall A → Wall_A
	const currentSurface = $('#slected-panel .display_surface_name h5#optionText').text().trim();
	if (currentSurface.toLowerCase() === surfaceName.toLowerCase()){
		//Find tile by caption
		const tileElement = $('#topPanelTilesListUl li.top-panel-content-tiles-list-item').filter(function () {
			const caption = $(this).find('p.-caption').first().text().trim().toLowerCase();
			return caption === tileName.trim().toLowerCase();
		}).first();

		if (tileElement.length === 0) {
			alert(`Tile "${tileName}" not found.`);
			return;
		}

		//Simulate tile click
		tileElement.trigger('click');
	}
}

//Show Tiles options for Wall A, Wall B, Floor Etc
function cmdShowTilesOptions(surfaceName)
{
	openSurfacePanel(surfaceName);

    // Update status (optional)
    document.getElementById("aiStatus").textContent = `Showing tile options for ${surfaceName}`;
}


function parseFilterCommand(text) {
    text = text.toLowerCase();

    // Match known surfaces like Wall A, Floor B, etc.
    const surfaceMatch = text.match(/\b(wall|floor|counter|paint)\s*([a-o])/i);
    const surface = surfaceMatch ? `${capitalizeWords(surfaceMatch[1])} ${surfaceMatch[2].toUpperCase()}` : null;

    // Match colors
    const colorMatch = text.match(/\b(white|black|grey|beige|brown|blue|pink|red|green|yellow|cream|multi colour)\b/i);
    const color = colorMatch ? capitalizeWords(colorMatch[1]) : null;

    return { surface, color };
}

function openSurfacePanel(surfaceName) {
   // Open drawer if not open
    const drawer = $('#topPanel');
    const isDrawerOpen = drawer.is(':visible') && drawer.css('right') === '0px';
    if (!isDrawerOpen) {
        drawer.show().animate({ right: '0px' }, 200);
        console.log('Drawer opened');
    }
	// Always attempt to switch panel (harmless if already correct)
  	openTileSelectionPanel(surfaceName.replace(" ", "_")); // e.g., Wall A → Wall_A
}

function checkColorFilterInPanel(color, surface) {
    if (!color || !surface) return;

    openSurfacePanel(surface);

    // Uncheck all first
    $panel.find('.checkboxClass').prop('checked', false);

    // Check matching label
    $panel.find('.filter-item-checkbox').each(function () {
        const label = $(this).find('label').text().trim();
        if (label.toLowerCase() === color.toLowerCase()) {
            $(this).find('input[type="checkbox"]').prop('checked', true).trigger('change');
        }
    });
}
