var Game = {
	init: function() {
		document.querySelector("title").innerHTML = `Quiver ${Game.version}`;
		Game.lang("en_US")
	},
	lang: function(l) {
		// param: lang name (str)
		// requesting the JSON file
		var path = `../assets/lang/${l}.json`,
			request = new XMLHttpRequest();
		request.open("GET", path);
		request.responseType = "json";
		request.send();
		request.addEventListener("load", function() {
			// the request has been
			var r = this.response[l];
			// applying the recovered language data to UI elements
			UI.btn.new_game.textContent = r.UI.btn["new_game.text"];
			UI.btn.launch_backup.textContent = r.UI.btn["launch_backup.text"];
			UI.btn.options.textContent = r.UI.btn["options.text"]
		})
	},
	open_menu: function(m) {
		// param: menu name (str)
		var o = document.querySelector(`.overlay-${m}`),
			menu = o.querySelector(".menu"),
			st = menu.querySelector(".scrollbox-top"),
			sb = menu.querySelector(".scrollbox-bottom"),
			content = menu.querySelector(".content");
		o.style.display = "flex";
		st.style.animationName = "scrollbox-open";
		sb.style.animationName = "scrollbox-open";
		st.style.height = "50%";
		sb.style.height = "50%";
		setTimeout(function() {content.style.display = "block"}, 200)
	},
	close_menu: function(m) {
		// param: menu name (str)
		var o = document.querySelector(`.overlay-${m}`),
			menu = o.querySelector(".menu"),
			st = menu.querySelector(".scrollbox-top"),
			sb = menu.querySelector(".scrollbox-bottom"),
			content = menu.querySelector(".content");
		content.style.display = "none";
		st.style.animationName = "scrollbox-close";
		sb.style.animationName = "scrollbox-close";
		st.style.height = "36px";
		sb.style.height = "36px";
		setTimeout(function() {o.style.display = "none"}, 200)
	},
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
	UI.btn.new_game = document.querySelector(".btn-new_game");
	UI.btn.launch_backup = document.querySelector(".btn-launch_backup");
	UI.btn.options = document.querySelector(".btn-options");
	Game.init();
	document.getElementById("close").onclick = function() {Game.close_menu("options")}
	UI.btn.options.addEventListener("click", function() {Game.open_menu("options")})
})