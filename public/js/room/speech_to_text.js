let mediaRecorder;
let audioChunks = [];
let micStream = null;
let speechToTextURL = new URL(window.location.href);
let baseUrl = window.location.origin;

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
        const surface = `${showOptionsMatch[1]} ${showOptionsMatch[2]}`.toUpperCase();
        cmdShowTilesOptions(surface);
        return;
    }

    // 3. Handle filter-based voice command like "Show me 600x1200 white glossy tiles for Wall A"
    const filterCommandTriggerWords = ["show", "display", "list", "give me", "may i see", "can you show", "please show", "let me see"];
    if (filterCommandTriggerWords.some(trigger => cleaned.includes(trigger))) {
        handleVoiceFilterCommand(cleaned);
        return;
    }

    const pathSegments = window.location.pathname.split("/");
    if( pathSegments[1] == "listing"){
        // Check if it's a room navigation command
        const roomTriggers = ["select", "open", "go to", "show"];
        if (roomTriggers.some(trigger => cleaned.startsWith(trigger))) {
            handleRoomSelectionCommand(cleaned,pathSegments[1]);
            return;
        }
    } else {
        const categoryCommandTriggers = ["open", "select", "go to", "show"];
        if (categoryCommandTriggers.some(trigger => cleaned.includes(trigger) && cleaned.includes(trigger))) {
            handleCategoryNavigation(cleaned);
            return;
        }
    }
    
    const backCommandTriggerWords = ["go back","back","go to previous","return","go back to listing","go to listing page","go back to listing page"];
    const cleanedText = cleaned.replace(/[^\w\s]/gi, '').trim();
    const isBackCommand = backCommandTriggerWords.some(trigger =>
        cleanedText.includes(trigger)
    );

    if (isBackCommand) {
        handleBackNavigation();
        return;
    }

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


function cmdShowTilesOptions(surfaceName)
{
	openSurfacePanel(surfaceName);

    // Update status (optional)
    document.getElementById("aiStatus").textContent = `Showing tile options for ${surfaceName}`;
}

//Multiple Filter commands
async function handleVoiceFilterCommand(voiceText) {
    const { surface, rawWords } = parseVoiceFilters(voiceText);
    if (!surface || rawWords.length === 0) return;

    const panelOpen = openSurfacePanel(surface);
    const filterOpen = $('#topPanelNavFilter').is(':visible');

    if (!panelOpen || !filterOpen) {
        await openSurfacePanelAndInitialize(surface);
    }

    applyVoiceFiltersToRoom(rawWords, surface);
}

function parseVoiceFilters(text) {
    const lowerText = text.toLowerCase();

    // ✅ Match full surface name (e.g., "floor a", "wall b", "counter top")
    const surfaceMatch = lowerText.match(/\b(?:floor|wall|counter|paint)\s*[a-z0-9]*/i);
    const surface = surfaceMatch ? surfaceMatch[0].trim().replace(/\s+/g, ' ') : null;

    // ✅ Normalize spoken sizes like "600 by 1200" or "600 1200" to "600x1200mm"
    let cleaned = lowerText
        .replace(/show me|please show|i want to see|give me|tiles for|can you show|may i see/g, '')
        .replace(/\b(?:floor|wall|counter|paint)\s*[a-z0-9]*/gi, '') // remove surface phrases
        .replace(/(\d{2,4})\s*(?:by|x|\s)\s*(\d{2,4})/g, (_, w1, w2) => `${w1}x${w2}mm`)
        .replace(/[^a-zA-Z0-9\sx]/g, ' ') // remove extra punctuation
        .replace(/\s+/g, ' ') // collapse spaces
        .trim();

    const stopWords = ['and', 'tiles', 'tile', 'for', 'on', 'the', 'a'];
    const rawWords = cleaned
        .split(/\s+/)
        .filter(w => w && !stopWords.includes(w));

    return { surface, rawWords };
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
    let surfaceType = surfaceName.toLowerCase().includes("floor") ? "floor" : "wall";

    let filters = currentRoom?._filters?._list || [];
    if (!filters.length) return console.warn('❌ No filters found');

    let matchCount = 0;

    let normalize = word => word.toLowerCase().replace(/mm$/, '');

    filters.forEach(filter => {
        if (!filter.surface || filter.surface.toLowerCase() !== surfaceType) return;

        const matchedItems = [];

        rawWords.forEach(word => {
            //let value = word;

            let value = normalize(word); // strip mm and lowercase

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
    console.log("matchCount " + matchCount);
    if (matchCount > 0) {
        currentRoom._filters._find?.();
        console.log(`✅ Applied ${matchCount} filter(s) to ${surfaceName}`);
    } else {
        console.warn('❌ No matching filter values found in rawWords');
    }
}

function normalizeFilterWord(word) {
    // Convert "600x1200mm" → "600x1200"
    return word.replace(/mm$/i, '').toLowerCase();
}

const categorySlugMap = {
    "living room": "livingroom",
    "prayer room": "prayer-room",
    "kitchen": "kitchen",
    "bathroom": "bathroom",
    "bedroom": "bedroom",
    "outdoor":"outdoor",
    "commercial":"commercial"
    // Add more as needed
};

function handleCategoryNavigation(cleanedText) {
    let lowerText = cleanedText.toLowerCase().trim();

    // Remove all common voice trigger phrases and trailing "category" or punctuation
    let categoryName = lowerText
        .replace(/^(open|select|go to|show)\s+/, '')     // remove start commands
        .replace(/\s*category\s*$/, '')                  // remove 'category' at end
        .replace(/[.?!]+$/, '')                          // remove trailing punctuation
        .trim();

    const slug = categorySlugMap[categoryName];

    if (slug) {
        redirectToCategory(slug);
    } else {
        console.warn("⚠️ No valid category name found in voice input");
    }
}


function redirectToCategory(categorySlug) {
    let pathSegments = speechToTextURL.pathname.split("/");
    // Customize this URL pattern as per your app routing
    if (pathSegments[1] === "2d-studio") {
        fetchCategory(categorySlug.toLowerCase(),'2d');
        window.location.href = `${baseUrl}/listing/${categorySlug.toLowerCase()}`;
    } else if (pathSegments[1] === "panorama-studio"){
        fetchCategory(categorySlug.toLowerCase(),'');
        window.location.href = `${baseUrl}/panorama-listing/${categorySlug.toLowerCase()}`;
    }
}

//Go back to previous page
function handleBackNavigation() {
    let pathSegments = window.location.pathname.split("/");
    let baseUrl = window.location.origin;
    let roomSlug = (typeof currentRoom !== "undefined" && currentRoom?.type) 
        ? currentRoom.type.toLowerCase() 
        : null;

    let from = pathSegments[1];

    if (from === "listing") {
        window.location.href = `${baseUrl}/2d-studio`;
    } else if (from === "panorama-listing") {
        window.location.href = `${baseUrl}/panorama-studio`;
    } else if (from === "2d-studio" && roomSlug) {
        window.location.href = `${baseUrl}/listing/${roomSlug}`;
    } else if (from === "panorama-studio" && roomSlug) {
        window.location.href = `${baseUrl}/panorama-listing/${roomSlug}`;
    } else {
        console.warn("⚠️ Unable to determine current room type or invalid path");
    }
}

let allRooms = Array.from(document.querySelectorAll('.body-selection-item a')).map(el => {
    return {
        id: el.getAttribute('data-room-id'),
        name: el.title.trim()
    };
});

function handleRoomSelectionCommand(cleanedText,redirectionWord) {
    let triggers = ["select", "open", "go to", "show"];
    let spokenRoom = cleanedText.toLowerCase().trim();

    // Remove trigger prefix
    triggers.forEach(trigger => {
        if (spokenRoom.startsWith(trigger)) {
            spokenRoom = spokenRoom.replace(trigger, "").trim();
        }
    });
    // Remove trailing punctuation like "." or ","
    spokenRoom = spokenRoom.replace(/[.,!?]$/, "").trim();
    // Try to match
    let matchedRoom = allRooms.find(room =>
        room.name.toLowerCase() === spokenRoom
    );
    let redirectUrl = "";
    if (matchedRoom) {
        let baseUrl = window.location.origin;
        // Redirect to your 2d-studio route with ID
        if( redirectionWord == "listing"){
            redirectUrl = `${baseUrl}/2d-studio/${matchedRoom.id}`;
            fetchRoom(matchedRoom.id, matchedRoom.name, '2d');
        } else {
            redirectUrl = `${baseUrl}/panorama-studio/${matchedRoom.id}`;
            fetchRoom(matchedRoom.id, matchedRoom.name, '3d');
        }
        window.location.href = redirectUrl;        
    } else {
        console.warn("⚠️ No room found for:", spokenRoom);
    }
}
