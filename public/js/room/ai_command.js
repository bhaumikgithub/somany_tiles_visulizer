function cmdOpenDrawer() {
    hideTopPanelMainPanel();
    if (!isMobilePortrait()) {
        $('#topPanel').show();
        $('#topPanel').animate({ right: '0px' }); // Move the panel to the right
        $('#topPanelHideIcon').addClass('glyphicon-menu-right');
    }
}

function cmdCloseDrawer(){
    closeTopPanel();
}

const getAllTileNames = () => {
    return Array.from(document.querySelectorAll('#topPanelTilesListUl li .-caption'))
        .map(el => el.textContent.trim());
};

const fuzzyMatchTile = (spokenTileName) => {
    const tiles = getAllTileNames();
    const fuse = new Fuse(tiles, {
        includeScore: true,
        threshold: 0.4 // adjust sensitivity
    });

    const result = fuse.search(spokenTileName);
    return result.length ? result[0].item : null;
};

function cmdAppliedTiles(tileName,surfaceName) {
	console.log("======================== Consoling AI TOOLS STARTS =================================");
	console.log(tileName , surfaceName);
	console.log("======================== Consoling AI TOOLS ENDS ===================================");
    // Open drawer if not open
    const drawer = $('#topPanel');
    const isDrawerOpen = drawer.is(':visible') && drawer.css('right') === '0px';
    if (!isDrawerOpen) {
        drawer.show().animate({ right: '0px' }, 200);
        console.log('Drawer opened');
    }

    // Always attempt to switch panel (harmless if already correct)
    openTileSelectionPanel(surfaceName.replace(" ", "_")); // e.g., Wall A → Wall_A
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

function getSimilarityScore(a, b) {
    a = a.toLowerCase();
    b = b.toLowerCase();

    if (a === b) return 1;

    const distance = levenshteinDistance(a, b);
    return 1 - distance / Math.max(a.length, b.length);
}

function levenshteinDistance(a, b) {
    const matrix = Array.from({ length: a.length + 1 }, () => []);
    for (let i = 0; i <= a.length; i++) matrix[i][0] = i;
    for (let j = 0; j <= b.length; j++) matrix[0][j] = j;

    for (let i = 1; i <= a.length; i++) {
        for (let j = 1; j <= b.length; j++) {
            const cost = a[i - 1] === b[j - 1] ? 0 : 1;
            matrix[i][j] = Math.min(
                matrix[i - 1][j] + 1,
                matrix[i][j - 1] + 1,
                matrix[i - 1][j - 1] + cost
            );
        }
    }

    return matrix[a.length][b.length];
}


let mediaRecorder;
let audioChunks = [];
let micStream = null;

// function speak(text) {
//   const msg = new SpeechSynthesisUtterance(text);
//   msg.lang = "en-US";
//   window.speechSynthesis.speak(msg);
// }

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
	let applyTileMatch = cleaned.match(/(?:apply|put|place)?\s*([\w\s]+?)\s*(?:tile|tiles)?\s*(?:on|to)?\s*(wall|floor)\s+([a-c])/i);
	if (applyTileMatch) {
		console.log(applyTileMatch);
		const spokenTile = capitalizeWords(applyTileMatch[1].trim());
		const surface = `${applyTileMatch[2]} ${applyTileMatch[3]}`.toUpperCase();

		const matchedTile = fuzzyMatchTile(spokenTile);
		if (matchedTile) {
			document.getElementById("aiStatus").textContent = `🧱 Applying ${matchedTile} to ${surface}…`;
			cmdAppliedTiles(matchedTile, surface);
		} else {
			document.getElementById("aiStatus").textContent = `❌ No tile matched "${spokenTile}"`;
		}
	}
}

function capitalizeWords(str) {
  return str.replace(/\b\w/g, char => char.toUpperCase());
}

async function startRecording() {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    const audioContext = new AudioContext();
    const source = audioContext.createMediaStreamSource(stream);
    const analyser = audioContext.createAnalyser();
    analyser.fftSize = 256;
    const dataArray = new Uint8Array(analyser.frequencyBinCount);
    source.connect(analyser);

    let animationId;
    const updateVolume = () => {
      analyser.getByteFrequencyData(dataArray);
      const avg = dataArray.reduce((a, b) => a + b, 0) / dataArray.length;
      const volume = Math.min((avg / 255) * 100, 100);
      const bar = document.getElementById("volumeBar");
      bar.style.width = `${volume}%`;
      bar.style.background = volume < 30 ? "#4caf50" : volume < 60 ? "#ff9800" : "#f44336";
      animationId = requestAnimationFrame(updateVolume);
    };
    updateVolume(); // start animation

    mediaRecorder = new MediaRecorder(stream);
    audioChunks = [];

    document.getElementById("aiPopup").style.display = "block";
    document.getElementById("aiStatus").textContent = "🎤 Listening… 5s";

    let secondsLeft = 5;
    const countdown = setInterval(() => {
      secondsLeft--;
      if (secondsLeft > 0) {
        document.getElementById("aiStatus").textContent = `🎤 Listening… ${secondsLeft}s`;
      } else {
        clearInterval(countdown);
      }
    }, 1000);

    mediaRecorder.ondataavailable = event => {
      audioChunks.push(event.data);
    };

    mediaRecorder.onstop = async () => {
      cancelAnimationFrame(animationId);
      stream.getTracks().forEach(track => track.stop());
      audioContext.close();
      document.getElementById("volumeBar").style.width = "0%";

      const blob = new Blob(audioChunks, { type: 'audio/webm' });
      const formData = new FormData();
      formData.append("audio", blob, "audio.webm");

      document.getElementById("aiStatus").textContent = "⏳ Processing…";

      try {
        const response = await fetch("/ai/transcribe", {
          method: "POST",
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: formData,
        });

        const result = await response.json();
        const text = result.text?.trim();

        if (text) {
          //document.getElementById("aiStatus").textContent = `Heard: ${text}`;
          setTimeout(() => {
            processCommand(text);
          }, 400);
        } else {
          document.getElementById("aiStatus").textContent = "❌ Could not transcribe.";
        }
      } catch (e) {
        console.error("❌ Error:", e);
        document.getElementById("aiStatus").textContent = "Server error";
      }

      setTimeout(() => {
        document.getElementById("aiPopup").style.display = "none";
      }, 2000);
    };

    mediaRecorder.start();
    setTimeout(() => {
      if (mediaRecorder.state === "recording") {
        mediaRecorder.stop();
      }
    }, 5000);

  } catch (err) {
    alert("Mic error: " + err.message);
  }
}


document.getElementById("startRecording").addEventListener("click", startRecording);