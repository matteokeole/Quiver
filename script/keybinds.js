function keybinds() {
	window.onkeydown = function(e) {
		switch (e.keyCode) {
			case 27: // echap
				with (Game.UI.section) {
					if (credits.style.display === "block" && !gameEnded) hide(credits);
					if (launch_backup.style.display === "block") {
						hide(launch_backup_content);
						hide(launch_backup);
						clearBackup(Backup)
					}
					if (new_game.style.display === "block") {
						clearBackup(Backup);
						closeNewGame(new_game_step1.querySelector(".btn-next"), new_game_step2.querySelector(".btn-create_game"))
					}
					if (options.style.display === "block") {
						hide(options_content);
						hide(options)
					}
					if (quit_game.style.display === "block") {
						hide(quit_game_content);
						hide(quit_game)
					}
					if (save.style.display === "block") {
						hide(save_content);
						save_content.querySelector("#btn-copy").innerHTML = "Copier";
						hide(save)
					}
				}
				with (Game.UI.wrapper) {
					if (pause.style.display === "block") {
						hide(pause);
						(!player.isFighting && !player.isInCredits) ? player.movement.on() : player.movement.off()
					} else {
						if (gameLaunched && !gameEnded) {
							show(pause);
							player.movement.off()
						}
					}
				}
				break;
			case 112: // f1
				(debug.style.display === "block") ? hide(debug) : show(debug);
				break
		}
	}
}