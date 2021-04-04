function set_width(e, w) {e.style.width = w + "px"}

function set_height(e, h) {e.style.height = h + "px"}

function set_scale() {
	w = window.innerWidth;
	h = window.innerHeight;
	if ((w / 16) < (h / 9)) {
		w_new = w;
		h_new = ((w / 16) * 9).toFixed(0);
		// scaling main menu
		set_width(Game.UI.wrapper.main, w_new);
		set_height(Game.UI.wrapper.main, h_new);
		// scaling new game section
		set_width(Game.UI.section.new_game, w_new);
		set_height(Game.UI.section.new_game, h_new);
		// scaling backup launching section
		set_width(Game.UI.section.launch_backup, w_new);
		set_height(Game.UI.section.launch_backup, h_new);
		// scaling credits section
		set_width(Game.UI.section.credits, w_new);
		set_height(Game.UI.section.credits, h_new);
		// scaling death menu
		set_width(Game.UI.section.death, w_new);
		set_height(Game.UI.section.death, h_new);
		// scaling pause menu
		set_width(Game.UI.wrapper.pause, w_new);
		set_height(Game.UI.wrapper.pause, h_new);
		// scaling fight menu
		set_width(Game.UI.wrapper.fight, w_new);
		set_height(Game.UI.wrapper.fight, h_new);
		// scaling game
		set_width(Game.UI.wrapper.game, w_new);
		set_height(Game.UI.wrapper.game, h_new);
		// scaling loading
		set_width(Game.UI.wrapper.loading, w_new);
		set_height(Game.UI.wrapper.loading, h_new);
		// random changes
		set_height(document.querySelector(".border.border-top"), (h - h_new) / 2);
		set_height(document.querySelector(".border.border-bottom"), (h - h_new) / 2);
		set_width(document.querySelector(".border.border-left"), (w - w_new) / 2);
		set_width(document.querySelector(".border.border-right"), (w - w_new) / 2)
	} else if ((w / 16) >= (h / 9)) {
		w_new = ((h / 9) * 16).toFixed(0);
		h_new = h;
		// scaling main menu
		set_width(Game.UI.wrapper.main, w_new);
		set_height(Game.UI.wrapper.main, h_new);
		// scaling new game section
		set_width(Game.UI.section.new_game, w_new);
		set_height(Game.UI.section.new_game, h_new);
		// scaling backup launching section
		set_width(Game.UI.section.launch_backup, w_new);
		set_height(Game.UI.section.launch_backup, h_new);
		// scaling credits section
		set_width(Game.UI.section.credits, w_new);
		set_height(Game.UI.section.credits, h_new);
		// scaling death menu
		set_width(Game.UI.section.death, w_new);
		set_height(Game.UI.section.death, h_new);
		// scaling pause menu
		set_width(Game.UI.wrapper.pause, w_new);
		set_height(Game.UI.wrapper.pause, h_new);
		// scaling fight menu
		set_width(Game.UI.wrapper.fight, w_new);
		set_height(Game.UI.wrapper.fight, h_new);
		// scaling game
		set_width(Game.UI.wrapper.game, w_new);
		set_height(Game.UI.wrapper.game, h_new);
		// scaling loading
		set_width(Game.UI.wrapper.loading, w_new);
		set_height(Game.UI.wrapper.loading, h_new);
		// random changes
		set_height(document.querySelector(".border.border-top"), (h - h_new) / 2);
		set_height(document.querySelector(".border.border-bottom"), (h - h_new) / 2);
		set_width(document.querySelector(".border.border-left"), (w - w_new) / 2);
		set_width(document.querySelector(".border.border-right"), (w - w_new) / 2)
	}
	Game.UI.tooltip.display.forEach(function(e) {e.innerHTML = "Affichage : " + w_new + "x" + h_new + " (" + Game.aspectRatio + ")"})
}



Game.scale = function() {
	var w = 0,
		h = 0,
		w_new = 0,
		h_new = 0;
	set_scale();
	window.onresize = set_scale
}