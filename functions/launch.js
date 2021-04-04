function launch(backup) {
	var isLoaded = false,
		player = document.querySelector(".game #player");

	// opening loading screen
	show(Game.UI.wrapper.loading);

	if (backup.player.level === null) {
		// this is a new game, setting level to 0 (lobby)
		backup.player.level = 0;
		// initializing basic data
		backup.player.pos = [0, 0];
		backup.player.orientation = "right";
		backup.stats.kill_total = 0;
		backup.stats.kill_goblin = 0;
		backup.stats.kill_skeleton1 = 0;
		backup.stats.kill_skeleton2 = 0;
		backup.stats.flight_total = 0;
		backup.stats.flight_goblin = 0;
		backup.stats.flight_skeleton1 = 0;
		backup.stats.flight_skeleton2 = 0;
		// initializing class data
		switch (backup.player.class) {
			case "mage":
				backup.player.health = Entity.Mage.health;
				backup.player.maxhealth = Entity.Mage.health;
				backup.player.mp = Entity.Mage.mp;
				backup.player.maxmp = Entity.Mage.mp;
				backup.player.resistance = Entity.Mage.resistance;
				backup.player.base_resistance = Entity.Mage.resistance;
				break;
			case "rogue":
				backup.player.health = Entity.Rogue.health;
				backup.player.maxhealth = Entity.Rogue.health;
				backup.player.mp = Entity.Rogue.mp;
				backup.player.maxmp = Entity.Rogue.mp;
				backup.player.resistance = Entity.Rogue.resistance;
				backup.player.base_resistance = Entity.Rogue.resistance;
				break;
			case "paladin":
				backup.player.health = Entity.Paladin.health;
				backup.player.maxhealth = Entity.Paladin.health;
				backup.player.mp = Entity.Paladin.mp;
				backup.player.maxmp = Entity.Paladin.mp;
				backup.player.resistance = Entity.Paladin.resistance;
				backup.player.base_resistance = Entity.Paladin.resistance;
				break
		}
	}
	// this is a backup, opening current level
	switch (backup.player.level) {
		case 0:
			// launch lvl_0
			isLoaded = init(isLoaded, lvl_0, lvl_0_upper, Backup);
			break;
		case 1:
			// launch lvl_1
			isLoaded = init(isLoaded, lvl_1, lvl_1_upper, Backup);
			break;
		case 2:
			// launch lvl_2
			isLoaded = init(isLoaded, lvl_2, lvl_2_upper, Backup);
			break
	}
	// initializing hud data
	with (Game.UI.section) {
		hud.querySelector(".health progress").setAttribute("max", backup.player.maxhealth);
		hud.querySelector(".mp progress").setAttribute("max", backup.player.maxmp);
		mini_hud.querySelector(".health progress").setAttribute("max", backup.player.maxhealth);
		mini_hud.querySelector(".mp progress").setAttribute("max", backup.player.maxmp)
	}
	reload_hud(backup);
	// initializing avatar texture
	player.style.backgroundImage = "url(assets/entity/" + backup.player.class + ".png)";
	(backup.player.orientation === "left") ? player.style.transform = "rotateY(180deg)" : player.style.transform = "rotateY(0)";
	// opening the game when fully loaded
	if (isLoaded) {
		hide(Game.UI.wrapper.main);
		show(Game.UI.wrapper.game, "flex");
		gameLaunched = true;
		setTimeout(function() {
			hide(Game.UI.wrapper.loading);
			pause() // pause menu
		}, 2750)
	}
}