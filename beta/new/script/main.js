var Game = {
	construct: function() {
		var main = document.querySelector("main");
		// main.appendChild(UI.new.menu);
		// adding the game content in the body
		// document.body.appendChild(main)
	},
	init: function() {
		Game.construct()
	},
	scale: function() {},
	aspectRatio: "16/9",
	version: "1.1.0"
}

var UI = {
	new: {
		menu: document.createElement("section"),
	},
	play: {
		text: ""
	},
	close: {
		text: ""
	},
	next: {
		text: ""
	},
	new_game: {
		text: ""
	},
	launch_backup: {
		text: ""
	},
	credits: {
		text: ""
	},
	display: {
		text: ""
	},
	version: {
		text: ""
	}
}

var Options = {
	text: "",
	key_forward: {
		value: 0,
		text: ""
	},
	key_backward: {
		value: 0,
		text: ""
	},
	key_turnLeft: {
		value: 0,
		text: ""
	},
	key_turnRight: {
		value: 0,
		text: ""
	},
	key_console: {
		value: 0,
		text: ""
	}
}

window.addEventListener("load", Game.init)