var Game = {
	init: function() {
		$("title").textContent = `Quiver ${Game.version}`; // updating the title with the last version
		UI.menu.options.scrollTop = 0;
		UI.menu.options.style.scrollBehavior = "smooth";
		Game.lang("en_US"); // setting the game language to english (USA) - can be modified in the options
		$("main").style.display = "block" // opening the game window
	},
	lang: function(l) {
		// l: language name (str)
		// requesting the JSON file
		var path = `../assets/lang/${l}.json`,
			request = new XMLHttpRequest();
		request.open("GET", path);
		request.responseType = "json";
		request.send();
		request.addEventListener("load", function() {
			// the request has been accepted, recovering file content
			var r = this.response[l];
			// applying the recovered language data to UI elements
			// unique buttons
			UI.btn.new_game.textContent = r["new_game.text"];
			UI.btn.launch_backup.textContent = r["launch_backup.text"];
			UI.btn.options.textContent = r["options.text"];
			// data-function buttons
			UI.btn.data_function.close.forEach(function(e) {e.textContent = r["close.text"]});
			// menu titles
			menu.new_game.textContent = r["new_game.text"];
			menu.launch_backup.textContent = r["launch_backup.text"];
			menu.options.textContent = r["options.text"];
			// options
			// keybinds
			options.keybinds._title_.textContent = r.options["keybinds.text"];
			options.keybinds.forward.textContent = r.options["keybinds:forward.text"];
			options.keybinds.backward.textContent = r.options["keybinds:backward.text"];
			options.keybinds.left.textContent = r.options["keybinds:left.text"];
			options.keybinds.right.textContent = r.options["keybinds:right.text"];
			options.keybinds.console.textContent = r.options["keybinds:console.text"];
			// display
			options.display._title_.textContent = r.options["display.text"];
			options.display.borders.textContent = r.options["display:borders.text"];
			options.display.animations.textContent = r.options["display:animations.text"];
			// audio
			options.audio._title_.textContent = r.options["audio.text"];
			options.audio.music.textContent = r.options["audio:music.text"];
			options.audio.music_volume.textContent = r.options["audio:music_volume.text"];
			options.audio.sound.textContent = r.options["audio:sound.text"];
			options.audio.sound_volume.textContent = r.options["audio:sound_volume.text"];
			// saves
			options.saves._title_.textContent = r.options["saves.text"];
			options.saves.advanced.textContent = r.options["saves:advanced.text"];
			// language
			options.lang._title_.textContent = r.options["lang.text"];
			options.lang.en_US.textContent = r.options["lang:en_US.text"];
			options.lang.es_ES.textContent = r.options["lang:es_ES.text"];
			options.lang.fr_FR.textContent = r.options["lang:fr_FR.text"];
			// about
			options.about._title_.textContent = r.options["about.text"];
			options.about.updates.textContent = r.options["about:updates.text"];
			options.about.credits.textContent = r.options["about:credits.text"]
		})
	},
	toggle_menu: function(m, s) {
		// m: menu name (str)
		// s: status (1: open or 0: close)
		var overlay = document.querySelector(".overlay"),
			menu = document.querySelector(`.${m}`),
			st = menu.querySelector(".scrollbox-top"),
			sb = menu.querySelector(".scrollbox-bottom"),
			content = menu.querySelector(".content");
		if (s == "open") {
			// opening
			overlay.style.display = "flex";
			menu.style.display = "flex";
			st.style.animationName = "scrollbox-open";
			sb.style.animationName = "scrollbox-open";
			st.style.height = "50%";
			sb.style.height = "50%";
			setTimeout(function() {content.style.visibility = "visible"}, 200)
		} else if (s == "close") {
			// closing
			content.style.visibility = "hidden";
			UI.menu.options.style.scrollBehavior = "auto";
			UI.menu.options.scrollTop = 0;
			UI.menu.options.style.scrollBehavior = "smooth";
			st.style.animationName = "scrollbox-close";
			sb.style.animationName = "scrollbox-close";
			st.style.height = 0;
			sb.style.height = 0;
			setTimeout(function() {
				menu.style.display = "none";
				overlay.style.display = "none"
			}, 200)
		}
	},
	version: "1.1.0"
}

var UI = {
	btn: {
		new_game: null,
		launch_backup: null,
		options: null,
		data_function: {
			close: null
		}
	},
	menu: {
		new_game: null,
		launch_backup: null,
		options: null
	}
},
menu = {
	new_game: null,
	launch_backup: null,
	options: null
},
options = {
	keybinds: {
		_title_: null,
		forward: null,
		backward: null,
		left: null,
		right: null,
		console: null
	},
	display: {
		_title_: null,
		borders: null,
		animations: null
	},
	audio: {
		_title_: null,
		music: null,
		music_volume: null,
		sound: null,
		sound_volume: null
	},
	saves: {
		_title_: null,
		advanced: null
	},
	lang: {
		_title_: null,
		en_US: null,
		es_ES: null,
		fr_FR: null
	},
	about: {
		_title_: null,
		updates: null,
		credits: null
	}
};

function $(e) {return document.querySelector(e)}



window.addEventListener("load", function() {
	// buttons
	UI.btn.new_game = $(".btn-new_game");
	UI.btn.launch_backup = $(".btn-launch_backup");
	UI.btn.options = $(".btn-options");
	UI.btn.data_function.close = document.querySelectorAll(".btn[data-function='close']");
	// menus
	UI.menu.new_game = $(".menu-new_game .scrollable");
	UI.menu.launch_backup = $(".menu-launch_backup .scrollable");
	UI.menu.options = $(".menu-options .scrollable");
	// menu titles
	menu.new_game = $(".content-new_game .title");
	menu.launch_backup = $(".content-launch_backup .title");
	menu.options = $(".content-options .title");
	// options
	// keybinds
	options.keybinds._title_ = $(".keybinds .option-title");
	options.keybinds.forward = $(".keybinds .forward");
	options.keybinds.backward = $(".keybinds .backward");
	options.keybinds.left = $(".keybinds .left");
	options.keybinds.right = $(".keybinds .right");
	options.keybinds.console = $(".keybinds .console");
	// display
	options.display._title_ = $(".display .option-title");
	options.display.borders = $(".display .borders");
	options.display.animations = $(".display .animations");
	// audio
	options.audio._title_ = $(".audio .option-title");
	options.audio.music = $(".audio .music");
	options.audio.music_volume = $(".audio .music_volume");
	options.audio.sound = $(".audio .sound");
	options.audio.sound_volume =$(".audio .sound_volume");
	// saves
	options.saves._title_ = $(".saves .option-title");
	options.saves.advanced = $(".saves .advanced");
	// language
	options.lang._title_ = $(".lang .option-title");
	options.lang.en_US = $(".lang .en_US");
	options.lang.es_ES = $(".lang .es_ES");
	options.lang.fr_FR = $(".lang .fr_FR");
	// about
	options.about._title_ = $(".about .option-title");
	options.about.updates = $(".about .updates");
	options.about.credits = $(".about .credits");

	Game.init();

	document.querySelectorAll(".btn").forEach(function(e) {
		e.addEventListener("click", function() {Game.toggle_menu(this.getAttribute("data-target"), this.getAttribute("data-function"))})
	});

	document.querySelectorAll(".option-title").forEach(function(e) {
		e.addEventListener("click", function() {UI.menu.options.scrollTop = (this.parentNode.offsetTop - 40)})
	})
})