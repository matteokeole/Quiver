function init(isLoaded, lvl, lvl_upper, backup) {
	// var COLLISIONS = [];
	document.querySelector("#player").tp = tp;

	function generate(map, uppermap) {
		var part, upperpart;
		for (i = 0; i < map.length -1; i++) {
			part = document.createElement("div");
			part.className = "part " + map[i][0];
			(map[i][1] != "") ? part.style.backgroundImage = "url(assets/map/" + map[i][1] + ".png)" : part.style.backgroundImage = "url(assets/map/error.png)";
			part.style.width = map[i][2][0] * 64 + "px";
			part.style.height = map[i][2][1] * 64 + "px";
			part.setpos = setpos;
			part.setpos(map[i][3][0], map[i][3][1], 0);
			part.style.zIndex = i;
			document.querySelector(".game .map").append(part)
		}
		for (j = 0; j < uppermap.length; j++) {
			upperpart = document.createElement("div");
			upperpart.className = "upperpart " + uppermap[j][0];
			(uppermap[j][1] != "") ? upperpart.style.backgroundImage = "url(assets/map/" + uppermap[j][1] + ".png)" : upperpart.style.backgroundImage = "url(assets/map/error.png)";
			upperpart.style.width = uppermap[j][2][0] * 64 + "px";
			upperpart.style.height = uppermap[j][2][1] * 64 + "px";
			upperpart.setpos = setpos;
			upperpart.setpos(uppermap[j][3][0], uppermap[j][3][1], 0);
			document.querySelector(".game .uppermap").append(upperpart)
		}
		show(document.querySelector(".game .map"), "flex");
		show(document.querySelector(".game .uppermap"), "flex");
		isLoaded = true
	}

	function generate_entity(map) {
		var entity,
			entity_map = map[map.length - 1];
		if (entity_map !== "noentity") {
			for (i = 0; i < entity_map.length; i++) {
				entity = document.createElement("div");
				entity.className = "entity " + entity_map[i][0];
				(entity_map[i][1] != "") ? entity.style.backgroundImage = "url(assets/entity/" + entity_map[i][1] + ".png)" : entity.style.backgroundImage = "url(assets/entity/something.png)";
				entity.setpos = setpos;
				entity.setpos(entity_map[i][2][0], entity_map[i][2][1], 0);
				if (entity_map[i][3] === "left") entity.style.transform += " rotateY(180deg)";
				document.querySelector(".game .map").append(entity)
			}
		}
	}

	function hide_dead_entities(backup) {
		for (i = 0; i < 7; i++) {
			if (document.querySelector(".map .entity" + i) !== null) {
				// the entity is in the current level
				if (backup.entity["Entity" + i].isDead || backup.entity["Entity" + i].hasFled) hide_entity(backup.entity["Entity" + i])
			}
		}
	}

	function hide_entity(entity) {document.querySelector(".map .entity" + entity.index).style.backgroundImage = "url(assets/map/empty.png)"}

	// not used
	function setRandomBg(part, slot, find, bg) {
		// applyies a random background image (only if the class is found in the list of classes of the object)
		// to increase the chance of getting a certain background, enter its name several times in the background list
		// example: ["brick", "brick", "brick", "mossybrick"] will spawn a mossy brick background once in 4
		if (part.className.split(" ")[1] === find) slot.style.backgroundImage = "url(assets/map/" + bg[Math.floor(bg.length * Math.random())] + ".png)"
	}

	function setpos(x, y, h) {
		// h = slot depth
		(!h) ? h = 0 : h;
		this.style.transform = "translateX(" + x * 64 + "px) translateY(" + (-y * 64 + -h * 64) + "px)"
	}



	generate(lvl, lvl_upper);
	generate_entity(lvl);
	hide_dead_entities(backup);
	// enabling player movement
	player.movement.on(backup);
	window.requestAnimationFrame(player.movement.move);
	story(backup.player.level);

	return isLoaded
}

function ungenerate(map) {
	// player.movement.off();
	// window.cancelAnimationFrame(player.movement.move);
	// hide(map);
	// hide(document.querySelector(".game"))
	location.reload()
}