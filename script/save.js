function save() {
	var backup_content = Game.UI.section.save_content.querySelector(".backup_content#new_content");

	// backup content
	var backup_to_save =
		"Backup.name = \"" + Backup.name + "\";\n" +
		"Backup.date.creation = \"" + Backup.date.creation + "\";\n" +
		"Backup.date.lastConnection = \"" + get_date() + "\";\n" +
		"Backup.player.nickname = \"" + Backup.player.nickname + "\";\n" +
		"Backup.player.class = \"" + Backup.player.class + "\";\n" +
		"Backup.player.level = " + Backup.player.level + ";\n" +
		"Backup.player.health = " + Backup.player.health + ";\n" +
		"Backup.player.maxhealth = " + Backup.player.maxhealth + ";\n" +
		"Backup.player.mp = " + Backup.player.mp + ";\n" +
		"Backup.player.maxmp = " + Backup.player.maxmp + ";\n" +
		"Backup.player.resistance = " + Backup.player.resistance + ";\n" +
		"Backup.player.base_resistance = " + Backup.player.base_resistance + ";\n" +
		"Backup.player.pos = [" + Backup.player.pos[0] + ", " + Backup.player.pos[1] + "];\n" +
		"Backup.player.orientation = \"" + Backup.player.orientation + "\";\n" +
		"Backup.stats.kill_total = " + Backup.stats.kill_total + ";\n" +
		"Backup.stats.kill_goblin = " + Backup.stats.kill_goblin + ";\n" +
		"Backup.stats.kill_skeleton1 = " + Backup.stats.kill_skeleton1 + ";\n" +
		"Backup.stats.kill_skeleton2 = " + Backup.stats.kill_skeleton2 + ";\n" +
		"Backup.stats.flight_total = " + Backup.stats.flight_total + ";\n" +
		"Backup.stats.flight_goblin = " + Backup.stats.flight_goblin + ";\n" +
		"Backup.stats.flight_skeleton1 = " + Backup.stats.flight_skeleton1 + ";\n" +
		"Backup.stats.flight_skeleton2 = " + Backup.stats.flight_skeleton2 + ";\n" +
		"Backup.entity.Entity0.isDead = " + Backup.entity.Entity0.isDead + ";\n" +
		"Backup.entity.Entity1.isDead = " + Backup.entity.Entity1.isDead + ";\n" +
		"Backup.entity.Entity2.isDead = " + Backup.entity.Entity2.isDead + ";\n" +
		"Backup.entity.Entity3.isDead = " + Backup.entity.Entity3.isDead + ";\n" +
		"Backup.entity.Entity4.isDead = " + Backup.entity.Entity4.isDead + ";\n" +
		"Backup.entity.Entity5.isDead = " + Backup.entity.Entity5.isDead + ";\n" +
		"Backup.entity.Entity6.isDead = " + Backup.entity.Entity6.isDead + ";\n" +
		"Backup.entity.Entity0.hasFled = " + Backup.entity.Entity0.hasFled + ";\n" +
		"Backup.entity.Entity1.hasFled = " + Backup.entity.Entity1.hasFled + ";\n" +
		"Backup.entity.Entity2.hasFled = " + Backup.entity.Entity2.hasFled + ";\n" +
		"Backup.entity.Entity3.hasFled = " + Backup.entity.Entity3.hasFled + ";\n" +
		"Backup.entity.Entity4.hasFled = " + Backup.entity.Entity4.hasFled + ";\n" +
		"Backup.entity.Entity5.hasFled = " + Backup.entity.Entity5.hasFled + ";\n" +
		"Backup.entity.Entity6.hasFled = " + Backup.entity.Entity6.hasFled + ";";
	backup_content.value = backup_to_save;

	// opening window
	show(Game.UI.section.save);
	show(Game.UI.section.save_content);

	// closing with button
	Game.UI.section.save_content.querySelector(".btn-close").onclick = function() {
		hide(Game.UI.section.save_content);
		Game.UI.section.save_content.querySelector("#btn-copy").innerHTML = "Copier";
		hide(Game.UI.section.save)
	}

	// saving
	Game.UI.section.save_content.querySelector("#btn-copy").onclick = function() {
		// copying backup content
		backup_content.select();
		backup_content.setSelectionRange(0, backup_content.value.length);
		document.execCommand("copy");
		this.innerHTML = "Copie avec succes !"
	}
}