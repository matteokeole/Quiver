function launch_backup(e) {
	var file = e.target.files[0];
	if (!file) return;
	var reader = new FileReader();
	reader.onload = function(e) {
		// opening
		show(Game.UI.section.launch_backup);
		show(Game.UI.section.launch_backup_content);

		// closing with button
		Game.UI.section.launch_backup_content.querySelector(".btn-close").onclick = function() {
			hide(Game.UI.section.launch_backup_content);
			hide(Game.UI.section.launch_backup);
			clearBackup(Backup)
		}

		// showing backup content/global info
		Game.UI.section.launch_backup_content.querySelector(".backup_content#old_content").value = e.target.result;
		var script_backup = document.createElement("script");
		script_backup.setAttribute("type", "text/javascript");
		script_backup.innerHTML = e.target.result;
		document.head.append(script_backup);
		Game.UI.section.launch_backup_content.querySelector(".backup_info").innerHTML = "\"" +
			Backup.name + "\" - " +
			Backup.player.nickname + " (niveau " +
			Backup.player.level + ", " +
			Backup.player.class + ")<br>Creee le : " +
			convert_date(Backup.date.creation) + "<br>Derniere connexion le : " +
			convert_date(Backup.date.lastConnection)
	}
	reader.readAsText(file);

	// confirmation
	Game.UI.section.launch_backup_content.querySelector("#btn-confirm_backup").onclick = function() {
		// closing
		hide(Game.UI.section.launch_backup_content);
		hide(Game.UI.section.launch_backup);
		launch(Backup)
	}
}