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


    // 1. Apply a tile to a surface
    let applyTileMatch = cleaned.match(/(?:\bapply\b|\bput\b|\bplace\b)\s+([\w\s]+?)\s*(?:tile|tiles)?\s*(?:on|to)?\s*(wall|floor|paint|counter)\s*([a-c])/i);    if (applyTileMatch) {
        console.log("Option 1");
        const spokenTile = capitalizeWords(applyTileMatch[1].trim());
        const rawSurface = `${applyTileMatch[2]} ${applyTileMatch[3]}`;
        const surface = fuzzyMatchSurface(rawSurface);
        const matchedTile = fuzzyMatchTile(spokenTile);

        if (matchedTile) {
            document.getElementById("aiStatus").textContent = `🧱 Applying ${matchedTile} to ${surface}…`;
            cmdAppliedTiles(matchedTile, surface);
        } else {
            document.getElementById("aiStatus").textContent = `❌ No tile matched "${spokenTile}"`;
        }
        return;
    }

	// 2. Show tile options for surface
    let showOptionsMatch = cleaned.match(/(?:show|any)?\s*(?:tile)?\s*options?\s*(?:for)?\s*(wall|floor|paint|counter)\s*([a-c])/i);
    if (showOptionsMatch) {
        console.log("Option 2");
        console.log(showOptionsMatch);
        const surface = `${showOptionsMatch[1]} ${showOptionsMatch[2]}`.toUpperCase();
        console.log(surface);
        cmdShowTilesOptions(surface);
        return;
    }

    // 3. Show me [Color] tiles for [Surface]
    // const preCleaned = cleaned
    //     .replace(/\btales\b/g, "tiles")
    //     .replace(/\bflor\b/g, "floor")
    //     .replace(/\bwal\b/g, "wall");

    // let colorOnlyMatch = preCleaned.match(/(?:show|display)?\s*(?:me)?\s*([\w\s]+?)\s+(?:tile|tiles)\s+for\s+(wall|floor|paint|counter)\s*([a-c])/i);
    // if (colorOnlyMatch) {
    //     const rawColor = colorOnlyMatch[1].trim();
    //     const color = normalizeColor(rawColor); // Only "multicolor" is converted
    //     const surface = `${colorOnlyMatch[2]} ${colorOnlyMatch[3]}`.toUpperCase();
    //     console.log("color is " + color + ", surface is " + surface);
    //     cmdFilterOptions(color, surface);
    //     return;
    // }

    // 4. Show me [size] [color] [finish] tile for [surface]
    handleVoiceFilterCommand("Show me white and pink glossy tiles for Floor A");
    //cmdApplyVoiceFilters(parsed);


    // If nothing matches
    document.getElementById("aiStatus").textContent = "Unrecognized command: " + cleaned;
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

async function cmdFilterOptions(colorLabel, surfaceName) {
    if (!colorLabel || !surfaceName) return;

    await openSurfacePanelAndInitialize(surfaceName);

    const surfaceType = surfaceName.toLowerCase().includes("floor") ? "floor" : "wall";

    const filters = currentRoom?._filters?._list || [];
    const colorFilter = filters.find(f =>
        (f.field === 'colour' || f.field === 'color') &&
        f.surface?.toLowerCase() === surfaceType
    );

    if (!colorFilter) {
        console.warn(`❌ No color filter found for surface: "${surfaceName}"`);
        return;
    }

    const targetItem = colorFilter._items.find(item =>
        item.value?.toLowerCase() === colorLabel.toLowerCase()
    );

    if (!targetItem?.domElement) {
        console.warn(`❌ Color "${colorLabel}" not found in filter items`);
        return;
    }

    // // Uncheck others
    // colorFilter._items.forEach(item => {
    //     if (item.domElement) {
    //         item.domElement.checked = false;
    //         item.checked = false;
    //     }
    // });

    // Click target
    targetItem.domElement.click();

    // Reapply logic
    colorFilter._apply?.();
    currentRoom._filters._find?.();
    console.log(`✅ Final reapply triggered for "${colorLabel}" on "${surfaceName}"`);
}

function normalizeColor(color) {
    const lower = color.toLowerCase().trim();
    
    if (lower === "multicolor" || lower === "multi color" || lower === "multi-color") {
        return "Multi Colour";
    }

    return capitalizeWords(color.trim());
}

function parseVoiceFilters(text) {
    const lowerText = text.toLowerCase();
    const surfaceMatch = text.match(/\b(floor|wall)\s+[a-z]/i);
    const surface = surfaceMatch ? surfaceMatch[0].trim() : null;

    const cleaned = lowerText
        .replace(/show me|i want to see|give me|tiles for/g, '')
        .replace(/\b(floor|wall)\s+[a-z]/gi, '')
        .replace(/[^a-zA-Z0-9\s]/g, '')
        .trim();

    const stopWords = ['and', 'tiles', 'tile', 'for', 'on', 'the', 'a'];
    const rawWords = cleaned
        .split(/\s+/)
        .filter(w => w && !stopWords.includes(w));

    return { surface, rawWords };
}

function openSurfacePanelAndInitialize(surfaceName) {
    return new Promise((resolve) => {
        openSurfacePanel(surfaceName);

        setTimeout(() => {
            const refineBtn = document.getElementById('btnRefine');
            if (refineBtn) {
                refineBtn.click(); // Important: trigger filter logic
                console.log('✅ Simulated first-time #btnRefine click');
            } else {
                console.warn('❌ #btnRefine not found.');
            }
            resolve();
        }, 300); // delay to allow panel to render
    });
}

function applyVoiceFiltersToRoom(rawWords, surfaceName) {
    const surfaceType = surfaceName.toLowerCase().includes("floor") ? "floor" : "wall";

    const filters = currentRoom?._filters?._list || [];
    if (!filters.length) return console.warn('❌ No filters found');

    let matchCount = 0;

    filters.forEach(filter => {
        if (!filter.surface || filter.surface.toLowerCase() !== surfaceType) return;

        const fieldName = filter.field;
        const matchedItems = [];

        rawWords.forEach(word => {
            let value = word;

            // Handle "Multicolor" voice variations
            if (value.toLowerCase() === 'multicolor' || value.toLowerCase() === 'multi') {
                value = 'Multi Colour';
            }

            const item = filter._items.find(i => i.value.toLowerCase() === value.toLowerCase());
            if (item && item.domElement) {
                matchedItems.push(item);
            }
        });

        if (matchedItems.length) {
            matchedItems.forEach(item => {
                item.domElement.checked = true;
                item.checked = true;
            });

            filter._apply?.();
            matchCount += matchedItems.length;
        }
    });

    if (matchCount > 0) {
        currentRoom._filters._find?.();
        console.log(`✅ Applied ${matchCount} filter(s) to ${surfaceName}`);
    } else {
        console.warn('❌ No matching filter values found in rawWords');
    }
}

function highlightMatchedFilterCategory(rawWords, surfaceType) {
    const filterPriority = ['size', 'colour', 'finishes', 'category', 'innovation'];

    const filters = currentRoom?._filters?._list || [];
    if (!filters.length) return;

    // Reset all tabs
    $('#topPanelNavFilter li').removeClass('filter-click-active');

    for (let field of filterPriority) {
        const fullField = `${surfaceType}_${field}`;
        const filter = filters.find(f => f.field === field && f.surface?.toLowerCase() === surfaceType);

        if (filter) {
            const match = rawWords.find(word =>
                filter._items?.some(item => item.value.toLowerCase() === word.toLowerCase())
            );

            if (match) {
                const liId = `#filterclick_${fullField}`;
                const liEl = $(liId);
                if (liEl.length) {
                    liEl.addClass('filter-click-active');
                    liEl[0].click(); // simulate tab change
                    console.log(`✅ Activated filter tab: ${field}`);
                }
                break;
            }
        }
    }
}


async function handleVoiceFilterCommand(voiceText) {
    const { surface, rawWords } = parseVoiceFilters(voiceText);
    if (!surface || rawWords.length === 0) return;

    const panelOpen = openSurfacePanel(surface);
    const filterOpen = $('#topPanelNavFilter').is(':visible');

    if (!panelOpen || !filterOpen) {
        await openSurfacePanelAndInitialize(surface);
    }

    highlightMatchedFilterCategory(rawWords, surface);
    applyVoiceFiltersToRoom(rawWords, surface);
}





