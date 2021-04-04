function new_game() {
	with (Game.UI.section) {
		var game_name = new_game_step1.querySelector("#game_name"),
			player_nickname = new_game_step1.querySelector("#player_nickname"),
			game_name_ok = false,
			player_nickname_ok = false,
			btn_next = new_game_step1.querySelector(".btn-next"),
			btn_create = new_game_step2.querySelector(".btn-create_game");

		// opening window & step 1 init
		show(new_game);
		show(new_game_content);
		step1();

		function step1() {
			show(new_game_step1);
			// checking game name
			game_name.addEventListener("keyup", function() {
				if (/^\s+$/.test(game_name.value) || game_name.value.length === 0) {
					// game name is composed only of whitespaces, or is empty
					game_name_ok = false;
					btn_next.disabled = true;
					btn_next.className = "btn btn-next btn-disabled";
					btn_next.removeEventListener("click", step2)
				} else game_name_ok = true; // no errors
				if (game_name_ok && player_nickname_ok) {
					btn_next.disabled = false;
					btn_next.className = "btn btn-next";
					btn_next.addEventListener("click", step2)
				}
			});
			// checking player nickname
			player_nickname.addEventListener("keyup", function() {
				if (/^\s+$/.test(player_nickname.value) || player_nickname.value.length === 0) {
					// player nickname is composed only of whitespaces, or is empty
					player_nickname_ok = false;
					btn_next.disabled = true;
					btn_next.className = "btn btn-next btn-disabled";
					btn_next.removeEventListener("click", step2)
				} else player_nickname_ok = true; // no errors
				if (game_name_ok && player_nickname_ok) {
					btn_next.disabled = false;
					btn_next.className = "btn btn-next";
					btn_next.addEventListener("click", step2)
				}
			})
		}

		function step2() {
			game_name_ok = false;
			player_nickname_ok = false;
			// recovering step 1 data and step 2 init
			Backup.name = game_name.value;
			Backup.player.nickname = player_nickname.value;
			// hiding step 1 & showing step 2
			hide(new_game_step1);
			show(new_game_step2);
			// selecting a class
			new_game_step2.querySelectorAll(".btn-class").forEach(function(e) {
				e.onclick = function() {
					class_info(e);
					switch (this.innerHTML) {
						case "Mage":
							Backup.player.class = "mage";
							break;
						case "Roublard":
							Backup.player.class = "rogue";
							break;
						case "Paladin":
							Backup.player.class = "paladin";
							break
					}
					btn_create.disabled = false;
					btn_create.className = "btn btn-create_game";
					new_game_step2.querySelectorAll(".btn-class").forEach(function(e) {e.className = "btn btn-class"});
					this.className = "btn btn-class btn-class_selected";
					btn_create.addEventListener("click", create_game)
				}
			});
			function create_game() {
				// creating new game
				Backup.date.creation = get_date();
				Backup.date.lastConnection = Backup.date.creation;
				closeNewGame(btn_next, btn_create);
				launch(Backup)
			}
		}

		// closing with button
		new_game_content.querySelector(".b1").onclick = function() {
			clearBackup(Backup);
			closeNewGame(btn_next, btn_create)
		}
	}
}

function closeNewGame(btn_next, btn_create) {
	// clearing all data and disabling Next/Create buttons
	hide(Game.UI.section.new_game);
	game_name_ok = false;
	player_nickname_ok = false;
	btn_next.disabled = true;
	btn_create.disabled = true;
	clear(game_name);
	clear(player_nickname);
	btn_next.className = "btn btn-next btn-disabled";
	btn_create.className = "btn btn-create_game btn-disabled";
	// closing window
	hide(document.querySelector(".class_info"));
	hide(Game.UI.section.new_game_content);
	hide(Game.UI.section.new_game_step2);
	Game.UI.section.new_game_step2.querySelectorAll(".btn-class").forEach(function(e) {e.className = "btn btn-class"})
}