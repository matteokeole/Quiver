<!DOCTYPE html>

<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="A rogue-like web video game.">
		<meta name="version" content="1.1.0">
		<meta name="author" content="Clarisse Eynard, Léan Houdayer, Mattéo Legagneux">
		<meta name="copyright" content="© 2021 Quiver. All rights reserved.">
		<link rel="stylesheet" type="text/css" href="assets/ui/noscript.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/overlay.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-main.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-play.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-options.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-keybind.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-load.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-pause.css">
		<link rel="stylesheet" type="text/css" href="assets/textures/texture_list.css">
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
					Game.lang("en_US"); // setting the game language to english (can be modified in the options)
					// resetting input values
					play.new_game.player_name.value = "";
					play.new_game.game_name.value = "";
					play.launch_backup.backup.value = "";
					show($("main")); // opening the game window
					play.new_game.player_name.addEventListener("keyup", function() {Game.check_input_value(play.new_game.player_name)});
					play.new_game.game_name.addEventListener("keyup", function() {Game.check_input_value(play.new_game.game_name)});
					document.querySelectorAll(".btn[data-function='open']").forEach(function(e) {
						e.addEventListener("click", function() {Game.toggle_menu(this.getAttribute("data-target"), "open")})
					});
					document.querySelectorAll(".btn[data-function='close']").forEach(function(e) {
						e.addEventListener("click", function() {Game.toggle_menu(this.getAttribute("data-target"), "close")})
					});
					document.querySelectorAll(".btn[data-character]").forEach(function(e) {
						e.addEventListener("click", function() {select_character(this.getAttribute("data-character"))})
					});
					document.querySelectorAll(".option-name[data-function='keybind']").forEach(function(e) {
						e.addEventListener("click", function() {Game.open_keybind(e)})
					});
					$("#open_backup").onchange = Game.open_backup;
					play.launch_backup.launch.addEventListener("click", function() {Game.launch_backup()});
					document.querySelectorAll(".subtitle").forEach(function(e) {
						e.addEventListener("click", function() {e.parentNode.parentNode.scrollTop = (this.parentNode.offsetTop - 60)})
					});
					keybind.cancel.addEventListener("click", Game.close_keybind);
					// music volume
					$(".music .volume").addEventListener("input", function() {set_volume_nb(this, this.nextElementSibling)}); // chrome/safari/ff
					$(".music .volume").addEventListener("change", function() {set_volume_nb(this, this.nextElementSibling)}); // ie
					$(".music .volume_nb").addEventListener("keyup", function() {set_volume_range(this, this.previousElementSibling)});
					// sound volume
					$(".sound .volume").addEventListener("input", function() {set_volume_nb(this, this.nextElementSibling)}); // chrome/safari/ff
					$(".sound .volume").addEventListener("change", function() {set_volume_nb(this, this.nextElementSibling)}); // ie
					$(".sound .volume_nb").addEventListener("keyup", function() {set_volume_range(this, this.previousElementSibling)})
				},
				check_input_value: function(input) {
					if (/^\s+$/.test(input.value) || input.value.length === 0) {
						// the input value is composed only of whitespaces or is empty
						(input.getAttribute("id") === "player_name") ? is_player_name_ok = false : is_game_name_ok = false;
						play.new_game.play.setAttribute("disabled", "disabled");
						play.new_game.play.removeEventListener("click", Game.launch_new_game)
					}
					else if (input.getAttribute("id") === "player_name") is_player_name_ok = true;
					else is_game_name_ok = true; // no errors
					Game.check_new_game_validity()
				},
				check_new_game_validity: function() {
					if (is_player_name_ok && is_game_name_ok && is_character_selected) {
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
						// the request has been accepted, changing page language
						var r = this.response[l]; // recovering file content
						$("html").setAttribute("lang", r["lang"]);
						// buttons
						document.querySelectorAll(".btn-class").forEach(function(e) {e.querySelector(".character_title").textContent = r.character[e.classList[2]]["name.text"]});
						document.querySelectorAll(".btn-options").forEach(function(e) {e.textContent = r["options.text"]});
						UI.btn.play.textContent = r["play.text"];
						UI.btn.resume.textContent = r["resume.text"];
						UI.btn.exit.textContent = r["exit.text"];
						// menu titles
						title.play.textContent = r["play.text"];
						title.options.textContent = r["options.text"];
						title.pause.textContent = r["pause.text"];
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
						// keybind menu
						keybind.cancel.textContent = r["cancel.text"];
						keybind.apply.textContent = r["apply.text"];
						keybind_tip = r["keybind_tip.text"];
						// audio settings
						options.audio.subtitle.textContent = r.options["audio.text"];
						options.audio.music.firstChild.textContent = r.options["audio:music.text"];
						options.audio.sound.firstChild.textContent = r.options["audio:sound.text"];
						// language settings
						options.lang.subtitle.textContent = r.options["lang.text"];
						options.lang.en_US.firstChild.textContent = r.options["lang:en_US.text"];
						options.lang.es_ES.firstChild.textContent = r.options["lang:es_ES.text"];
						options.lang.fr_FR.firstChild.textContent = r.options["lang:fr_FR.text"];
						// about settings
						options.about.subtitle.textContent = r.options["about.text"];
						options.about.updates.textContent = r.options["about:updates.text"];
						options.about.credits.textContent = r.options["about:credits.text"];
						// ability names
						ability.fireball = r.character.mage["fireball.text"];
						ability.wand = r.character.mage["wand.text"];
						ability.lightning = r.character.mage["lightning.text"];
						ability.double_daggers = r.character.rogue["double_daggers.text"];
						ability.stealth = r.character.rogue["stealth.text"];
						ability.discretion = r.character.rogue["discretion.text"];
						ability.sword_strike = r.character.paladin["sword_strike.text"];
						ability.parade = r.character.paladin["parade.text"];
						ability.regeneration = r.character.paladin["regeneration.text"];
						// errors
						json_error = r.error["json_error.text"]
					});
					// showing the check mark on the selected language
					document.querySelectorAll(".option.lang .option-name").forEach(function(e) {e.querySelector(".icon").style.visibility = "hidden"});
					$(`.option.lang .option-name.${l}`).querySelector(".icon").style.visibility = "visible"
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
							character: character_selected,
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
					window["Backup"] = Game.create_backup();
					Game.load(window["Backup"])
				},
				open_backup: function(e) {
					var file = e.target.files[0];
					if (!file) return;
					var reader = new FileReader();
					reader.onload = function(e) {
						show($(".container-backup"), "flex"); // opening
						try {
							var temp = JSON.parse(e.target.result); // e.target.result = backup content
							temp = temp.Backup;
							// showing backup content
							play.launch_backup.backup.value = e.target.result;
							show(play.launch_backup.backup, "flex");
							play.launch_backup.backup_info.classList.remove("error");
							play.launch_backup.backup_info.innerHTML = `"${temp.name}" - ${temp.player.nickname} (level: ${temp.player.level}, ${temp.player.character})<br>Creation: ${convert_date(temp.date.creation)}<br>Last connection: ${convert_date(temp.date.lastConnection)}`;
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
					window["Backup"] = JSON.parse(play.launch_backup.backup.value);
					window["Backup"] = window["Backup"].Backup;
					Game.load(window["Backup"])
				},
				load: function(backup) {
					document.removeEventListener("keydown", esc);
					// showing loading screen
					show(UI.overlay.load);
					UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style["animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style.backgroundColor = "#000";
					// initializing the player
					var Player = new Character(backup.player.character);
					Player.canMove = player.canMove;
					Player.direction = player.direction;
					Player.movement = player.movement;
					Map.player.style.backgroundImage = `url(assets/textures/entity/${Player.texture.idle})`;
					TempPlayer = undefined;
					// generating the map
					Game.generate(backup, Player);
					// loading overlay/screen animations
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
						hide(UI.menu.main)
					}, 4600);
					// hiding loading screen and showing game
					setTimeout(function() {
						hide(UI.menu.load);
						UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style["animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style.backgroundColor = "transparent";
						show(Map.overlay, "flex");
						show(Map.container, "flex")
					}, 5200);
					setTimeout(function() {
						hide(UI.overlay.load);
						Game.start(backup, Player) // starting the game
					}, 5800)
				},
				generate: function(backup, player) {
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
							scale_multiplier = 64;
						// map parts
						for (i = 0; i < map.length; i++) {
							part = document.createElement("div");
							part.className = `part ${map[i].part}`;
							part.style.width = `${scale_multiplier * map[i].size[0]}px`;
							part.style.height = `${scale_multiplier * map[i].size[1]}px`;
							part.style.transform = `translateX(${scale_multiplier * map[i].origin[0]}px) translateY(${(scale_multiplier * -map[i].origin[1])}px)`;
							part.style.backgroundImage = `url(assets/textures/map/${map[i].texture})`;
							Map.map.append(part)
						}
						// uppermap parts
						for (i = 0; i < uppermap.length; i++) {
							part = document.createElement("div");
							part.className = `part ${uppermap[i].part}`;
							part.style.width = `${scale_multiplier * uppermap[i].size[0]}px`;
							part.style.height = `${scale_multiplier * uppermap[i].size[1]}px`;
							part.style.transform = `translateX(${scale_multiplier * uppermap[i].origin[0]}px) translateY(${(scale_multiplier * -uppermap[i].origin[1])}px)`;
							part.style.backgroundImage = `url(assets/textures/map/${uppermap[i].texture})`;
							Map.uppermap.append(part)
						}
					})
				},
				start: function(backup, player) {
					document.addEventListener("keydown", pause_menu);
					UI.btn.resume.addEventListener("click", function() {
						UI.overlay.pause.style["-webkit-animation-name"] = "overlay_pause_fade_out";
						UI.overlay.pause.style["animation-name"] = "overlay_pause_fade_out";
						Map.container.classList.remove("blur");
						setTimeout(function() {
							hide(UI.overlay.pause);
							player.movement.on()
						}, 200)
					});
					UI.btn.exit.addEventListener("click", function() {location.reload()}); // TODO: a confirmation section musts appear when user want to quit
					// player movement
					player.movement.on();
					window.requestAnimationFrame(player.movement.move)
				},
				toggle_menu: function(m, s) {
					// m: menu name (str)
					// s: status (1: open or 0: close)
					var menu = UI.overlay.menu.querySelector(`.${m}`),
						st = menu.querySelector(".scrollbox-top"),
						sb = menu.querySelector(".scrollbox-bottom"),
						content = menu.querySelector(".content"),
						scrollable = content.querySelector(".scrollable");
					switch (s) {
						case "open": // opening
							if (play.launch_backup.backup_info.classList.contains("error")) play.launch_backup.backup_info.textContent = ""; // removing JSON error info
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
							}, 200);
							break;
						case "close": // closing with button
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
							}, 200);
							break
					}
				},
				// keybind methods
				open_keybind: function(k) {
					current_key = k;
					keybind.title.textContent = current_key.firstChild.textContent;
					keybind.tip.textContent = keybind_tip;
					show(UI.overlay.keybind, "flex"); // showing the menu
					document.addEventListener("keydown", esc);
					document.addEventListener("keydown", Game.input_keybind)
				},
				close_keybind: function() {
					document.removeEventListener("keydown", Game.input_keybind);
					keybind.apply.removeEventListener("click", Game.apply_keybind);
					keybind.apply.setAttribute("disabled", "disabled");
					hide(UI.overlay.keybind)
				},
				input_keybind: function(e) {
					new_keybind = e;
					keybind.tip.textContent = new_keybind.key;
					keybind.apply.removeAttribute("disabled");
					keybind.apply.addEventListener("click", Game.apply_keybind);
				},
				apply_keybind: function() {
					keybind.apply.removeEventListener("click", Game.apply_keybind);
					keybind.apply.setAttribute("disabled", "disabled");
					Key[current_key.classList[1]] = new_keybind.keyCode;
					current_key.querySelector(".key").innerHTML = new_keybind.key;
					Game.close_keybind()
				},
				version: "1.1.0"
			}

			var UI = {
				btn: {
					play: null,
					options: null,
					create: null,
					resume: null,
					exit: null
				},
				menu: {
					main: null,
					play: null,
					options: null,
					load: null
				},
				overlay: {
					menu: null,
					keybind: null,
					load: null,
					pause: null
				}
			};

			var title = {
				play: null,
				options: null,
				pause: null
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

			var keybind = {
				title: null,
				tip: null,
				cancel: null,
				apply: null
			};

			var Map = {
				container: null,
				overlay: null,
				map: null,
				uppermap: null,
				player: null
			};

			function Character(c) {
				switch (c) {
					case "mage":
						this.id = "mage";
						this.texture = {
							idle: "mage_idle.gif",
							walking: "mage_walking.gif"
						};
						this.health = 20;
						this.shield = 1;
						this.mana = 25;
						this.ability1 = {
							id: "fireball",
							name: ability.fireball,
							cost: 3,
							damage: 4
						}
						this.ability2 = {
							id: "wand",
							name: ability.wand,
							cost: 2,
							damage: 3
						}
						this.ability3 = {
							id: "lightning",
							name: ability.lightning,
							cost: 9,
							damage: 5,
							hitAllMobs: true
						}
						break;
					case "rogue":
						this.id = "rogue";
						this.texture = {
							idle: "rogue.png",
							walking: "rogue.png"
						};
						this.health = 22;
						this.shield = 2;
						this.mana = 15;
						this.ability1 = {
							id: "double_daggers",
							name: ability.double_daggers,
							cost: 2,
							damage: 4,
							base_damage: 4,
							boost_damage: 8
						}
						this.ability2 = {
							id: "stealth",
							name: ability.stealth,
							cost: 4,
							damage: 0,
							damageMult: false
						}
						this.ability3 = {
							id: "discretion",
							name: ability.discretion,
							cost: 7,
							damage: 0
						}
						break;
					case "paladin":
						this.id = "paladin";
						this.texture = {
							idle: "paladin.png",
							walking: "paladin.png"
						};
						this.health = 25;
						this.shield = 3;
						this.mana = 20;
						this.ability1 = {
							id: "sword_strike",
							name: ability.sword_strike,
							cost: 2,
							damage: 4
						}
						this.ability2 = {
							id: "parade",
							name: ability.parade,
							cost: 4,
							damage: 0,
							blockAttack: true
						}
						this.ability3 = {
							id: "regeneration",
							name: ability.regeneration,
							cost: 7,
							damage: 0,
							healthAmount: 5
						}
						break
				}
			}

			var Key = {
				forward: 90,
				backward: 83,
				left: 81,
				right: 68,
				console: 112
			};

			var player = {
				canMove: {
					top: false,
					bottom: false,
					left: false,
					right: false
				},
				direction: {
					top: false,
					bottom: false,
					left: false,
					right: false
				},
				movement: {
					on: function() {
						// allowing player movement
						window.addEventListener("keydown", player.movement.keydown);
						window.addEventListener("keyup", player.movement.keyup);
						player.canMove.top = true;
						player.canMove.bottom = true;
						player.canMove.left = true;
						player.canMove.right = true
					},
					off: function() {
						// disallowing player movement
						window.removeEventListener("keydown", player.movement.keydown);
						window.removeEventListener("keyup", player.movement.keyup);
						player.canMove.top = false;
						player.canMove.bottom = false;
						player.canMove.left = false;
						player.canMove.right = false
					},
					keydown: function(e) {
						// pressing a key
						switch (e.keyCode) {
							case Key["forward"]: // forward key
								player.canMove.top ? player.direction.top = true : player.direction.top = false;
								break;
							case Key["backward"]: // backward key
								player.canMove.bottom ? player.direction.bottom = true : player.direction.bottom = false;
								break;
							case Key["left"]: // left key
								player.canMove.left ? player.direction.left = true : player.direction.left = false;
								break;
							case Key["right"]: // right key
								player.canMove.right ? player.direction.right = true : player.direction.right = false;
								break
						}
					},
					keyup: function(e) {
						// releasing the key
						switch (e.keyCode) {
							case Key["forward"]: // forward key
								player.direction.top = false;
								break;
							case Key["backward"]: // backward key
								player.direction.bottom = false;
								break;
							case Key["left"]: // left key
								player.direction.left = false;
								break;
							case Key["right"]: // right key
								player.direction.right = false;
								break
						}
					},
					move: function() {
						player.test_collision(window["Backup"].player.pos[0].toFixed(1), window["Backup"].player.pos[1].toFixed(1));
						if (player.direction.top && player.canMove.top) window["Backup"].player.pos[1] += 0.1;
						if (player.direction.bottom && player.canMove.bottom) window["Backup"].player.pos[1] -= 0.1;
						if (player.direction.left && player.canMove.left) {
							window["Backup"].player.pos[0] -= 0.1;
							// changing player orientation
							window["Backup"].player.orientation = "left";
							Map.player.style.transform = "rotateY(180deg)"
						}
						if (player.direction.right && player.canMove.right) {
							window["Backup"].player.pos[0] += 0.1;
							// changing player orientation
							window["Backup"].player.orientation = "right";
							Map.player.style.transform = "rotateY(0)"
						}
						// moving the player
						// backup.player.pos[0] = player.x.toFixed(1);
						// backup.player.pos[1] = player.y.toFixed(1);
						player.movement.tp(window["Backup"].player.pos[0].toFixed(1), window["Backup"].player.pos[1].toFixed(1));
						window.requestAnimationFrame(player.movement.move)
					},
					tp: function(x, y) {
						Map.map.style.transform = "translateX(" + -x * 64 + "px) translateY(" + y * 64 + "px)";
						Map.uppermap.style.transform = "translateX(" + -x * 64 + "px) translateY(" + y * 64 + "px)"
					}
				},
				test_collision: function(x, y) {
					switch (window["Backup"].player.level) {
						case "lobby": // lobby collision list
							// top collisions
							if (x >= -9 && x <= 9 && y >= 6.5 && y <= 7.5) player.canMove.top = false; // hwall1
							else if (x > -8 && x < -4 && y >= -1.5 && y <= -0.5) player.canMove.top = false; // table1
							else if (x > 4 && x < 8 && y >= -1.5 && y <= -0.5) player.canMove.top = false; // table2
							else player.canMove.top = true;
							// bottom collisions
							if (x >= -9 && x < -1 && y >= -5 && y <= -4) player.canMove.bottom = false; // hwall2
							else if (x >= -9 && x < -6 && y >= -4.5 && y <= -3.5) player.canMove.bottom = false; // weapons
							else if (x > 1 && x <= 9 && y >= -5 && y <= -4) player.canMove.bottom = false; // hwall3
							else if (x >= -1 && x <= 1 && y >= -6 && y <= -5) player.canMove.bottom = false; // carpet4
							else if (x > -8 && x < -4 && y <= 3 && y >= 2) player.canMove.bottom = false; // table1
							else if (x > 4 && x < 8 && y <= 3 && y >= 2) player.canMove.bottom = false; // table2
							else player.canMove.bottom = true;
							// left collisions
							if (x >= -11 && x <= -9 && y >= -4 && y <= 6.5) player.canMove.left = false; // vwall1
							else if (x >= -2 && x <= -1 && y > -5.5 && y < -4) player.canMove.left = false; // hwall2
							else if (x >= -7 && x <= -6 && y >= -4.5 && y < -3.5) player.canMove.left = false; // weapons
							else if (x >= -5 && x <= -4 && y > -1.5 && y < 3) player.canMove.left = false; // table1
							else if (x >= 7 && x <= 8 && y > -1.5 && y < 3) player.canMove.left = false; // table2
							else player.canMove.left = true;
							// right collisions
							if (x >= 9 && x <= 11 && y >= -4 && y <= 6.5) player.canMove.right = false; // vwall2
							else if (x >= 1 && x <= 2 && y > -5.5 && y < -4) player.canMove.right = false; // hwall3
							else if (x >= -8 && x <= -7 && y > -1.5 && y < 3) player.canMove.right = false; // table1
							else if (x >= 4 && x <= 5 && y > -1.5 && y < 3) player.canMove.right = false; // table2
							else player.canMove.right = true;
							break;
						case "dungeon":
							// top
							if (x >= -1 && x < 10 && y >= 0.5 && y <= 1.5) player.canMove.top = false; // hwall1
							else if (x >= 10 && x < 15 && y >= 3.5 && y <= 4.5) player.canMove.top = false; // hwall3
							else if (x > 16 && x < 24 && y >= 3.5 && y <= 4.5) player.canMove.top = false; // hwall4
							else if (x >= 31 && x <= 35 && y >= 11.5 && y <= 12.5) player.canMove.top = false; // hwall9
							else if (x > 31.1 && x < 32.9 && y >= 10.6 && y <= 11.4) player.canMove.top = false; // chest
							else if (x >= 31 && x < 34 && y >= 4.5 && y <= 5.5) player.canMove.top = false; // table
							else if (x >= 24 && x < 31 && y >= 9.5 && y <= 10.5) player.canMove.top = false; // hwall10
							else if (x > 25 && x < 31 && y >= 3.5 && y <= 4.5) player.canMove.top = false; // hwall7
							else if (x > 11 && x < 17 && y >= 9.5 && y <= 10.5) player.canMove.top = false; // hwall13
							else if (x >= 4 && x <= 37 && y >= 21.5 && y <= 22.5) player.canMove.top = false; // hwall17
							else player.canMove.top = true;
							// bottom
							if (x >= -1 && x <= 11 && y >= -2 && y <= -1) player.canMove.bottom = false; // hwall2
							else if (x > 11 && x < 31 && y >= 1 && y <= 2) player.canMove.bottom = false; // hwall5
							else if (x >= 31 && x <= 35 && y >= 0 && y <= 1) player.canMove.bottom = false; // hwall6
							else if (x >= 31 && x < 34 && y >= 6 && y <= 7) player.canMove.bottom = false; // table
							else if (x > 25 && x < 31 && y >= 7 && y <= 8) player.canMove.bottom = false; // hwall8
							else if (x >= 10 && x < 15 && y >= 7 && y <= 8) player.canMove.bottom = false; // hwall11
							else if (x > 16 && x <= 18 && y >= 7 && y <= 8) player.canMove.bottom = false; // hwall12
							else if (x > 11 && x < 17 && y >= 18 && y <= 19) player.canMove.bottom = false; // hwall14
							else if (x >= 4 && x < 10 && y >= 18 && y <= 19) player.canMove.bottom = false; // hwall15
							else if (x > 18 && x <= 37 && y >= 18 && y <= 19) player.canMove.bottom = false; // hwall16
							else player.canMove.bottom = true;
							// left
							if (x >= -2 && x <= -1 && y >= -1 && y <= 0.5) player.canMove.left = false; // map_border1
							else if (x >= 9 && x <= 10 && y > 0.5 && y <= 3.5) player.canMove.left = false; // vwall1
							else if (x >= 30 && x <= 31 && y >= 1 && y < 2) player.canMove.left = false; // hwall5
							else if (x >= 31.9 && x <= 32.9 && y > 10.6 && y <= 11.5) player.canMove.left = false; // chest
							else if (x >= 33 && x <= 34 && y > 4.5 && y < 7) player.canMove.left = false; // table
							else if (x >= 30 && x <= 31 && y > 9.5 && y <= 11.5) player.canMove.left = false; // vwall6
							else if (x >= 30 && x <= 31 && y > 3.5 && y < 8) player.canMove.left = false; // vwall4
							else if (x >= 23 && x <= 24 && y > 3.5 && y <= 9.5) player.canMove.left = false; // vwall7
							else if (x >= 14 && x <= 15 && y > 3.5 && y < 8) player.canMove.left = false; // vwall8
							else if (x >= 9 && x <= 10 && y >= 8 && y < 19) player.canMove.left = false; // vwall10
							else if (x >= 16 && x <= 17 && y > 9.5 && y < 19) player.canMove.left = false; // vwall13
							else if (x >= 3 && x <= 4 && y >= 19 && y <= 21.5) player.canMove.left = false; // map_border2
							else player.canMove.left = true;
							// right
							if (x >= 11 && x <= 12 && y >= -1 && y < 2) player.canMove.right = false; // vwall2
							else if (x >= 35 && x <= 36 && y >= 1 && y <= 11.5) player.canMove.right = false; // vwall5
							else if (x >= 31.1 && x <= 32.1 && y > 10.6 && y <= 11.5) player.canMove.right = false; // chest
							else if (x >= 25 && x <= 26 && y > 3.5 && y < 8) player.canMove.right = false; // vwall3
							else if (x >= 16 && x <= 17 && y > 3.5 && y < 8) player.canMove.right = false; // vwall9
							else if (x >= 18 && x <= 19 && y >= 8 && y < 19) player.canMove.right = false; // vwall11
							else if (x >= 11 && x <= 12 && y > 9.5 && y < 19) player.canMove.right = false; // vwall12
							else if (x >= 37 && x <= 38 && y >= 19 && y <= 21.5) player.canMove.right = false; // map_border3
							else player.canMove.right = true;
							break;
						case "diamond":
							// top
							if (x >= -1 && x <= 1 && y >= 1 && y <= 2) player.canMove.top = false; // map_border1
							else if (x > 1 && x < 11 && y >= -11.5 && y <= -10.5) player.canMove.top = false; // hwall1
							else if (x > 12 && x <= 27 && y >= -11.5 && y <= -10.5) player.canMove.top = false; // hwall2
							else if (x > 20 && x < 22 && y >= -12.5 && y <= -11.5) player.canMove.top = false; // hwall4
							else if (x > 26 && x <= 27 && y >= -13.5 && y <= -12.5) player.canMove.top = false; // table2
							else if (x >= 7 && x <= 16 && y >= -4.5 && y <= -3.5) player.canMove.top = false; // hwall6
							else if (x > 7 && x < 11 && y >= -7.5 && y <= -6.5) player.canMove.top = false; // table1
							else if (x > 13.1 && x < 14.9 && y >= -5.4 && y <= -4.4) player.canMove.top = false; // chest
							else if (x > 1 && x <= 19 && y >= -17.5 && y <= -16.5) player.canMove.top = false; // hwall9
							else if (x >= 7 && x < 11 && y >= -22.5 && y <= -21.5) player.canMove.top = false; // hwall12
							else player.canMove.top = true;
							// bottom
							if (x > 1 && x <= 27 && y >= -15 && y <= -14) player.canMove.bottom = false; // hwall3
							else if (x > 20 && x < 22 && y >= -14 && y <= -13) player.canMove.bottom = false; // hwall5
							else if (x > 26 && x <= 27 && y >= -13 && y <= -12) player.canMove.bottom = false; // table2
							else if (x >= 7 && x < 11 && y >= -9 && y <= -8) player.canMove.bottom = false; // hwall7
							else if (x > 12 && x <= 16 && y >= -9 && y <= -8) player.canMove.bottom = false; // hwall8
							else if (x > 7 && x < 11 && y >= -6 && y <= -5) player.canMove.bottom = false; // table1
							else if (x >= -1 && x < 11 && y >= -20 && y <= -19) player.canMove.bottom = false; // hwall10
							else if (x > 12 && x <= 19 && y >= -20 && y <= -19) player.canMove.bottom = false; // hwall11
							else if (x >= 7 && x <= 12 && y >= -25 && y <= -24) player.canMove.bottom = false; // hwall13
							else player.canMove.bottom = true;
							// left
							if (x >= -2 && x <= -1 && y >= -19 && y <= 1) player.canMove.left = false; // vwall1
							else if (x >= 21 && x <= 22 && y > -12.5 && y <= -11.5) player.canMove.left = false; // hwall4
							else if (x >= 21 && x <= 22 && y >= -14 && y < -13) player.canMove.left = false; // hwall5
							else if (x >= 10 && x <= 11 && y > -11.5 && y < -8) player.canMove.left = false; // vwall7
							else if (x >= 6 && x <= 7 && y >= -8 && y <= -4.5) player.canMove.left = false; // vwall5
							else if (x >= 10 && x <= 11 && y > -7.5 && y < -5) player.canMove.left = false; // table1
							else if (x >= 13.9 && x <= 14.9 && y > -5.4 && y <= -4.5) player.canMove.left = false; // chest
							else if (x >= 10 && x <= 11 && y > -22.5 && y < -19) player.canMove.left = false; // vwall10
							else if (x >= 6 && x <= 7 && y >= -24 && y <= -22.5) player.canMove.left = false; // map_border3
							else player.canMove.left = true;
							// right
							if (x >= 1 && x <= 2 && y > -11.5 && y <= 1) player.canMove.right = false; // vwall2
							else if (x >= 1 && x <= 2 && y > -17.5 && y < -14) player.canMove.right = false; // vwall3
							else if (x >= 27 && x <= 28 && y >= -14 && y <= -11.5) player.canMove.right = false; // vwall4
							else if (x >= 20 && x <= 21 && y > -12.5 && y <= -11.5) player.canMove.right = false; // hwall4
							else if (x >= 20 && x <= 21 && y >= -14 && y < -13) player.canMove.right = false; // hwall5
							else if (x >= 26 && x <= 27 && y > -13.5 && y < -12) player.canMove.right = false; // table2
							else if (x >= 12 && x <= 13 && y > -11.5 && y < -8) player.canMove.right = false; // vwall8
							else if (x >= 16 && x <= 17 && y >= -8 && y <= -4.5) player.canMove.right = false; // vwall6
							else if (x >= 7 && x <= 8 && y > -7.5 && y < -5) player.canMove.right = false; // table1
							else if (x >= 13.1 && x <= 14.1 && y > -5.4 && y <= -4.5) player.canMove.right = false; // chest
							else if (x >= 19 && x <= 20 && y >= -19 && y <= -17.5) player.canMove.right = false; // vwall9
							else if (x >= 12 && x <= 13 && y >= -24 && y < -19) player.canMove.right = false; // vwall11
							else player.canMove.right = true;
							break
					}
				}
			};

			var ability = {
				fireball: "",
				wand: "",
				lightning: "",
				double_daggers: "",
				stealth: "",
				discretion: "",
				sword_strike: "",
				parade: "",
				regeneration: ""
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

			var raw_menus = ["menu-play", "menu-options"],
				TempPlayer,
				is_player_name_ok = false,
				is_game_name_ok = false,
				is_character_selected = false,
				character_selected = "",
				current_key = "",
				new_keybind = "",
				keybind_tip = "",
				json_error = "";

			function $(e) {return document.querySelector(e)}

			function show(e, v) {return e.style.display = (v === undefined) ? "block" : v}

			function hide(e) {return e.style.display = "none"}

			function esc(e) {
				if (e.keyCode === 27) {
					if (UI.overlay.keybind.style.display === "flex") Game.close_keybind();
					else {
						for (i = 0; i < raw_menus.length; i++) {
							if ($(`.${raw_menus[i]}`).style.display === "flex") Game.toggle_menu(raw_menus[i], "close")
						}
					}
				}
			}

			function pause_menu(e) {
				if (e.keyCode === 27 && UI.overlay.menu.style.display !== "flex") {
					if (UI.overlay.pause.style.display === "flex") {
						UI.overlay.pause.style["-webkit-animation-name"] = "overlay_pause_fade_out";
						UI.overlay.pause.style["animation-name"] = "overlay_pause_fade_out";
						Map.container.classList.remove("blur");
						setTimeout(function() {
							hide(UI.overlay.pause);
							player.movement.on()
						}, 200)
					} else {
						player.movement.off();
						Map.container.classList.add("blur");
						show(UI.overlay.pause, "flex");
						UI.overlay.pause.style["-webkit-animation-name"] = "overlay_pause_fade_in";
						UI.overlay.pause.style["animation-name"] = "overlay_pause_fade_in"
					}
				}
			}

			function select_character(c) {
				if (is_character_selected === false) is_character_selected = true;
				character_selected = c;
				TempPlayer = new Character(c);
				document.querySelectorAll(".btn[data-character").forEach(function(e) {e.style.backgroundImage = "url(assets/textures/btn/btn-class.png)"});
				$(`.btn[data-character=${c}]`).style.backgroundImage = "url(assets/textures/btn/btn-class-selected.png)";
				play.new_game.health_value.textContent = TempPlayer.health;
				play.new_game.shield_value.textContent = TempPlayer.shield;
				play.new_game.mana_value.textContent = TempPlayer.mana;
				Game.check_new_game_validity()
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
				UI.btn.resume = $(".btn-resume");
				UI.btn.exit = $(".btn-exit");
				// menus
				UI.menu.main = $(".menu-main");
				UI.menu.play = $(".menu-play .scrollable");
				UI.menu.options = $(".menu-options .scrollable");
				UI.menu.load = $(".menu-load");
				// overlays
				UI.overlay.menu = $(".overlay-menu");
				UI.overlay.keybind = $(".overlay-keybind");
				UI.overlay.load = $(".overlay-load");
				UI.overlay.pause = $(".overlay-pause");
				// menu titles
				title.play = $(".content-play .title");
				title.options = $(".content-options .title");
				title.pause = $(".pause-title");
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
				// keybind menu
				keybind.title = $(".keybind-title");
				keybind.tip = $(".keybind-tip");
				keybind.cancel = $(".menu-keybind .actions .btn[data-keybind='cancel']");
				keybind.apply = $(".menu-keybind .actions .btn[data-keybind='apply']");
				// audio settings
				options.audio.subtitle = $(".option.audio .subtitle");
				options.audio.music = $(".option.audio .music");
				options.audio.sound = $(".option.audio .sound");
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
				Map.overlay = $(".overlay-map");
				Map.map = $("#map");
				Map.uppermap = $("#uppermap");
				Map.player = $("#player");

				Game.init()
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
			<?php include "assets/ui/menu-keybind.html"; ?>
			<div class="overlay overlay-load"></div>
			<?php include "assets/ui/menu-load.html"; ?>
			<div class="overlay overlay-pause">
				<?php include "assets/ui/menu-pause.html"; ?>
			</div>
			<div class="map-container">
				<div class="overlay overlay-map"></div>
				<div id="player"></div>
				<div id="map"></div>
				<div id="uppermap"></div>
			</div>
		</main>
	</body>

</html>