<!DOCTYPE html>

<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="A rogue-like web video game.">
		<meta name="version" content="1.1.0">
		<meta name="author" content="Clarisse Eynard">
		<meta name="author" content="Léan Houdayer">
		<meta name="author" content="Mattéo Legagneux">
		<meta name="copyright" content="© 2021 Quiver. All rights reserved.">
		<link rel="stylesheet" type="text/css" href="assets/ui/noscript.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/overlay.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-main.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-play.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-options.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-load.css">
		<link rel="stylesheet" type="text/css" href="assets/textures/btn.css">
		<link rel="stylesheet" type="text/css" href="assets/textures/icon.css">
		<link rel="stylesheet" type="text/css" href="assets/textures/map.css">
		<style type="text/css">
			@font-face {
				font-family: Quiver;
				src: url(assets/font/Quiver.ttf) format("truetype")
			}

			::selection {background-color: rgba(0, 0, 0, 0.2)} /* text selection color */

			body {
				margin: 0;
				background-color: #000;
				cursor: default
			}

			main {display: none}

			div, input {font-family: Quiver}

			textarea {
				font-family: monospace;
				font-weight: bold
			}
		</style>
		<script type="text/javascript" data-function="main">
			var Game = {
				init: function() {
					$("title").textContent = `Quiver ${Game.version}`; // updating the window title with the last version
					Game.lang("en_US"); // setting the game language to english (USA) - can be modified in the options
					// resetting input values
					play.new_game.player_name.value = "";
					play.new_game.game_name.value = "";
					play.launch_backup.backup.value = "";
					show($("main")); // opening the game window
					play.new_game.player_name.addEventListener("keyup", function() {Game.check_input_value(play.new_game.player_name)});
					play.new_game.game_name.addEventListener("keyup", function() {Game.check_input_value(play.new_game.game_name)})
				},
				rickroll: function() {location.href = "https://www.youtube.com/watch?v=dQw4w9WgXcQ"},
				check_input_value: function(input) {
					if (/^\s+$/.test(input.value) || input.value.length === 0) {
						// the input value is composed only of whitespaces or is empty
						(input.getAttribute("id") === "player_name") ? player_name_ok = false : game_name_ok = false;
						play.new_game.play.setAttribute("disabled", "disabled");
						play.new_game.play.removeEventListener("click", Game.launch_new_game)
					} else (input.getAttribute("id") === "player_name") ? player_name_ok = true : game_name_ok = true; // no errors
					Game.check_new_game_validity()
				},
				check_new_game_validity: function() {
					if (player_name_ok && game_name_ok && character_selected) {
						play.new_game.play.removeAttribute("disabled");
						play.new_game.play.addEventListener("click", Game.launch_new_game)
					}
				},
				lang: function(l) {
					// l: language name (str)
					// requesting the JSON file
					var path = `assets/lang/${l}.json`,
						request = new XMLHttpRequest();
					request.open("GET", path);
					request.responseType = "json";
					request.send();
					request.addEventListener("load", function() {
						// the request has been accepted, recovering file content and changing the default language of the page
						var r = this.response[l];
						document.querySelector("html").setAttribute("lang", r["lang"]);
						// applying the recovered language data to UI elements
						// global buttons
						document.querySelectorAll(".btn-prev").forEach(function(e) {e.textContent = r["prev.text"]});
						document.querySelectorAll(".btn-next").forEach(function(e) {e.textContent = r["next.text"]});
						document.querySelectorAll(".btn-class").forEach(function(e) {e.querySelector(".character_title").textContent = r.class[e.classList[2]]["name.text"]});
						// unique buttons
						UI.btn.play.textContent = r["play.text"];
						UI.btn.options.textContent = r["options.text"];
						// menu titles
						title.play.textContent = r["play.text"];
						title.options.textContent = r["options.text"];
						// play menu
						// new game section
						play.new_game.subtitle.textContent = r["new_game.text"];
						play.new_game.player_name_tip.textContent = r.new_game["player_name_tip.text"];
						play.new_game.player_name.setAttribute("placeholder", r.new_game["player_name_placeholder.text"]);
						play.new_game.game_name_tip.textContent = r.new_game["game_name_tip.text"];
						play.new_game.game_name.setAttribute("placeholder", r.new_game["game_name_placeholder.text"]);
						play.new_game.character_selection_tip.textContent = r.new_game["character_selection_tip.text"];
						play.new_game.play.textContent = r["new_game.text"];
						// launch backup section
						play.launch_backup.subtitle.textContent = r["launch_backup.text"];
						play.launch_backup.open.textContent = r["open_backup.text"];
						play.launch_backup.backup_tip.textContent = r["backup_tip.text"];
						play.launch_backup.launch.textContent = r["launch_this_backup.text"];
						// option menu
						// keybind settings
						options.keybind.subtitle.textContent = r.options["keybind.text"];
						options.keybind.forward.firstChild.textContent = r.options["keybind:forward.text"];
						options.keybind.backward.firstChild.textContent = r.options["keybind:backward.text"];
						options.keybind.left.firstChild.textContent = r.options["keybind:left.text"];
						options.keybind.right.firstChild.textContent = r.options["keybind:right.text"];
						options.keybind.console.firstChild.textContent = r.options["keybind:console.text"];
						// display settings (not used)
						/*options.display.subtitle.textContent = r.options["display.text"];
						options.display.borders.textContent = r.options["display:borders.text"];
						options.display.animations.textContent = r.options["display:animations.text"];*/
						// audio settings
						options.audio.subtitle.textContent = r.options["audio.text"];
						options.audio.music.firstChild.textContent = r.options["audio:music.text"];
						options.audio.sound.firstChild.textContent = r.options["audio:sound.text"];
						// save settings (not used)
						/*options.saves.subtitle.textContent = r.options["saves.text"];
						options.saves.advanced.textContent = r.options["saves:advanced.text"];*/
						// language settings
						options.lang.subtitle.textContent = r.options["lang.text"];
						options.lang.en_US.firstChild.textContent = r.options["lang:en_US.text"];
						options.lang.es_ES.firstChild.textContent = r.options["lang:es_ES.text"];
						options.lang.fr_FR.firstChild.textContent = r.options["lang:fr_FR.text"];
						// about settings
						options.about.subtitle.textContent = r.options["about.text"];
						options.about.updates.textContent = r.options["about:updates.text"];
						options.about.credits.textContent = r.options["about:credits.text"];
						// errors
						json_error = r.error["json_error.text"]
					});
					// showing the check mark on the selected language
					document.querySelectorAll(".option.lang .option-name").forEach(function(e) {e.querySelector(".icon").style.visibility = "hidden"});
					document.querySelector(`.option.lang .option-name.${l}`).querySelector(".icon").style.visibility = "visible"
				},
				create_backup: function() {
					var Backup = {
						name: play.new_game.game_name.value,
						date: {
							creation: now(),
							lastConnection: now()
						},
						player: {
							nickname: play.new_game.player_name.value,
							class: null,
							level: "lobby",
							health: null,
							maxhealth: null,
							mp: null,
							maxmp: null,
							resistance: null,
							base_resistance: null,
							pos: [0, 0],
							orientation: "right",
							hasBlocked: false
						},
						stats: {
							kill_total: 0,
							kill_goblin: 0,
							kill_skeleton1: 0,
							kill_skeleton2: 0,
							flight_total: 0,
							flight_goblin: 0,
							flight_skeleton1: 0,
							flight_skeleton2: 0
						},
						entity: {
							// entity data
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
					}
					return Backup
				},
				launch_new_game: function() {
					Game.toggle_menu("menu-play", "close");
					var Backup = Game.create_backup();
					Game.load(Backup)
				},
				open_backup: function(e) {
					var file = e.target.files[0];
					if (!file) return;
					var reader = new FileReader();
					reader.onload = function(e) {
						show(document.querySelector(".container-backup"), "flex"); // opening
						try {
							var temp = JSON.parse(e.target.result); // e.target.result = backup content
							temp = temp.Backup;
							// showing backup content
							play.launch_backup.backup.value = e.target.result;
							show(play.launch_backup.backup, "flex");
							play.launch_backup.backup_info.classList.remove("error");
							play.launch_backup.backup_info.innerHTML = `"${temp.name}" - ${temp.player.nickname} (level ${temp.player.level}, ${temp.player.class})<br>Creation: ${convert_date(temp.date.creation)}<br>Last connection: ${convert_date(temp.date.lastConnection)}`;
							show(play.launch_backup.launch)
						} catch (e) {
							hide(play.launch_backup.launch);
							hide(play.launch_backup.backup);
							play.launch_backup.backup_info.classList.add("error");
							play.launch_backup.backup_info.textContent = json_error.split("%e").join(file.name)
							return e
						}
						UI.menu.play.scrollTop = UI.menu.play.scrollHeight
					}
					reader.readAsText(file)
				},
				launch_backup: function() {
					Game.toggle_menu("menu-play", "close");
					var Backup = JSON.parse(play.launch_backup.backup.value);
					Backup = Backup.Backup;
					Game.load(Backup)
				},
				load: function(backup) {
					show(UI.overlay.load);
					Game.generate(backup)
					UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style["animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style.backgroundColor = "#000";
					setTimeout(function() {show(UI.menu.load, "flex")}, 600);
					setTimeout(function() {
						UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style["animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style.backgroundColor = "transparent"
					}, 1200);
					setTimeout(function() {
						UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_in";
						UI.overlay.load.style["animation-name"] = "overlay_load_fade_in";
						UI.overlay.load.style.backgroundColor = "#000";
						// Game.generate(backup)
					}, 4600);
					setTimeout(function() {
						hide(UI.menu.load);
						UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style["animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style.backgroundColor = "transparent";
						show(Map.container, "flex");
					}, 5200);
					setTimeout(function() {hide(UI.overlay.load)}, 5800)
				},
				generate: function(backup) {
					// requesting the JSON map data
					var path = `maps/${backup.player.level}.json`,
						map_request = new XMLHttpRequest();
					map_request.open("GET", path);
					map_request.responseType = "json";
					map_request.send();
					map_request.addEventListener("load", function() {
						// the request has been accepted, recovering file content and generating the map
						var r = this.response[backup.player.level],
							map = r.map,
							uppermap = r.uppermap,
							entity = r.entity,
							next_level = r.next_level,
							part,
							texture;
						// map parts
						for (i = 0; i < map.length; i++) {
							part = document.createElement("div");
							part.className = `part ${map[i].part}`;
							texture = new Image();
							texture.src = `assets/textures/map/${map[i].texture}`;
							if (texture.width === 0) console.log(`${map[i].texture} doesn't exist.`);
							else console.log(`${map[i].texture} does exist.`)
							Map.map.append(part)
						}
						// uppermap parts
						/*for (i = 0; i < uppermap.length; i++) {
							part = document.createElement("div");
							part.className = `part ${uppermap[i]}`;
							Map.uppermap.append(part)
						}*/
						// entities
					})
				},
				toggle_menu: function(m, s) {
					// m: menu name (str)
					// s: status (1: open or 0: close)
					var menu = UI.overlay.menu.querySelector(`.${m}`),
						st = menu.querySelector(".scrollbox-top"),
						sb = menu.querySelector(".scrollbox-bottom"),
						content = menu.querySelector(".content"),
						scrollable = content.querySelector(".scrollable");
					if (s == "open") {
						// opening
						if (play.launch_backup.backup_info.classList.contains("error")) play.launch_backup.backup_info.textContent = ""; // removing JSON error
						show(UI.overlay.menu, "flex");
						UI.overlay.menu.style["-webkit-animation-name"] = "overlay_menu_fade_in";
						UI.overlay.menu.style["animation-name"] = "overlay_menu_fade_in";
						UI.overlay.menu.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
						show(menu, "flex");
						st.style["-webkit-animation-name"] = "scrollbox-open";
						st.style["animation-name"] = "scrollbox-open";
						st.style.height = "50%";
						sb.style["-webkit-animation-name"] = "scrollbox-open";
						sb.style["animation-name"] = "scrollbox-open";
						sb.style.height = "50%";
						scrollable.style.scrollBehavior = "auto";
						scrollable.scrollTop = 0; /* scrolling to top (without animations) */
						scrollable.style.scrollBehavior = "smooth";
						setTimeout(function() {
							content.style.visibility = "visible";
							document.addEventListener("keydown", esc)
						}, 200)
					} else if (s == "close") {
						// closing with button
						document.removeEventListener("keydown", esc);
						content.style.visibility = "hidden";
						st.style["-webkit-animation-name"] = "scrollbox-close";
						st.style["animation-name"] = "scrollbox-close";
						st.style.height = 0;
						sb.style["-webkit-animation-name"] = "scrollbox-close";
						sb.style["animation-name"] = "scrollbox-close";
						sb.style.height = 0;
						UI.overlay.menu.style["-webkit-animation-name"] = "overlay_menu_fade_out";
						UI.overlay.menu.style["animation-name"] = "overlay_menu_fade_out";
						UI.overlay.menu.style.backgroundColor = "transparent";
						setTimeout(function() {
							hide(menu);
							hide(UI.overlay.menu)
						}, 200)
					}
				},
				version: "1.1.0"
			}

			var UI = {
				btn: {
					play: null,
					options: null,
					create: null,
					data_function: {close: null}
				},
				menu: {
					play: null,
					options: null,
					load: null
				},
				overlay: {
					menu: null,
					load: null
				}
			};

			var title = {
				play: null,
				options: null
			};

			var play = {
				new_game: {
					subtitle: null,
					player_name_tip: null,
					player_name: null,
					game_name_tip: null,
					game_name: null,
					character_selection_tip: null,
					character_info: null,
					health_value: null,
					shield_value: null,
					mana_value: null,
					play: null
				},
				launch_backup: {
					subtitle: null,
					open: null,
					backup_tip: null,
					backup: null,
					backup_info: null,
					launch: null
				}
			};

			var options = {
				keybind: {
					subtitle: null,
					forward: null,
					backward: null,
					left: null,
					right: null,
					console: null
				},
				display: {
					subtitle: null,
					borders: null,
					animations: null
				},
				audio: {
					subtitle: null,
					music: null,
					sound: null
				},
				saves: {
					subtitle: null,
					advanced: null
				},
				lang: {
					subtitle: null,
					en_US: null,
					es_ES: null,
					fr_FR: null
				},
				about: {
					subtitle: null,
					updates: null,
					credits: null
				}
			};

			var Map = {
				container: null,
				map: null,
				uppermap: null,
				player: null
			};

			var Character = {
				mage: {
					id: "mage",
					type: "player",
					texture: "mage_idle.gif",
					roundActions: ["attack", "use", "flee"],
					health: 20,
					shield: 1,
					mana: 25,
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
				rogue: {
					id: "rogue",
					type: "player",
					texture: "rogue.png",
					roundActions: ["attack", "use", "flee"],
					health: 22,
					shield: 2,
					mana: 15,
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
				paladin: {
					id: "paladin",
					type: "player",
					texture: "paladin.png",
					roundActions: ["attack", "use", "flee"],
					health: 25,
					shield: 3,
					mana: 20,
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
				}
			};

			var Entity = {
				Goblin: {
					id: "goblin",
					name: "Gobelin",
					type: "enemy",
					texture: "goblin.png",
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
					texture: "skeleton1.png",
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
					texture: "skeleton2.png",
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
			};

			var player_name_ok = false,
				game_name_ok = false,
				character_selected = false,
				json_error = "";

			function $(e) {return document.querySelector(e)}

			function show(e, v) {return e.style.display = (v === undefined) ? "block" : v}

			function hide(e) {return e.style.display = "none"}

			function esc(e) {
				var raw_menus = ["menu-play", "menu-options"];
				if (e.keyCode == 27) {
					for (i = 0; i < raw_menus.length; i++) {
						if (document.querySelector(`.${raw_menus[i]}`).style.display === "flex") Game.toggle_menu(raw_menus[i], "close")
					}
				}
			}

			function select_character(c) {
				if (character_selected === false) character_selected = true;
				Game.check_new_game_validity();
				document.querySelectorAll(".btn[data-character").forEach(function(e) {e.style.backgroundImage = "url(assets/textures/btn/btn-class.png)"});
				document.querySelector(`.btn[data-character=${c}]`).style.backgroundImage = "url(assets/textures/btn/btn-class-selected.png)";
				play.new_game.health_value.textContent = Character[c].health;
				play.new_game.shield_value.textContent = Character[c].shield;
				play.new_game.mana_value.textContent = Character[c].mana
			}

			function now() {
				var D = new Date(),
					year = D.getFullYear(),
					month = D.getMonth() + 1,
					day = D.getDate(),
					hour = D.getHours(),
					minute = D.getMinutes(),
					second = D.getSeconds(),
					date = year + "-" + month + "-" + day + "-" + hour + "-" + minute + "-" + second;
				return date
			}

			function convert_date(date) {
				date = date.split("-");
				var new_date = `${date[0]}/${date[1]}/${date[2]} ${date[3]}:${date[4]}:${date[5]}`;
				return new_date
			}

			function set_volume_range(input, target) {
				if (input.value.length !== 0 && /^\d*\.?\d*$/.test(input.value)) {
					// a number has been entered
					if (input.value >= 0 && input.value <= 100) return target.value = input.value
				}
			}

			function set_volume_nb(input, target) {return target.value = input.value}

			window.addEventListener("load", function() {
				// buttons
				UI.btn.play = $(".btn-play");
				UI.btn.options = $(".btn-options");
				UI.btn.create = $(".menu-new_game .btn-create");
				UI.btn.data_function.close = document.querySelectorAll(".btn[data-function='close']");
				// menus
				UI.menu.play = $(".menu-play .scrollable");
				UI.menu.options = $(".menu-options .scrollable");
				UI.menu.load = $(".menu-load");
				// overlays
				UI.overlay.menu = $(".overlay-menu");
				UI.overlay.load = $(".overlay-load");
				// menu titles
				title.play = $(".content-play .title");
				title.options = $(".content-options .title");
				// play menu
				// new game section
				play.new_game.subtitle = $(".new_game .subtitle");
				play.new_game.player_name_tip = $(".new_game .player_name_tip");
				play.new_game.player_name = $(".new_game #player_name");
				play.new_game.game_name_tip = $(".new_game .game_name_tip");
				play.new_game.game_name = $(".new_game #game_name");
				play.new_game.character_selection_tip = $(".new_game .character_selection_tip");
				play.new_game.character_info = $(".character_info");
				play.new_game.health_value = $(".health_value");
				play.new_game.shield_value = $(".shield_value");
				play.new_game.mana_value = $(".mana_value");
				play.new_game.play = $(".new_game .btn-new_game");
				// launch backup section
				play.launch_backup.subtitle = $(".launch_backup .subtitle");
				play.launch_backup.open = $(".launch_backup .btn-open_backup");
				play.launch_backup.backup_tip = $(".launch_backup .backup_tip");
				play.launch_backup.backup = $(".launch_backup #backup");
				play.launch_backup.backup_info = $(".launch_backup .backup_info");
				play.launch_backup.launch = $(".launch_backup .btn-launch_backup");
				// option menu
				// keybind settings
				options.keybind.subtitle = $(".option.keybind .subtitle");
				options.keybind.forward = $(".option.keybind .forward");
				options.keybind.backward = $(".option.keybind .backward");
				options.keybind.left = $(".option.keybind .left");
				options.keybind.right = $(".option.keybind .right");
				options.keybind.console = $(".option.keybind .console");
				// display settings (not used)
				/*options.display.subtitle = $(".option.display .subtitle");
				options.display.borders = $(".option.display .borders");
				options.display.animations = $(".option.display .animations");*/
				// audio settings
				options.audio.subtitle = $(".option.audio .subtitle");
				options.audio.music = $(".option.audio .music");
				options.audio.sound = $(".option.audio .sound");
				// save settings (not used)
				/*options.saves.subtitle = $(".option.saves .subtitle");
				options.saves.advanced = $(".option.saves .advanced");*/
				// language settings
				options.lang.subtitle = $(".option.lang .subtitle");
				options.lang.en_US = $(".option.lang .en_US");
				options.lang.es_ES = $(".option.lang .es_ES");
				options.lang.fr_FR = $(".option.lang .fr_FR");
				// about settings
				options.about.subtitle = $(".option.about .subtitle");
				options.about.updates = $(".option.about .updates");
				options.about.credits = $(".option.about .credits");
				// map elements
				Map.container = $(".map-container");
				Map.map = $("#map");
				Map.uppermap = $("#uppermap");
				Map.player = $("#player");

				Game.init();

				document.querySelectorAll(".btn[data-function]").forEach(function(e) {
					e.addEventListener("click", function() {Game.toggle_menu(this.getAttribute("data-target"), this.getAttribute("data-function"))})
				});
				document.querySelectorAll(".btn[data-character]").forEach(function(e) {
					e.addEventListener("click", function() {select_character(this.getAttribute("data-character"))})
				});
				document.querySelector("#open_backup").onchange = Game.open_backup;
				play.launch_backup.launch.addEventListener("click", function() {Game.launch_backup()});
				document.querySelectorAll(".subtitle").forEach(function(e) {
					e.addEventListener("click", function() {e.parentNode.parentNode.scrollTop = (this.parentNode.offsetTop - 60)})
				});
				// music volume
				document.querySelector(".music .volume").addEventListener("input", function() {set_volume_nb(this, this.nextElementSibling)}); // chrome/safari/ff
				document.querySelector(".music .volume").addEventListener("change", function() {set_volume_nb(this, this.nextElementSibling)}); // ie
				document.querySelector(".music .volume_nb").addEventListener("keyup", function() {set_volume_range(this, this.previousElementSibling)});
				// sound volume
				document.querySelector(".sound .volume").addEventListener("input", function() {set_volume_nb(this, this.nextElementSibling)}); // chrome/safari/ff
				document.querySelector(".sound .volume").addEventListener("change", function() {set_volume_nb(this, this.nextElementSibling)}); // ie
				document.querySelector(".sound .volume_nb").addEventListener("keyup", function() {set_volume_range(this, this.previousElementSibling)})
			})
		</script>
		<title translate="no">Quiver</title>
	</head>

	<body onselectstart="return false">
		<noscript>
			<p>
				This game needs JavaScript to work.<br>
				Enable JavaScript in your browser, then retry.<br><br>
				<a href="">Reload</a>
			</p>
			<div class="version">1.1.0</div>
		</noscript>
		<main>
			<!-- game content goes here -->
			<?php include "assets/ui/menu-main.html"; ?>
			<div class="overlay overlay-menu">
				<?php include "assets/ui/menu-play.html"; ?>
				<?php include "assets/ui/menu-options.html"; ?>
			</div>
			<div class="overlay overlay-load"></div>
			<?php include "assets/ui/menu-load.html"; ?>
			<div class="map-container">
				<div id="player"></div>
				<div id="map"></div>
				<div id="uppermap"></div>
			</div>
		</main>
	</body>

</html>