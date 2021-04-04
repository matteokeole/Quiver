function tp(x, y) {
	map.x = x;
	map.y = y;
	document.querySelector(".game .map").style.transform = "translateX(" + -map.x * 64 + "px) translateY(" + map.y * 64 + "px)";
	document.querySelector(".game .uppermap").style.transform = "translateX(" + -map.x * 64 + "px) translateY(" + map.y * 64 + "px)"
}

player.movement.on = function(backup) {
	if (backup !== undefined) {
		player.x = backup.player.pos[0];
		player.y = backup.player.pos[1]
	}
	// allowing player movement
	player.canMove.top = true;
	player.canMove.bottom = true;
	player.canMove.left = true;
	player.canMove.right = true;
	window.addEventListener("keydown", player.movement.keydown);
	window.addEventListener("keyup", player.movement.keyup)
}

player.movement.off = function() {
	// disallowing player movement
	window.removeEventListener("keydown", player.movement.keydown);
	window.removeEventListener("keyup", player.movement.keyup);
	player.canMove.top = false;
	player.canMove.bottom = false;
	player.canMove.left = false;
	player.canMove.right = false
}

player.movement.keydown = function(e) {
	if (Backup.player.class === "mage") document.querySelector("#player").style.backgroundImage = "url(assets/entity/mage_walking.gif)";
	switch (e.keyCode) {
		case 90: // z key
			(player.canMove.top) ? player.direction.top = true : player.direction.top = false;
			break;
		case 83: // s key
			(player.canMove.bottom) ? player.direction.bottom = true : player.direction.bottom = false;
			break;
		case 81: // q key
			(player.canMove.left) ? player.direction.left = true : player.direction.left = false;
			break;
		case 68: // d key
			(player.canMove.right) ? player.direction.right = true : player.direction.right = false;
			break
	}
}

player.movement.keyup = function(e) {
	if (Backup.player.class === "mage") document.querySelector("#player").style.backgroundImage = "url(assets/entity/mage_idle.gif)";
	switch (e.keyCode) {
		case 90: // z key
			player.direction.top = false;
			break;
		case 83: // s key
			player.direction.bottom = false;
			break;
		case 81: // q key
			player.direction.left = false;
			break;
		case 68: // d key
			player.direction.right = false;
			break
	}
}

player.movement.move = function() {
	with (player) {
		near(x.toFixed(1), y.toFixed(1), Backup.player.level);
		collide(x.toFixed(1), y.toFixed(1), canMove, Backup.player.level);
		if (direction.top && canMove.top) y += speed;
		if (direction.bottom && canMove.bottom) y -= speed;
		if (direction.left && canMove.left) {
			x -= speed;
			Backup.player.orientation = "left";
			document.querySelector("#player").style.transform = "rotateY(180deg)"
		}
		if (direction.right && canMove.right) {
			x += speed;
			Backup.player.orientation = "right";
			document.querySelector("#player").style.transform = "rotateY(0)"
		}

		// moving the player
		Backup.player.pos[0] = x.toFixed(1);
		Backup.player.pos[1] = y.toFixed(1);
		document.querySelector("#player").tp(x.toFixed(1), y.toFixed(1));
		// debug
		debug.log(1, "Player X: " + x.toFixed(1));
		debug.log(2, "Player Y: " + y.toFixed(1))
	}
	window.requestAnimationFrame(player.movement.move)
}