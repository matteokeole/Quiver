var Game = {
	init: function() {
		$("title").textContent = `Quiver ${Game.version}`; // updating the window title with the last version
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
			// the request has been accepted, recovering file content and changing the default language of the page
			var r = this.response[l];
			document.querySelector("html").setAttribute("lang", r["lang"]);
			// applying the recovered language data to UI elements
			// unique buttons
			UI.btn.play.textContent = r["play.text"];
			UI.btn.options.textContent = r["options.text"];
			// menu titles
			menu.play.textContent = r["play.text"];
			menu.options.textContent = r["options.text"];
			// play menu
			// new game section
			play.new_game.subtitle.textContent = r["new_game.text"];
			play.new_game.play.textContent = r["new_game.text"];
			// launch backup section
			play.launch_backup.subtitle.textContent = r["launch_backup.text"];
			play.launch_backup.open.textContent = r["open_backup.text"];
			play.launch_backup.launch.textContent = r["launch_backup.text"];
			// option menu
			// keybind settings
			options.keybinds.subtitle.textContent = r.options["keybinds.text"];
			options.keybinds.forward.textContent = r.options["keybinds:forward.text"];
			options.keybinds.backward.textContent = r.options["keybinds:backward.text"];
			options.keybinds.left.textContent = r.options["keybinds:left.text"];
			options.keybinds.right.textContent = r.options["keybinds:right.text"];
			options.keybinds.console.textContent = r.options["keybinds:console.text"];
			// display settings
			options.display.subtitle.textContent = r.options["display.text"];
			options.display.borders.textContent = r.options["display:borders.text"];
			options.display.animations.textContent = r.options["display:animations.text"];
			// audio settings
			options.audio.subtitle.textContent = r.options["audio.text"];
			options.audio.music.textContent = r.options["audio:music.text"];
			options.audio.sound.textContent = r.options["audio:sound.text"];
			// savey settings
			options.saves.subtitle.textContent = r.options["saves.text"];
			options.saves.advanced.textContent = r.options["saves:advanced.text"];
			// language settings
			options.lang.subtitle.textContent = r.options["lang.text"];
			options.lang.en_US.textContent = r.options["lang:en_US.text"];
			options.lang.es_ES.textContent = r.options["lang:es_ES.text"];
			options.lang.fr_FR.textContent = r.options["lang:fr_FR.text"];
			// about settings
			options.about.subtitle.textContent = r.options["about.text"];
			options.about.updates.textContent = r.options["about:updates.text"];
			options.about.credits.textContent = r.options["about:credits.text"]
		})
	},
	launch_new_game: function() {
		Game.toggle_menu("menu-play", "close");
		alert("Creating a new game...")
	},
	open_backup: function(e) {
		var file = e.target.files[0];
		if (!file) return;
		var reader = new FileReader();
		reader.onload = function(e) {
			// opening
			var backup = UI.menu.play.querySelector(".container-backup");
			backup.style.visibility = "visible";
			backup.querySelector(".backup").value = e.target.result; // showing backup content
			// getting the backup content as a js object
			var script_backup = document.createElement("script");
			script_backup.setAttribute("type", "text/javascript");
			script_backup.innerHTML = e.target.result;
			document.head.append(script_backup);
			/*UI.menu.play.querySelector(".backup_info").textContent = "\"" +
				Backup.name + "\" - " +
				Backup.player.nickname + " (niveau " +
				Backup.player.level + ", " +
				Backup.player.class + ")<br>Creee le : " +
				convert_date(Backup.date.creation) + "<br>Derniere connexion le : " +
				convert_date(Backup.date.lastConnection)*/
		}
		reader.readAsText(file)
	},
	launch_backup: function() {
		Game.toggle_menu("menu-play", "close");
		alert("Launching a backup...")
	},
	toggle_menu: function(m, s) {
		// m: menu name (str)
		// s: status (1: open or 0: close)
		var overlay = document.querySelector(".overlay"),
			menu = overlay.querySelector(`.${m}`),
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
			st.style["-webkit-animation-name"] = "scrollbox-open";
			st.style["animation-name"] = "scrollbox-open";
			st.style.height = "50%";
			sb.style["-webkit-animation-name"] = "scrollbox-open";
			sb.style["animation-name"] = "scrollbox-open";
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
			st.style["-webkit-animation-name"] = "scrollbox-close";
			st.style["animation-name"] = "scrollbox-close";
			st.style.height = 0;
			sb.style["-webkit-animation-name"] = "scrollbox-close";
			sb.style["animation-name"] = "scrollbox-close";
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

var play = {
	new_game: {
		subtitle: null,
		play: null
	},
	launch_backup: {
		subtitle: null,
		open: null,
		backup_info: null,
		launch: null
	}
};

var options = {
	keybinds: {
		subtitle: null,
		forward: null,
		backward: null,
		left: null,
		right: null,
		console: null
	},
	display: {
		subtitle: null,
		borders: null,
		animations: null
	},
	audio: {
		subtitle: null,
		music: null,
		sound: null
	},
	saves: {
		subtitle: null,
		advanced: null
	},
	lang: {
		subtitle: null,
		en_US: null,
		es_ES: null,
		fr_FR: null
	},
	about: {
		subtitle: null,
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

function convert_date(date) {
	date = date.split("-");
	var new_date = date[2] + "/" + date[1] + "/" + date[0] + " a " + date[3] + ":" + date[4] + ":" + date[5];
	return new_date
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
	// play menu
	// new game section
	play.new_game.subtitle = $(".new_game .subtitle");
	play.new_game.play = $(".new_game .btn-play");
	// launch backup section
	play.launch_backup.subtitle = $(".launch_backup .subtitle");
	play.launch_backup.open = $(".launch_backup .btn-open_backup");
	play.launch_backup.backup_info = $(".launch_backup .backup_info");
	play.launch_backup.launch = $(".launch_backup .btn-launch_backup");
	// option menu
	// keybind settings
	options.keybinds.subtitle = $(".keybinds .subtitle");
	options.keybinds.forward = $(".keybinds .forward");
	options.keybinds.backward = $(".keybinds .backward");
	options.keybinds.left = $(".keybinds .left");
	options.keybinds.right = $(".keybinds .right");
	options.keybinds.console = $(".keybinds .console");
	// display settings
	options.display.subtitle = $(".display .subtitle");
	options.display.borders = $(".display .borders");
	options.display.animations = $(".display .animations");
	// audio settings
	options.audio.subtitle = $(".audio .subtitle");
	options.audio.music = $(".audio .music");
	options.audio.sound = $(".audio .sound");
	// save settings
	options.saves.subtitle = $(".saves .subtitle");
	options.saves.advanced = $(".saves .advanced");
	// language settings
	options.lang.subtitle = $(".lang .subtitle");
	options.lang.en_US = $(".lang .en_US");
	options.lang.es_ES = $(".lang .es_ES");
	options.lang.fr_FR = $(".lang .fr_FR");
	// about settings
	options.about.subtitle = $(".about .subtitle");
	options.about.updates = $(".about .updates");
	options.about.credits = $(".about .credits");

	Game.init();

	document.querySelectorAll(".btn[data-function]").forEach(function(e) {
		e.addEventListener("click", function() {Game.toggle_menu(this.getAttribute("data-target"), this.getAttribute("data-function"))})
	});
	play.new_game.play.addEventListener("click", function() {Game.launch_new_game()});
	play.launch_backup.open.addEventListener("change", Game.open_backup);
	play.launch_backup.launch.addEventListener("click", function() {Game.launch_backup()});

	document.querySelectorAll(".option-title").forEach(function(e) {
		e.addEventListener("click", function() {UI.menu.options.scrollTop = (this.parentNode.offsetTop - 40)})
	})
})