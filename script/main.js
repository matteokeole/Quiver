var Game = {
	construct: function() {},
	init: function() {
		document.querySelector("title").innerHTML = "Quiver " + Game.version
	},
	lang: function(l) {
		var path = `../assets/lang/${l}.json`,
			request = new XMLHttpRequest();
		request.open("GET", path);
		request.responseType = "json";
		request.send();
		request.addEventListener("load", function() {
			var r = request.response;
			// applying the recovered language data to UI elements
			UI.btn.new_game.innerHTML = r.en_US["UI.btn.new_game.text"];
			UI.btn.launch_backup.innerHTML = r.en_US["UI.btn.launch_backup.text"];
			UI.btn.options.innerHTML = r.en_US["UI.btn.options.text"]
		})
	},
	aspectRatio: "16/9",
	version: "1.1.0"
}

var UI = {
	btn: {
		new_game: null,
		launch_backup: null,
		options: null
	}
}

window.addEventListener("load", function() {
	with (UI) {
		with (btn) {
			new_game = document.querySelector(".btn-new_game");
			launch_backup = document.querySelector(".btn-launch_backup");
			options = document.querySelector(".btn-options")
		}
	}
	Game.init();
	Game.lang("en_US")
})