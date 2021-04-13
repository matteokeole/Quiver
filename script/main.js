var Game = {
	init: function() {
		$("title").textContent = `Quiver ${Game.version}`; // updating the title with the last version
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
			UI.btn.play.textContent = r["play.text"];
			UI.btn.options.textContent = r["options.text"];
			// menu titles
			menu.play.textContent = r["play.text"];
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
			options.audio.sound.textContent = r.options["audio:sound.text"];
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
			overlay.style["-webkit-animation-name"] = "overlay_fade_in";
			overlay.style["animation-name"] = "overlay_fade_in";
			overlay.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
			menu.style.display = "flex";
			st.style.animationName = "scrollbox-open";
			sb.style.animationName = "scrollbox-open";
			st.style.height = "50%";
			sb.style.height = "50%";
			UI.menu.options.style.scrollBehavior = "auto";
			UI.menu.options.scrollTop = 0;
			UI.menu.options.style.scrollBehavior = "smooth";
			setTimeout(function() {
				content.style.visibility = "visible";
				document.addEventListener("keydown", esc)
			}, 200)
		} else if (s == "close") {
			// closing with button
			document.removeEventListener("keydown", esc);
			content.style.visibility = "hidden";
			st.style.animationName = "scrollbox-close";
			sb.style.animationName = "scrollbox-close";
			st.style.height = 0;
			sb.style.height = 0;
			overlay.style["-webkit-animation-name"] = "overlay_fade_out";
			overlay.style["animation-name"] = "overlay_fade_out";
			overlay.style.backgroundColor = "transparent";
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
		play: null,
		options: null,
		data_function: {
			close: null
		}
	},
	menu: {
		play: null,
		options: null
	}
};

var menu = {
	play: null,
	options: null
};

var options = {
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
		sound: null
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

function esc(e) {
	var raw_menus = ["menu-play", "menu-options"];
	if (e.keyCode == 27) {
		for (i = 0; i < raw_menus.length; i++) {
			if (document.querySelector(`.${raw_menus[i]}`).style.display == "flex") Game.toggle_menu(raw_menus[i], "close")
		}
	}
}



window.addEventListener("load", function() {
	// buttons
	UI.btn.play = $(".btn-play");
	UI.btn.options = $(".btn-options");
	UI.btn.data_function.close = document.querySelectorAll(".btn[data-function='close']");
	// menus
	UI.menu.play = $(".menu-play .scrollable");
	UI.menu.options = $(".menu-options .scrollable");
	// menu titles
	menu.play = $(".content-play .title");
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
	options.audio.sound = $(".audio .sound");
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