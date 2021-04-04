function quit_game() {
	// opening window
	show(Game.UI.section.quit_game);
	show(Game.UI.section.quit_game_content);

	// closing with button
	Game.UI.section.quit_game_content.querySelector(".btn-cancel").onclick = function() {
		// closing
		hide(Game.UI.section.quit_game_content);
		hide(Game.UI.section.quit_game)
	}

	// exiting the game (hmm yeah)
	Game.UI.section.quit_game_content.querySelector(".btn-ok").onclick = function() {quit(Game.UI.section.quit_game, Game.UI.section.quit_game_content)}
}