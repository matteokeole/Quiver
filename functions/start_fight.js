// hud functions
function reload_hud(backup) {
	var health_span = "Sante (" + backup.player.health + "/" + backup.player.maxhealth + ")",
		mp_span = "Mana (" + backup.player.mp + "/" + backup.player.maxmp + ")";

	with (Game.UI.section) {
		// reloading hud
		hud.querySelector("#hud_nickname").innerHTML = backup.player.nickname;
		hud.querySelector(".health span").innerHTML = health_span;
		hud.querySelector(".health progress").setAttribute("value", backup.player.health);
		hud.querySelector(".mp span").innerHTML = mp_span;
		hud.querySelector(".mp progress").setAttribute("value", backup.player.mp)
		// reloading mini hud
		mini_hud.querySelector(".health span").innerHTML = health_span;
		mini_hud.querySelector(".health progress").setAttribute("value", backup.player.health);
		mini_hud.querySelector(".mp span").innerHTML = mp_span;
		mini_hud.querySelector(".mp progress").setAttribute("value", backup.player.mp)
	}
}

function reload_enemy_hud(enemy) {
	var enemy_health_span = "Sante (" + enemy.health + "/" + enemy.max_health + ")";

	with (Game.UI.section) {
		mini_hud.querySelector(".enemy_health span").innerHTML = enemy_health_span;
		mini_hud.querySelector(".enemy_health progress").setAttribute("max", enemy.max_health);
		mini_hud.querySelector(".enemy_health progress").setAttribute("value", enemy.health)
	}
}



// fight system
function start_fight(backup, enemy) {
	var fight = Game.UI.wrapper.fight,
		player_avatar = fight.querySelector(".you img"),
		attack_name = fight.querySelector(".attack_name"),
		enemy_attack_name = fight.querySelector(".enemy_attack_name"),
		actions = fight.querySelector(".actions"),
		attack1 = actions.querySelector(".attack1"),
		attack2 = actions.querySelector(".attack2"),
		ult = actions.querySelector(".ult"),
		flee = actions.querySelector(".flee"),
		mini_hud = Game.UI.section.mini_hud;

	// initializing fight menu with current enemy
	player.isFighting = true;
	fight.querySelector(".title").innerHTML = "FIGHT !";
	fight.querySelector(".enemy img").setAttribute("src", enemy.texture);
	switch (backup.player.class) {
		case "mage":
			player_avatar.setAttribute("src", Entity.Mage.texture);
			attack1.innerHTML = Entity.Mage.attack1.name;
			attack2.innerHTML = Entity.Mage.attack2.name;
			ult.innerHTML = Entity.Mage.ult.name;
			attack1.onclick = function() {attack(Entity.Mage.attack1)}
			attack2.onclick = function() {attack(Entity.Mage.attack2)}
			ult.onclick = function() {attack(Entity.Mage.ult)}
			break;
		case "rogue":
			player_avatar.setAttribute("src", Entity.Rogue.texture);
			attack1.innerHTML = Entity.Rogue.attack1.name;
			attack2.innerHTML = Entity.Rogue.attack2.name;
			ult.innerHTML = Entity.Rogue.ult.name;
			attack1.onclick = function() {attack(Entity.Rogue.attack1)}
			attack2.onclick = function() {attack(Entity.Rogue.attack2)}
			ult.onclick = function() {attack(Entity.Rogue.ult)}
			break;
		case "paladin":
			player_avatar.setAttribute("src", Entity.Paladin.texture);
			attack1.innerHTML = Entity.Paladin.attack1.name;
			attack2.innerHTML = Entity.Paladin.attack2.name;
			ult.innerHTML = Entity.Paladin.ult.name;
			attack1.onclick = function() {attack(Entity.Paladin.attack1)}
			attack2.onclick = function() {attack(Entity.Paladin.attack2)}
			ult.onclick = function() {attack(Entity.Paladin.ult)}
			break
	}
	reload_hud(Backup);
	reload_enemy_hud(enemy);
	show(actions);
	show(fight);

	flee.onclick = function() {
		if (confirm("Etes-vous sur(e) de vouloir fuir ? Vous recupererez tout votre mana mais vous perdrez 8 points de vie.")) {
			// player is fleeing the enemy
			hide(fight);
			player.isFighting = false;
			backup.player.health -= 8;
			backup.player.mp = backup.player.maxmp;
			reload_hud(backup);
			if (backup.player.health <= 0) death();
			else {
				// player is alive
				player.movement.on()
			}
		}
	}

	function attack(ability) {
		// attack
		if (backup.player.mp - ability.cost < 0) alert("Vous n'avez pas assez de mana pour cette attaque.");
		else {
			// enough mana to attack
			hide(actions);
			backup.player.mp -= ability.cost;
			reload_hud(backup);
			var block = test_enemy_block(enemy);
			if (!block) {
				// attack not blocked
				attack_name.innerHTML = ability.name;
				show(attack_name);
				setTimeout(function() {hide(attack_name)}, 3000);
				switch (ability.id) {
					// exceptions
					case "lightning":
						// mage ult
						if (enemy.index < 3) {
							// level 1
							(backup.entity.Entity0.health - Entity.Mage.ult.damage <= 0) ? backup.entity.Entity0.health = 1 : backup.entity.Entity0.health -= Entity.Mage.ult.damage;
							(backup.entity.Entity1.health - Entity.Mage.ult.damage <= 0) ? backup.entity.Entity1.health = 1 : backup.entity.Entity1.health -= Entity.Mage.ult.damage;
							(backup.entity.Entity2.health - Entity.Mage.ult.damage <= 0) ? backup.entity.Entity2.health = 1 : backup.entity.Entity2.health -= Entity.Mage.ult.damage;
							reload_enemy_hud(enemy);
							(enemy.health <= 0) ? enemy_dead(enemy, backup) : enemy_attack(enemy, backup)
						} else {
							// level 2
							(backup.entity.Entity3.health - Entity.Mage.ult.damage <= 0) ? backup.entity.Entity3.health = 1 : backup.entity.Entity3.health -= Entity.Mage.ult.damage;
							(backup.entity.Entity4.health - Entity.Mage.ult.damage <= 0) ? backup.entity.Entity4.health = 1 : backup.entity.Entity4.health -= Entity.Mage.ult.damage;
							(backup.entity.Entity5.health - Entity.Mage.ult.damage <= 0) ? backup.entity.Entity5.health = 1 : backup.entity.Entity5.health -= Entity.Mage.ult.damage;
							(backup.entity.Entity6.health - Entity.Mage.ult.damage <= 0) ? backup.entity.Entity6.health = 1 : backup.entity.Entity6.health -= Entity.Mage.ult.damage;
							reload_enemy_hud(enemy);
							(enemy.health <= 0) ? enemy_dead(enemy, backup) : enemy_attack(enemy, backup)
						}
						break;
					case "doubledaggers":
						// rogue first ability
						enemy.health -= ability.damage;
						Entity.Rogue.attack1.damage = Entity.Rogue.attack1.base_damage; // clearing damage multiplier due to rogue's ult
						reload_enemy_hud(enemy);
						(enemy.health <= 0) ? enemy_dead(enemy, backup) : enemy_attack(enemy, backup);
						break;
					case "discretion":
						// rogue second ability
						Entity.Rogue.attack1.damage = Entity.Rogue.attack1.base_damage; // clearing damage multiplier due to rogue's ult
						enemy.isScared = true;
						enemy_attack(enemy, backup);
						break;
					case "stealth":
						// rogue ult
						Entity.Rogue.ult.damageMult = true;
						Entity.Rogue.attack1.damage = Entity.Rogue.attack1.boost_damage;
						enemy_attack(enemy, backup);
						break;
					case "sword":
						// paladin first ability
						backup.player.resistance = backup.player.base_resistance; // clearing damage multiplier due to paladin's second attack
						enemy.health -= ability.damage;
						reload_enemy_hud(enemy);
						(enemy.health <= 0) ? enemy_dead(enemy, backup) : enemy_attack(enemy, backup);
						break;
					case "parade":
						// paladin second ability
						backup.player.resistance = 20; // 20 = max resistance
						enemy_attack(enemy, backup);
						break;
					case "regeneration":
						// paladin ult
						backup.player.resistance = backup.player.base_resistance; // clearing damage multiplier due to paladin's second attack
						(backup.player.health + 5 > backup.player.maxhealth) ? backup.player.health = backup.player.maxhealth : backup.player.health += 5;
						reload_hud(backup);
						enemy_attack(enemy, backup);
						break;
					// all others abilities
					default:
						enemy.health -= ability.damage;
						reload_enemy_hud(enemy);
						(enemy.health <= 0) ? enemy_dead(enemy, backup) : enemy_attack(enemy, backup);
						break
				}
			}
		}
	}

	function test_enemy_block(enemy) {
		var block = Math.floor(20 * Math.random() + 1);
		(block <= enemy.resistance) ? enemy.hasBlocked = true : enemy.hasBlocked = false;
		if (enemy.hasBlocked) {
			enemy_attack_name.innerHTML = "Attaque paree !";
			show(enemy_attack_name);
			setTimeout(function() {hide(enemy_attack_name)}, 3000);
			enemy_attack(enemy, backup)
		}
		return enemy.hasBlocked
	}

	function test_player_block(backup) {
		var block = Math.floor(20 * Math.random() + 1);
		(block + 1 <= backup.player.resistance) ? backup.player.hasBlocked = true : backup.player.hasBlocked = false;
		if (backup.player.hasBlocked) {
			attack_name.innerHTML = "Vous parez !";
			show(attack_name);
			setTimeout(function() {
				hide(attack_name);
				show(actions)
			}, 3000)
		}
		return backup.player.hasBlocked
	}

	function enemy_attack(enemy, backup) {
		setTimeout(function() {
			var block = test_player_block(backup),
				reaction = Math.floor(6 * Math.random() + 1);
			if (!block) {
				// attack not blocked
				switch (enemy.id) {
					case "goblin":
						// goblin, 2 attacks
						if (reaction === 5) enemy_heal(enemy, backup);
						else if ((reaction === 6) || (reaction === 4 && enemy.isScared)) enemy_flee(enemy, backup);
						else {
							enemy.isScared = false;
							var attack_choice = Math.floor(2 * Math.random() + 1);
							if (attack_choice === 1) {
								enemy_attack_name.innerHTML = Entity.Goblin.attack1.name;
								show(enemy_attack_name);
								setTimeout(function() {
									hide(enemy_attack_name);
									show(actions)
								}, 3000);
								(backup.player.health - Entity.Goblin.attack1.damage <= 0) ? death() : backup.player.health -= Entity.Goblin.attack1.damage;
								reload_hud(backup)
							} else {
								enemy_attack_name.innerHTML = Entity.Goblin.attack2.name;
								show(enemy_attack_name);
								setTimeout(function() {
									hide(enemy_attack_name);
									show(actions)
								}, 3000);
								(backup.player.health - Entity.Goblin.attack2.damage <= 0) ? death() : backup.player.health -= Entity.Goblin.attack2.damage;
								reload_hud(backup)
							}
						}
						break;
					case "skeleton1":
						// warrior skeleton, only 1 attack
						if (reaction === 6) enemy_heal(enemy, backup);
						else if (reaction === 5 && enemy.isScared) enemy_flee(enemy, backup);
						else {
							enemy.isScared = false;
							enemy_attack_name.innerHTML = Entity.Skeleton1.attack1.name;
							show(enemy_attack_name);
							setTimeout(function() {
								hide(enemy_attack_name);
								show(actions)
							}, 3000);
							(backup.player.health - Entity.Skeleton1.attack1.damage <= 0) ? death() : backup.player.health -= Entity.Skeleton1.attack1.damage;
							reload_hud(backup)
						}
						break;
					case "skeleton2":
						// archer skeleton, only 1 attack
						if ((reaction === 6) || (reaction === 5 && enemy.isScared)) enemy_flee(enemy, backup);
						else {
							enemy.isScared = false;
							enemy_attack_name.innerHTML = Entity.Skeleton2.attack1.name;
							show(enemy_attack_name);
							setTimeout(function() {
								hide(enemy_attack_name);
								show(actions)
							}, 3000);
							(backup.player.health - Entity.Skeleton2.attack1.damage <= 0) ? death() : backup.player.health -= Entity.Skeleton2.attack1.damage;
							reload_hud(backup)
						}
						break
				}
			}
		}, 3000)
	}

	function enemy_heal(enemy) { 
		// enemy heals himself
		enemy.isScared = false;
		if (enemy.health + enemy.heal > enemy.max_health) enemy.health = enemy.max_health;
		else enemy.health += enemy.heal;
		reload_enemy_hud(enemy)
		enemy_attack_name.innerHTML = "Regeneration (+" + enemy.heal + ")";
		show(enemy_attack_name);
		setTimeout(function() {
			hide(enemy_attack_name);
			show(actions)
		}, 3000)
	}

	function enemy_flee(enemy, backup) {
		// enemy flees the player
		enemy.hasFled = true;
		backup.stats.flight_total += 1;
		hide(fight);
		player.isFighting = false;
		player.movement.on();
		document.querySelector(".map .entity" + enemy.index).style.backgroundImage = "url(assets/map/textures/empty.png)";
		switch (enemy.id) {
			case "goblin":
				backup.stats.flight_goblin += 1;
				dialog(Dialog[20]); // fled goblin dialog
				// loot
				(backup.player.health + Entity.Goblin.loot_flight.health > backup.player.maxhealth) ? backup.player.health = backup.player.maxhealth : backup.player.health += Entity.Goblin.loot_flight.health;
				(backup.player.mp + Entity.Goblin.loot_flight.mp > backup.player.maxmp) ? backup.player.mp = backup.player.maxmp : backup.player.mp += Entity.Goblin.loot_flight.mp;
				reload_hud(backup);
				break;
			case "skeleton1":
				backup.stats.flight_skeleton1 += 1;
				dialog(Dialog[21]); // fled warrior skeleton dialog
				// loot
				(backup.player.health + Entity.Skeleton1.loot_flight.health > backup.player.maxhealth) ? backup.player.health = backup.player.maxhealth : backup.player.health += Entity.Skeleton1.loot_flight.health;
				(backup.player.mp + Entity.Skeleton1.loot_flight.mp > backup.player.maxmp) ? backup.player.mp = backup.player.maxmp : backup.player.mp += Entity.Skeleton1.loot_flight.mp;
				reload_hud(backup);
				break;
			case "skeleton2":
				backup.stats.flight_skeleton2 += 1;
				dialog(Dialog[22]); // fled archer skeleton dialog
				// loot
				(backup.player.health + Entity.Skeleton2.loot_flight.health > backup.player.maxhealth) ? backup.player.health = backup.player.maxhealth : backup.player.health += Entity.Skeleton2.loot_flight.health;
				(backup.player.mp + Entity.Skeleton2.loot_flight.mp > backup.player.maxmp) ? backup.player.mp = backup.player.maxmp : backup.player.mp += Entity.Skeleton2.loot_flight.mp;
				reload_hud(backup);
				break
		}
	}

	function enemy_dead(enemy, backup) {
		// enemy is killed by the player
		enemy.isDead = true;
		backup.stats.kill_total += 1;
		hide(fight);
		player.isFighting = false;
		player.movement.on();
		document.querySelector(".map .entity" + enemy.index).style.backgroundImage = "url(assets/map/textures/empty.png)";
		switch (enemy.id) {
			case "goblin":
				backup.stats.kill_goblin += 1;
				dialog(Dialog[17]); // dead goblin dialog
				// loot
				(backup.player.health + Entity.Goblin.loot_death.health > backup.player.maxhealth) ? backup.player.health = backup.player.maxhealth : backup.player.health += Entity.Goblin.loot_death.health;
				(backup.player.mp + Entity.Goblin.loot_death.mp > backup.player.maxmp) ? backup.player.mp = backup.player.maxmp : backup.player.mp += Entity.Goblin.loot_death.mp;
				reload_hud(backup);
				break;
			case "skeleton1":
				backup.stats.kill_skeleton1 += 1;
				dialog(Dialog[18]); // dead warrior skeleton dialog
				// loot
				(backup.player.health + Entity.Skeleton1.loot_death.health > backup.player.maxhealth) ? backup.player.health = backup.player.maxhealth : backup.player.health += Entity.Skeleton1.loot_death.health;
				(backup.player.mp + Entity.Skeleton1.loot_death.mp > backup.player.maxmp) ? backup.player.mp = backup.player.maxmp : backup.player.mp += Entity.Skeleton1.loot_death.mp;
				reload_hud(backup);
				break;
			case "skeleton2":
				backup.stats.kill_skeleton2 += 1;
				dialog(Dialog[19]); // dead archer skeleton dialog
				// loot
				(backup.player.health + Entity.Skeleton2.loot_death.health > backup.player.maxhealth) ? backup.player.health = backup.player.maxhealth : backup.player.health += Entity.Skeleton2.loot_death.health;
				(backup.player.mp + Entity.Skeleton2.loot_death.mp > backup.player.maxmp) ? backup.player.mp = backup.player.maxmp : backup.player.mp += Entity.Skeleton2.loot_death.mp;
				reload_hud(backup);
				break
		}
	}
}