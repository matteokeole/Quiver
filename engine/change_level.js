function change_level(lvl, lvl_upper) {
	var isLoaded = false,
		map = document.querySelector(".game .map"),
		uppermap = document.querySelector(".game .uppermap"),
		newmap = document.createElement("section"),
		newuppermap = document.createElement("section");
	player.movement.off();
	newmap.className = "map";
	newuppermap.className = "uppermap";
	Game.UI.wrapper.game.removeChild(map);
	Game.UI.wrapper.game.removeChild(uppermap);
	Game.UI.wrapper.game.appendChild(newmap);
	Game.UI.wrapper.game.appendChild(newuppermap);
	player.speed /= 2;
	Backup.player.level += 1;
	Backup.player.pos[0] = 0;
	Backup.player.pos[1] = 0;
	Backup.player.health = Backup.player.maxhealth;
	Backup.player.mp = Backup.player.maxmp;
	reload_hud(Backup);
	player.movement.on();
	isLoaded = init(isLoaded, lvl, lvl_upper, Backup)
}