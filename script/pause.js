function pause() {
	var pause_menu = Game.UI.wrapper.pause.querySelector(".menu");

	pause_menu.querySelector("#btn-continue").onclick = function() {
		hide(Game.UI.wrapper.pause);
		if (!player.isFighting && !player.isInCredits) {
			// the player is not fighting an enemy or is not in the credits menu and can move
			player.movement.on()
		}
	};
	pause_menu.querySelector("#btn-save").onclick = save;
	pause_menu.querySelector(".btn-options").onclick = options;
	pause_menu.querySelector("#btn-quit_game").onclick = quit_game
}