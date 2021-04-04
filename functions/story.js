function goto_level1() {
	dialog(Dialog[3]);
	setTimeout(function() {
		player.canLevelUp = true;
		dialog(Dialog[4])
	}, 10000)
}

function alt_end() {
	// alternative end
	dialog(Dialog[5]);
	setTimeout(function() {
		gameEnded = true;
		player.movement.off();
		end_credits()
	}, 4000)
}

function end() {
	// true end
	player.movement.off();
	dialog(Dialog[16]);
	document.querySelector(".diamond").style.backgroundImage = "url(assets/map/textures/empty.png)";
	setTimeout(function() {
		gameEnded = true;
		end_credits()
	}, 4000)
}



function story(lvl) {
	switch (lvl) {
		case 0:
			// lvl 0 story
			subtitle(Subtitle[0]);
			setTimeout(function() {dialog(Dialog[0])}, 4000);
			setTimeout(function() {dialog(Dialog[1], Backup.player.nickname)}, 16000);
			setTimeout(function() {dialog(Dialog[2], undefined, true, goto_level1, alt_end)}, 23000)
			break;
		case 1:
			// lvl 1 story
			setTimeout(function() {subtitle(Subtitle[1])}, 0);
			setTimeout(function() {dialog(Dialog[6])}, 0);
			break;
		case 2:
			// lvl 2 story
			setTimeout(function() {subtitle(Subtitle[2])}, 0);
			setTimeout(function() {dialog(Dialog[12])}, 0);
			break
	}
}