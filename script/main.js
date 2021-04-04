var Game = {
		aspectRatio: "16/9",
		currentVersion: "1.0.0",
		options: {
			controls: [
				90, // forward
				83, // backward
				81, // left
				68 // right
			]
		},
		scale: null,
		UI: {
			tooltip: {
				display: null,
				version: null,
				class1: null,
				class2: null,
				class3: null
			},
			section: {
				__nojs__: null,
				__window__: null,
				new_game: null,
				new_game_content: null,
				new_game_step1: null,
				new_game_step2: null,
				launch_backup: null,
				launch_backup_content: null,
				options: null,
				options_content: null,
				credits: null,
				save: null,
				save_content: null,
				quit_game: null,
				quit_game_content: null,
				hud: null,
				mini_hud: null,
				death: null
			},
			wrapper: {
				main: null,
				pause: null,
				loading: null,
				game: null,
				fight: null,
				options: null
			}
		}
	},
	Entity = {
		Mage: {
			id: "mage",
			name: "Mage",
			type: "player",
			texture: "assets/entity/mage_idle.gif",
			roundActions: ["attack", "use", "flee"],
			health: 20,
			mp: 25,
			resistance: 1,
			attack1: {
				id: "fireball",
				name: "Boule de feu",
				cost: 3,
				damage: 4
			},
			attack2: {
				id: "wand",
				name: "Coup de baguette",
				cost: 2,
				damage: 3
			},
			ult: {
				id: "lightning",
				name: "Eclair",
				cost: 9,
				damage: 5,
				hitAllMobs: true
			}
		},
		Rogue: {
			id: "rogue",
			name: "Roublard",
			type: "player",
			texture: "assets/entity/rogue.png",
			roundActions: ["attack", "use", "flee"],
			health: 22,
			mp: 15,
			resistance: 2,
			attack1: {
				id: "doubledaggers",
				name: "Doubles dagues",
				cost: 2,
				damage: 4,
				base_damage: 4,
				boost_damage: 8
			},
			attack2: {
				id: "stealth",
				name: "Attaque furtive",
				cost: 4,
				damage: 0,
				damageMult: false
			},
			ult: {
				id: "discretion",
				name: "Discretion",
				cost: 7,
				damage: 0
			}
		},
		Paladin: {
			id: "paladin",
			name: "Paladin",
			type: "player",
			texture: "assets/entity/paladin.png",
			roundActions: ["attack", "use", "flee"],
			health: 25,
			mp: 20,
			resistance: 3,
			attack1: {
				id: "sword",
				name: "Coup d'epee",
				cost: 2,
				damage: 4
			},
			attack2: {
				id: "parade",
				name: "Parade",
				cost: 4,
				damage: 0,
				blockAttack: true
			},
			ult: {
				id: "regeneration",
				name: "Regeneration (+5)",
				cost: 7,
				damage: 0,
				healthAmount: 5
			}
		},
		Goblin: {
			id: "goblin",
			name: "Gobelin",
			type: "enemy",
			texture: "assets/entity/goblin.png",
			roundActions: ["attack", "attack", "attack", "attack", "flee", "help"],
			health: 20,
			mp: 0,
			resistance: 0,
			heal: 3,
			attack1: {
				id: "hammer",
				name: "Coup de massue",
				cost: 0,
				damage: 2
			},
			attack2: {
				id: "kick",
				name: "Coup de pied",
				cost: 0,
				damage: 1
			},
			loot_death: {
				health: 4,
				mp: 6
			},
			loot_flight: {
				health: 3,
				mp: 4
			}
		},
		Skeleton1: {
			id: "skeleton1",
			name: "Guerrier squelette",
			type: "enemy",
			texture: "assets/entity/skeleton1.png",
			roundActions: ["attack", "attack", "attack", "attack", "attack", "help"],
			health: 20,
			mp: 0,
			resistance: 2,
			heal: 2,
			attack1: {
				id: "dagger",
				name: "Coup de dague",
				cost: 0,
				damage: 2
			},
			loot_death: {
				health: 5,
				mp: 8
			},
			loot_flight: {
				health: 2,
				mp: 4
			}
		},
		Skeleton2: {
			id: "skeleton2",
			name: "Archer squelette",
			type: "enemy",
			texture: "assets/entity/skeleton2.png",
			roundActions: ["attack", "attack", "attack", "attack", "attack", "flee"],
			health: 20,
			mp: 0,
			resistance: 1,
			heal: 0,
			attack1: {
				id: "arrow",
				name: "Tir de fleche",
				cost: 0,
				damage: 3
			},
			loot_death: {
				health: 3,
				mp: 7
			},
			loot_flight: {
				health: 1,
				mp: 3
			}
		}
	},
	Backup = {
		name: null,
		date: {
			creation: null,
			lastConnection: null
		},
		player: {
			nickname: null,
			class: null,
			level: null,
			health: null,
			maxhealth: null,
			mp: null,
			maxmp: null,
			resistance: null,
			base_resistance: null,
			pos: [null, null],
			orientation: null,
			hasBlocked: false
		},
		stats: {
			kill_total: null,
			kill_goblin: null,
			kill_skeleton1: null,
			kill_skeleton2: null,
			flight_total: null,
			flight_goblin: null,
			flight_skeleton1: null,
			flight_skeleton2: null
		},
		entity: {
			Entity0: {
				// archer skeleton
				id: Entity.Skeleton2.id,
				index: 0,
				name: Entity.Skeleton2.name,
				texture: Entity.Skeleton2.texture,
				health: Entity.Skeleton2.health,
				max_health: Entity.Skeleton2.health,
				resistance: Entity.Skeleton2.resistance,
				heal: Entity.Skeleton2.heal,
				isScared: false,
				hasBlocked: false,
				hasFled: false,
				isDead: false
			},
			Entity1: {
				// warrior skeleton
				id: Entity.Skeleton1.id,
				index: 1,
				name: Entity.Skeleton1.name,
				texture: Entity.Skeleton1.texture,
				health: Entity.Skeleton1.health,
				max_health: Entity.Skeleton1.health,
				resistance: Entity.Skeleton1.resistance,
				heal: Entity.Skeleton1.heal,
				isScared: false,
				hasBlocked: false,
				hasFled: false,
				isDead: false
			},
			Entity2: {
				// goblin
				id: Entity.Goblin.id,
				index: 2,
				name: Entity.Goblin.name,
				texture: Entity.Goblin.texture,
				health: Entity.Goblin.health,
				max_health: Entity.Goblin.health,
				resistance: Entity.Goblin.resistance,
				heal: Entity.Goblin.heal,
				isScared: false,
				hasBlocked: false,
				hasFled: false,
				isDead: false
			},
			Entity3: {
				// warrior skeleton
				id: Entity.Skeleton1.id,
				index: 3,
				name: Entity.Skeleton1.name,
				texture: Entity.Skeleton1.texture,
				health: Entity.Skeleton1.health,
				max_health: Entity.Skeleton1.health,
				resistance: Entity.Skeleton1.resistance,
				heal: Entity.Skeleton1.heal,
				isScared: false,
				hasBlocked: false,
				hasFled: false,
				isDead: false
			},
			Entity4: {
				// goblin
				id: Entity.Goblin.id,
				index: 4,
				name: Entity.Goblin.name,
				texture: Entity.Goblin.texture,
				health: Entity.Goblin.health,
				max_health: Entity.Goblin.health,
				resistance: Entity.Goblin.resistance,
				heal: Entity.Goblin.heal,
				isScared: false,
				hasBlocked: false,
				hasFled: false,
				isDead: false
			},
			Entity5: {
				// archer skeleton (top map)
				id: Entity.Skeleton2.id,
				index: 5,
				name: Entity.Skeleton2.name,
				texture: Entity.Skeleton2.texture,
				health: Entity.Skeleton2.health,
				max_health: Entity.Skeleton2.health,
				resistance: Entity.Skeleton2.resistance,
				heal: Entity.Skeleton2.heal,
				isScared: false,
				hasBlocked: false,
				hasFled: false,
				isDead: false
			},
			Entity6: {
				// archer skeleton (bottom map)
				id: Entity.Skeleton2.id,
				index: 6,
				name: Entity.Skeleton2.name,
				texture: Entity.Skeleton2.texture,
				health: Entity.Skeleton2.health,
				max_health: Entity.Skeleton2.health,
				resistance: Entity.Skeleton2.resistance,
				heal: Entity.Skeleton2.heal,
				isScared: false,
				hasBlocked: false,
				hasFled: false,
				isDead: false
			}
		}
	},
	Dialog = [
		// level 0 dialogs
		[
			"Narrateur",
			"Alors que vous vous reveillez dans une taverne, l'aubergiste s'adresse a vous.",
			10000
		], [
			"Aubergiste",
			"Bonjour %s, comment allez-vous ? Connaissez-vous la legende du diamant perdu ?",
			5000
		], [
			"Aubergiste",
			"Voulez-vous que je vous la raconte ?",
			0,
			["Oui, pourquoi pas", "J'ai la flemme d'ecouter"]
		], [
			"Aubergiste",
			"Une legende dit qu'il y a un enorme diamant cache dans un ancien donjon. Si vous le trouvez, il est a vous.",
			10000
		], [
			"Aubergiste",
			"Si vous voulez partir a sa recherche, vous pouvez sortir de l'auberge. Je vous donnerai une carte pour vous permettre de vous rendre au donjon. Bonne chance !",
			10000
		], [
			"Aubergiste",
			"Comme vous voulez :(",
			4000
		],
		// level 1 dialogs
		[
			"Narrateur",
			"Vous etes enfin arrive(e) au donjon. Faites attention aux monstres qui y trainent.",
			5000
		], [
			"Narrateur",
			"Non, vous ne pouvez pas revenir en arriere.",
			3000
		], [
			"Narrateur",
			"Vous entendez du bruit venir de la piece. Il y a surement des tresors a l'interieur.",
			4000
		], [
			"Narrateur",
			"Vous tentez d'ouvrir le coffre, mais il est rouille et ne cede pas.",
			4000
		], [
			"Narrateur",
			"Vous sentez une presence hostile dans ce couloir. Vaut-il vraiment la peine d'y aller ?",
			4000
		], [
			"Narrateur",
			"Vous vous cognez la tete. C'est un mur, ici.",
			4000
		],
		// level 2 dialogs
		[
			"Narrateur",
			"Vous continuez dans le couloir en vous enfoncant encore plus loin dans le donjon.",
			5000
		], [
			"Gobelin",
			"-bleh bleh bleh-",
			3000
		], [
			"Narrateur",
			"Vous ouvrez le coffre et constatez qu'il est vide. Et poussiereux aussi.",
			4000
		], [
			"Narrateur",
			"Ce couloir est tres sombre. Mieux vaut ne pas y aller sans torche.",
			3000
		], [
			"Narrateur",
			"Vous vous emparez du diamant et vous vous enfuyez avec aussi vite que possible.",
			4000
		],
		// kill dialogs
		[
			"Narrateur",
			"Vous avez tue un gobelin. *bruit de gargouillement* Vous recuperez un peu de sante et de mana.",
			5000
		], [
			"Narrateur",
			"Vous avez tue un guerrier squelette. *bruits d'os qui craquent* Vous recuperez un peu de sante et de mana.",
			5000
		], [
			"Narrateur",
			"Vous avez tue un archer squelette. *bruits d'os qui craquent* Vous recuperez un peu de sante et de mana.",
			5000
		],
		// flight dialogs
		[
			"Narrateur",
			"Le gobelin a fui. *bruits de pas lointains* Vous recuperez un peu de sante et de mana.",
			5000
		], [
			"Narrateur",
			"Le guerrier squelette a fui. *bruits de pas et d'os* Vous recuperez un peu de sante et de mana.",
			5000
		], [
			"Narrateur",
			"L'archer squelette a fui. *bruits de pas et d'os* Vous recuperez un peu de sante et de mana.",
			5000
		]
	],
	Subtitle = [
		[
			"Partie 1 :<br>La taverne",
			4000
		], [
			"Partie 2 :<br>Dans le donjon",
			4000
		], [
			"Partie 3 :<br>Le diamant perdu",
			4000
		]
	],
	player = {
		movement: {
			on: null,
			off: null,
			keydown: null,
			keyup: null,
			move: null
		},
		direction: {
			top: false,
			bottom: false,
			left: false,
			right: false
		},
		canMove: {
			top: false,
			bottom: false,
			left: false,
			right: false
		},
		x: 0,
		y: 0,
		newx: 0,
		newy: 0,
		speed: 0.1,
		canLevelUp: false,
		isEnemyNear: false,
		isCustomCaseNear: false,
		isFighting: false,
		isInCredits: false,
		hasVisitedChestRoom: false,
		hasVisitedCorridor: false,
		hasHeardNoise: false,
		hasTakenDiamond: false
	},
	map = {
		structure: [],
		x: 0,
		y: 0,
		slotWidth: 64
	},
	lvl_0 = [],
	lvl_0_upper = [],
	lvl_1 = [],
	lvl_1_upper = [],
	lvl_2 = [],
	lvl_2_upper = [],
	gameLaunched = false,
	gameEnded = false,
	resistance = 20,
	debug = "";

function show(e, value) {
	var display = (value === undefined) ? "block" : value;
	return e.style.display = display
}

function hide(e) {return e.style.display = "none"}

function close() {hide(this.parentNode)}

function clear(e) {return e.value = ""}

function clearBackup(backup) {
	// removing all backup data
	backup.name = null;
	backup.date.creation = null;
	backup.date.lastConnection = null;
	backup.player.nickname = null;
	backup.player.class = null;
	backup.player.level = null;
	backup.player.health = 0;
	backup.player.maxhealth = 0;
	backup.player.mp = 0;
	backup.player.maxmp = 0
}



window.onload = function() {
	// dom init
	// debug console
	debug = document.querySelector("debug");
	debug.log = function(debug, text) {this.querySelector(".d" + debug).innerHTML = text}
	// game selectors
	with (Game.UI) {
		tooltip.display = document.querySelectorAll(".display");
		tooltip.version = document.querySelectorAll(".version");
		section.__nojs__ = document.querySelector(".nojs");
		section.__window__ = document.querySelector(".windowtoosmall");
		section.new_game = document.querySelector(".section-new_game");
		section.new_game_content = section.new_game.querySelector("content");
		section.new_game_step1 = section.new_game_content.querySelector(".step1");
		section.new_game_step2 = section.new_game_content.querySelector(".step2");
		section.launch_backup = document.querySelector(".section-launch_backup");
		section.launch_backup_content = section.launch_backup.querySelector("content");
		section.options = document.querySelector(".section-options");
		section.options_content = section.options.querySelector("content");
		section.credits = document.querySelector(".credits");
		section.credits_overlay = section.credits.querySelector(".overlay-credits");
		section.save = document.querySelector(".section-save");
		section.save_content = section.save.querySelector("content");
		section.quit_game = document.querySelector(".section-quit_game");
		section.quit_game_content = section.quit_game.querySelector("content");
		section.hud = document.querySelector(".game .hud");
		section.mini_hud = document.querySelector(".menu-fight .mini_hud");
		section.death = document.querySelector(".menu-death");
		wrapper.main = document.querySelector(".menu-main");
		wrapper.pause = document.querySelector(".menu-pause");
		wrapper.loading = document.querySelector(".game-loading");
		wrapper.game = document.querySelector(".game");
		wrapper.fight = document.querySelector(".menu-fight");
		wrapper.options = document.querySelector(".overlay-options")
	}

	// hidn' red scren of da deth coz it go brrr
	hide(Game.UI.section.__nojs__);

	// game update
	document.querySelector("title").innerHTML = "Quiver " + Game.currentVersion;
	Game.UI.tooltip.version.forEach(function(e) {e.innerHTML = "Version : " + Game.currentVersion});

	// scaling window (16/9)
	Game.scale();

	// clearing all old data
	clear(Game.UI.section.new_game_step1.querySelector("#game_name"));
	clear(Game.UI.section.new_game_step1.querySelector("#player_nickname"));

	// applying functions to buttons
	document.querySelector("#btn-new_game").onclick = new_game;
	document.querySelector("#btn-launch_backup").onchange = launch_backup;
	document.querySelector("#btn-open_credits").onclick = credits;
	document.querySelector(".menu-main .menu .btn-options").onclick = options;

	// enabling keybinds
	keybinds()
}