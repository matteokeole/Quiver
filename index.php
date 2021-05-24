<!DOCTYPE html>

<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="A rogue-like web video game.">
		<meta name="version" content="1.1.0">
		<meta name="author" content="Clarisse Eynard, Léan Houdayer, Mattéo Legagneux">
		<meta name="copyright" content="© 2021 Quiver. All rights reserved.">
		<link rel="stylesheet" type="text/css" href="assets/ui/dialog.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-credits.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-keybind.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-load.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-main.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-options.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-pause.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-play.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-save.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/noscript.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/overlay.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/subtitle.css">
		<link rel="stylesheet" type="text/css" href="assets/textures/btn.css">
		<link rel="stylesheet" type="text/css" href="assets/textures/icon.css">
		<link rel="stylesheet" type="text/css" href="assets/textures/map.css">
		<link rel="stylesheet" type="text/css" href="assets/textures/texture_list.css">
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

			div, input, button {font-family: Quiver}

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
					save.backup.value = "";
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
					document.querySelectorAll(".btn[data-function='refresh']").forEach(function(e) {
						e.addEventListener("click", function() {location.reload()})
					});
					$(".option-name.credits").addEventListener("click", Game.open_credits);
					UI.btn.save.addEventListener("click", function() {Game.update_save_backup(window["Backup"])});
					UI.btn.close_credits.addEventListener("click", Game.close_credits);
					UI.btn.copy.addEventListener("click", function() {
						// copying backup content
						save.backup.select();
						save.backup.setSelectionRange(0, save.backup.value.length);
						document.execCommand("copy");
						this.textContent = copy_success
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
						UI.btn.save.textContent = r["save.text"];
						UI.btn.copy.textContent = r["copy.text"];
						copy_success = r["copy_success.text"];
						UI.btn.exit.textContent = r["exit.text"];
						UI.btn.close_credits.textContent = r["close.text"];
						UI.btn.main_menu.textContent = r["main_menu.text"];
						// menu titles
						title.play.textContent = r["play.text"];
						title.options.textContent = r["options.text"];
						title.save.textContent = r["save.text"];
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
						// credit menu
						credits.title.textContent = r["credits.text"];
						copyright = r.credits["copyright.text"];
						copyright_fool = r.credits["copyright_fool.text"];
						credits.clarisse_job.textContent = r.credits["clarisse_job.text"];
						credits.lean_job.textContent = r.credits["lean_job.text"];
						credits.matteo_job.textContent = r.credits["matteo_job.text"];
						// save menu
						save.tip.textContent = r["save_tip.text"];
						// ability names
						ability_id.fireball = r.character.mage["fireball.text"];
						ability_id.wand = r.character.mage["wand.text"];
						ability_id.lightning = r.character.mage["lightning.text"];
						ability_id.double_daggers = r.character.rogue["double_daggers.text"];
						ability_id.stealth = r.character.rogue["stealth.text"];
						ability_id.discretion = r.character.rogue["discretion.text"];
						ability_id.sword_strike = r.character.paladin["sword_strike.text"];
						ability_id.parade = r.character.paladin["parade.text"];
						ability_id.regeneration = r.character.paladin["regeneration.text"];
						// ability descriptions
						ability_desc.fireball = r.character.mage["fireball_desc.text"];
						ability_desc.wand = r.character.mage["wand_desc.text"];
						ability_desc.lightning = r.character.mage["lightning_desc.text"];
						ability_desc.double_daggers = r.character.rogue["double_daggers_desc.text"];
						ability_desc.stealth = r.character.rogue["stealth_desc.text"];
						ability_desc.discretion = r.character.rogue["discretion_desc.text"];
						ability_desc.sword_strike = r.character.paladin["sword_strike_desc.text"];
						ability_desc.parade = r.character.paladin["parade_desc.text"];
						ability_desc.regeneration = r.character.paladin["regeneration_desc.text"];
						// ability titles
						ability1_title = r["ability1_title.text"];
						ability2_title = r["ability2_title.text"];
						ult_title = r["ult_title.text"];
						// errors/infos
						json_error = r.error["json_error.text"];
						copy_success = r["copy_success.text"];
						// level subtitles
						Subtitle.lobby.text = r.subtitle["lobby.text"];
						Subtitle.dungeon.text = r.subtitle["dungeon.text"];
						Subtitle.diamond.text = r.subtitle["diamond.text"];
						// teller names
						Teller.narrator = r.teller["narrator.text"];
						Teller.innkeeper = r.teller["innkeeper.text"];
						Teller.goblin = r.teller["goblin.text"];
						// dialogs
						// lobby dialogs
						for (i = 0; i < Dialog.lobby.length; i++) {Dialog.lobby[i].text = r.dialog.lobby[i].text}
						Dialog.lobby[2].options[0] = r.dialog.lobby[2].options[0];
						Dialog.lobby[2].options[1] = r.dialog.lobby[2].options[1];
						for (i = 0; i < Dialog.dungeon.length; i++) {Dialog.dungeon[i].text = r.dialog.dungeon[i].text} // dungeon dialogs
						for (i = 0; i < Dialog.diamond.length; i++) {Dialog.diamond[i].text = r.dialog.diamond[i].text} // diamond dialogs
						for (i = 0; i < Dialog.misc.kill.length; i++) {Dialog.misc.kill[i].text = r.dialog.misc.kill[i].text} // kill dialogs
						for (i = 0; i < Dialog.misc.flight.length; i++) {Dialog.misc.flight[i].text = r.dialog.misc.flight[i].text} // flight dialogs
						// character info
						if (character_selected !== "") select_character(character_selected)
					});
					// showing the check mark on the selected language
					document.querySelectorAll(".option.lang .option-name").forEach(function(e) {e.querySelector(".icon").style.visibility = "hidden"});
					$(`.option.lang .option-name.${l}`).querySelector(".icon").style.visibility = "visible"
				},
				create_backup: function(player) {
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
							health: player.health,
							health_max: player.health,
							mana: player.mana,
							mana_max: player.mana,
							resistance: player.shield,
							base_resistance: player.shield,
							pos: [0, 0],
							orientation: "right"
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
							Entity0: {
								health: Entity.Skeleton2.health,
								isDead: false,
								hasFled: false
							},
							Entity1: {
								health: Entity.Skeleton1.health,
								isDead: false,
								hasFled: false
							},
							Entity2: {
								health: Entity.Goblin.health,
								isDead: false,
								hasFled: false
							},
							Entity3: {
								health: Entity.Skeleton1.health,
								isDead: false,
								hasFled: false
							},
							Entity4: {
								health: Entity.Goblin.health,
								isDead: false,
								hasFled: false
							},
							Entity5: {
								health: Entity.Skeleton2.health,
								isDead: false,
								hasFled: false
							},
							Entity6: {
								health: Entity.Skeleton2.health,
								isDead: false,
								hasFled: false
							}
						}
					}
					return Backup
				},
				launch_new_game: function() {
					Game.toggle_menu("menu-play", "close");
					// initializing the player
					var Player = new Character(character_selected);
					Player.canMove = player.canMove;
					Player.direction = player.direction;
					Player.movement = player.movement;
					TempPlayer = undefined;
					window["Backup"] = Game.create_backup(Player);
					Game.load(window["Backup"], Player)
				},
				open_backup: function(e) {
					var file = e.target.files[0];
					if (!file) return;
					var reader = new FileReader();
					reader.onload = function(e) {
						show($(".container-backup"), "flex"); // opening
						try {
							var temp = JSON.parse(e.target.result); // e.target.result = backup content
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
					window["Backup"].date.lastConnection = now();
					// initializing the player
					var Player = new Character(window["Backup"].player.character);
					Player.canMove = player.canMove;
					Player.direction = player.direction;
					Player.movement = player.movement;
					Game.load(window["Backup"], Player)
				},
				update_save_backup: function(backup) {save.backup.value = JSON.stringify(backup, null, "\t")},
				load: function(backup, player) {
					document.removeEventListener("keydown", esc);
					// showing loading screen
					show(UI.overlay.load);
					UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style["animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style.backgroundColor = "#000";
					Game.generate(backup); // generating the map
					Map.player.style.backgroundImage = `url(assets/textures/entity/${player.texture.idle})`;
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
						Game.start(backup, player); // starting the game
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
							entities = r.entity,
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
						// entities
						for (i = 0; i < entities.length; i++) {
							part = document.createElement("div");
							part.className = `entity ${entities[i].entity}`;
							part.style.width = `${scale_multiplier}px`;
							part.style.height = `${scale_multiplier}px`;
							part.style.transform = `translateX(${scale_multiplier * entities[i].origin[0]}px) translateY(${(scale_multiplier * -entities[i].origin[1])}px) rotateY(${entities[i].orientation === "right" ? 0 : 180}deg)`;
							part.style.backgroundImage = `url(assets/textures/entity/${entities[i].type}.png)`;
							Map.entities.append(part)
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
					// player movement
					player.movement.on();
					window.requestAnimationFrame(player.movement.move);
					Game.story(backup.player.level) // level subtitle & dialogs
				},
				end: function() {
					player.speed = 0; // setting speed to 0 so the player can't move
					player.movement.off(); // disabling player movement
					Game.open_credits() // opening credit menu
				},
				story: function(map) {
					update_subtitle(map);
					switch (map) {
						case "lobby": // showing lobby dialogs
							Map.dialog_content.innerHTML = `${Teller.narrator}: <i>${Dialog.lobby[0].text}</i>`;
							show(Map.dialog);
							setTimeout(function() {hide(Map.dialog)}, Dialog.lobby[0].duration);
							setTimeout(function() {
								Map.dialog_content.innerHTML = `${Teller.innkeeper}: <i>${Dialog.lobby[1].text.split("%s").join(window["Backup"].player.nickname)}</i>`;
								show(Map.dialog);
								setTimeout(function() {hide(Map.dialog)}, Dialog.lobby[1].duration);
								setTimeout(function() {
									Map.dialog_content.innerHTML = `${Teller.innkeeper}: <i>${Dialog.lobby[2].text}</i>`;
									Map.dialog_option1.textContent = `-> ${Dialog.lobby[2].options[0]}`;
									Map.dialog_option2.textContent = `-> ${Dialog.lobby[2].options[1]}`;
									show(Map.dialog_option1);
									show(Map.dialog_option2);
									show(Map.dialog);
									Map.dialog_option1.addEventListener("click", function() {
										// continuing the game
										hide(Map.dialog_option1);
										hide(Map.dialog_option2);
										hide(Map.dialog);
										setTimeout(function() {
											Map.dialog_content.innerHTML = `${Teller.innkeeper}: <i>${Dialog.lobby[3].text}</i>`;
											show(Map.dialog);
											setTimeout(function() {hide(Map.dialog)}, Dialog.lobby[3].duration);
											setTimeout(function() {
												can_enter_dungeon = true;
												Map.dialog_content.innerHTML = `${Teller.innkeeper}: <i>${Dialog.lobby[4].text}</i>`;
												show(Map.dialog);
												setTimeout(function() {hide(Map.dialog)}, Dialog.lobby[4].duration)
											}, Dialog.lobby[3].duration + 1000)
										}, 1000);
									});
									Map.dialog_option2.addEventListener("click", function() {
										// alternative ending
										hide(Map.dialog_option1);
										hide(Map.dialog_option2);
										hide(Map.dialog);
										setTimeout(function() {
											Map.dialog_content.innerHTML = `${Teller.innkeeper}: <i>${Dialog.lobby[5].text}</i>`;
											show(Map.dialog);
										}, 1000)
										setTimeout(function() {
											hide(Map.dialog);
											game_ended = true;
											// closing pause menu if opened
											UI.overlay.pause.style["-webkit-animation-name"] = "overlay_pause_fade_out";
											UI.overlay.pause.style["animation-name"] = "overlay_pause_fade_out";
											Map.container.classList.remove("blur");
											setTimeout(function() {
												hide(UI.overlay.pause);
												player.movement.on()
											}, 200);
											document.removeEventListener("keydown", pause_menu);
											Game.end()
										}, Dialog.lobby[5].duration + 1000)
									})
								}, Dialog.lobby[1].duration + 1000)
							}, Dialog.lobby[0].duration + 1000);
							break;
						case "dungeon":
							dialog(Dialog.dungeon[0]);
							break;
						case "diamond":
							dialog(Dialog.diamond[0]);
							break
					}
				},
				update_map: function(backup, map) {
					while (Map.map.firstChild) {Map.map.removeChild(Map.map.lastChild)}
					while (Map.uppermap.firstChild) {Map.uppermap.removeChild(Map.uppermap.lastChild)}
					while (Map.entities.firstChild) {Map.entities.removeChild(Map.entities.lastChild)}
					backup.player.level = map; // disabling lobby collisions and enabling dungeon collisions
					// setting player coords to (0, 0)
					backup.player.pos[0] = 0;
					backup.player.pos[1] = 0;
					Game.generate(backup, map); // generating the next map
					Game.story(map) // next map story (subtitle/dialogs)
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
				open_credits: function() {
					Game.toggle_menu("menu-options", "close");
					show(UI.overlay.load);
					UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style["animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style.backgroundColor = "#000";
					setTimeout(function() {
						var donut = Math.floor(6 * Math.random() + 1);
						credits.copyright.textContent = (donut === 1) ? copyright_fool : copyright;
						if (game_ended) {
							hide(UI.btn.close_credits);
							show(UI.btn.main_menu)
						} else {
							hide(UI.btn.main_menu);
							show(UI.btn.close_credits)
						}
						show(UI.menu.credits, "flex")
					}, 600);
					setTimeout(function() {
						UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style["animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style.backgroundColor = "transparent";
						if (!game_ended)	document.addEventListener("keydown", esc);
						setTimeout(function() {hide(UI.overlay.load)}, 600)
					}, 1200)
				},
				close_credits: function() {
					document.removeEventListener("keydown", esc);
					show(UI.overlay.load);
					UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style["animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style.backgroundColor = "#000";
					setTimeout(function() {hide(UI.menu.credits)}, 600);
					setTimeout(function() {
						UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style["animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style.backgroundColor = "transparent";
						setTimeout(function() {hide(UI.overlay.load)}, 600)
					}, 1200)
				},
				version: "1.1.0"
			}

			var UI = {
				btn: {
					play: null,
					options: null,
					create: null,
					save: null,
					resume: null,
					copy: null,
					exit: null,
					close_credits: null,
					main_menu: null
				},
				menu: {
					main: null,
					play: null,
					options: null,
					load: null,
					credits: null
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
				save: null,
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
					ability1_title: null,
					ability1_desc: null,
					ability2_title: null,
					ability2_desc: null,
					ult_title: null,
					ult_desc: null,
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

			var credits = {
				title: null,
				copyright: null,
				clarisse_job: null,
				lean_job: null,
				matteo_job: null
			};

			var save = {
				tip: null,
				backup: null
			};

			var Map = {
				container: null,
				overlay: null,
				map: null,
				uppermap: null,
				player: null,
				entities: null,
				subtitle: null,
				dialog: null,
				dialog_content: null,
				dialog_option1: null,
				dialog_option2: null
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
							cost: 3,
							damage: 4
						};
						this.ability2 = {
							id: "wand",
							cost: 2,
							damage: 3
						};
						this.ult = {
							id: "lightning",
							cost: 9,
							damage: 5,
							hitAllMobs: true
						};
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
							cost: 2,
							damage: 4,
							base_damage: 4,
							boost_damage: 8
						};
						this.ability2 = {
							id: "stealth",
							cost: 4,
							damage: 0,
							damageMult: false
						};
						this.ult = {
							id: "discretion",
							cost: 7,
							damage: 0
						};
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
							cost: 2,
							damage: 4
						};
						this.ability2 = {
							id: "parade",
							cost: 4,
							damage: 0,
							blockAttack: true
						};
						this.ult = {
							id: "regeneration",
							cost: 7,
							damage: 0,
							healthAmount: 5
						};
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
				speed: 0.1,
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
						if (player.direction.top && player.canMove.top) window["Backup"].player.pos[1] += player.speed;
						if (player.direction.bottom && player.canMove.bottom) window["Backup"].player.pos[1] -= player.speed;
						if (player.direction.left && player.canMove.left) {
							window["Backup"].player.pos[0] -= player.speed;
							// changing player orientation
							window["Backup"].player.orientation = "left";
							Map.player.style.transform = "rotateY(180deg)"
						}
						if (player.direction.right && player.canMove.right) {
							window["Backup"].player.pos[0] += player.speed;
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
						var t = "translateX(" + -x * 64 + "px) translateY(" + y * 64 + "px)";
						Map.map.style.transform = t;
						Map.uppermap.style.transform = t;
						Map.entities.style.transform = t
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
							// custom cases
							if (x >= -1 && x <= 1 && y >= -6 && y <= -5) {
								// exit
								if (!custom_case_near && can_enter_dungeon) {
									custom_case_near = true; // a custom case is near
									Game.update_map(window["Backup"], "dungeon") // entering the dungeon
								}
							}
							else custom_case_near = false; // no custom cases near
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
							// custom cases
							if (x >= -2 && x <= -1 && y >= -1 && y <= 0.5) {
								// map border
								if (!custom_case_near) {
									custom_case_near = true; // a custom case is near
									dialog(Dialog.dungeon[1]) // border dialog
								}
							}
							else if ((x > 26 && x < 28 && y >= 2 && y <= 3.5) || (x > 26 && x < 28 && y >= 8 && y <= 9.5)) {
								// chest room
								if (!custom_case_near && !chest_room_visited) {
									custom_case_near = true; // a custom case is near
									chest_room_visited = true;
									dialog(Dialog.dungeon[2]) // chest room dialog
								}
							}
							else if (x >= 31.1 && x <= 32.9 && y >= 10.6 && y <= 11.6) {
								// chest
								if (!custom_case_near) {
									custom_case_near = true; // a custom case is near
									dialog(Dialog.dungeon[3]) // chest dialog
								}
							}
							else if (x >= 10 && x <= 11 && y > 9.5 && y < 11.5) {
								// corridor monster
								if (!custom_case_near && !corridor_visited) {
									custom_case_near = true; // a custom case is near
									corridor_visited = true;
									dialog(Dialog.dungeon[4]) // corridor monster dialog
								}
							}
							else if (x >= 3 && x <= 4 && y >= 19 && y <= 21.5) {
								// false exit
								if (!custom_case_near) {
									custom_case_near = true; // a custom case is near
									dialog(Dialog.dungeon[5]) // false exit dialog
								}
							}
							else if (x >= 37 && x <= 38 && y >= 19 && y <= 21.5) {
								// exit
								if (!custom_case_near) {
									custom_case_near = true; // a custom case is near
									Game.update_map(window["Backup"], "diamond") // entering "diamond" level
								}
							}
							else custom_case_near = false; // no custom cases near
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
							// custom cases
							if (x > 1 && x < 3 && y >= -14 && y <= -11.5) {
								// goblin noise
								if (!custom_case_near && !goblin_noise_done) {
									custom_case_near = true; // a custom case is near
									goblin_noise_done = true;
									dialog(Dialog.diamond[1]) // goblin noise dialog
								}
							}
							else if (x >= 13.1 && x <= 14.9 && y >= -5.4 && y <= -4.4) {
								// chest
								if (!custom_case_near) {
									custom_case_near = true; // a custom case is near
									dialog(Dialog.diamond[2]) // chest dialog
								}
							}
							else if (x >= -1 && x <= 1 && y >= 1 && y <= 2) {
								// map border 1
								if (!custom_case_near) {
									custom_case_near = true; // a custom case is near
									dialog(Dialog.dungeon[1]) // border 1 dialog
								}
							}
							else if (x >= 6 && x <= 7 && y >= -24 && y <= -22.5) {
								// map border 2
								if (!custom_case_near) {
									custom_case_near = true; // a custom case is near
									dialog(Dialog.diamond[3]) // border 2 dialog
								}
							}
							else if ((x > 26 && x <= 27 && y >= -13.5 && y <= -12.5) || (x > 26 && x <= 27 && y >= -13 && y <= -12) || (x >= 26 && x <= 27 && y > -13.5 && y < -12)) {
								// diamond
								if (!custom_case_near && !diamond_taken) {
									custom_case_near = true; // a custom case is near
									diamond_taken = true;
									game_ended = true;
									document.removeEventListener("keydown", pause_menu);
									Map.uppermap.removeChild(Map.uppermap.querySelector(".diamond")); // removing the diamond
									player.movement.off();
									dialog(Dialog.diamond[4]);
									setTimeout(Game.end, Dialog.diamond[4].duration)
									// main ending
								}
							}
							else custom_case_near = false;
							break
					}
				}
			};

			var ability_id = {
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

			var ability_desc = {
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

			var Subtitle = {
				lobby: {
					text: null,
					duration: 4000
				},
				dungeon: {
					text: null,
					duration: 4000
				},
				diamond: {
					text: null,
					duration: 4000
				}
			};

			var Teller = {
				narrator: null,
				innkeeper: null,
				goblin: null
			}

			var Dialog = {
				lobby: [
					{
						teller: "narrator",
						text: null,
						duration: 10000
					}, {
						teller: "innkeeper",
						text: null,
						duration: 5000
					}, {
						teller: "innkeeper",
						text: null,
						duration: 0,
						options: [null, null]
					}, {
						teller: "innkeeper",
						text: null,
						duration: 10000
					}, {
						teller: "innkeeper",
						text: null,
						duration: 10000
					}, {
						teller: "innkeeper",
						text: null,
						duration: 4000
					}
				],
				dungeon: [
					{
						teller: "narrator",
						text: null,
						duration: 5000
					}, {
						teller: "narrator",
						text: null,
						duration: 3000
					}, {
						teller: "narrator",
						text: null,
						duration: 4000
					}, {
						teller: "narrator",
						text: null,
						duration: 4000
					}, {
						teller: "narrator",
						text: null,
						duration: 4000
					}, {
						teller: "narrator",
						text: null,
						duration: 4000
					}
				],
				diamond: [
					{
						teller: "narrator",
						text: null,
						duration: 5000
					}, {
						teller: "goblin",
						text: null,
						duration: 3000
					}, {
						teller: "narrator",
						text: null,
						duration: 4000
					}, {
						teller: "narrator",
						text: null,
						duration: 3000
					}, {
						teller: "narrator",
						text: null,
						duration: 4000
					}
				],
				misc: {
					kill: [
						{
							text: null,
							duration: 5000
						}, {
							text: null,
							duration: 5000
						}, {
							text: null,
							duration: 5000
						}
					],
					flight: [
						{
							text: null,
							duration: 5000
						}, {
							text: null,
							duration: 5000
						}, {
							text: null,
							duration: 5000
						}
					]
				}
			};

			// other variables
			var raw_menus = ["menu-play", "menu-options", "menu-save"],
				TempPlayer,
				is_player_name_ok = false,
				is_game_name_ok = false,
				is_character_selected = false,
				character_selected = "",
				current_key = "",
				new_keybind = "",
				keybind_tip = "",
				ability1_title = "",
				ability2_title = "",
				ult_title = "",
				json_error = "",
				copy_success = "",
				custom_case_near = false,
				can_enter_dungeon = false,
				chest_room_visited = false,
				corridor_visited = false,
				goblin_noise_done = false,
				diamond_taken = false,
				game_ended = false,
				copyright = "",
				copyright_fool = "";

			function $(e) {return document.querySelector(e)}

			function show(e, v) {return e.style.display = (v === undefined) ? "block" : v}

			function hide(e) {return e.style.display = "none"}

			function esc(e) {
				if (e.keyCode === 27) {
					if (UI.overlay.keybind.style.display === "flex") Game.close_keybind();
					else if (UI.menu.credits.style.display === "flex") Game.close_credits();
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
				is_character_selected = true;
				character_selected = c;
				Game.check_new_game_validity();
				TempPlayer = new Character(c);
				show(play.new_game.character_info);
				document.querySelectorAll(".btn[data-character").forEach(function(e) {e.style.backgroundImage = "url(assets/textures/btn/btn-class.png)"});
				$(`.btn[data-character=${c}]`).style.backgroundImage = "url(assets/textures/btn/btn-class-selected.png)";
				play.new_game.health_value.textContent = TempPlayer.health;
				play.new_game.shield_value.textContent = TempPlayer.shield;
				play.new_game.mana_value.textContent = TempPlayer.mana;
				play.new_game.ability1_title.textContent = `${ability1_title}: ${ability_id[TempPlayer.ability1.id]}`;
				play.new_game.ability1_desc.textContent = ability_desc[TempPlayer.ability1.id];
				play.new_game.ability2_title.textContent = `${ability2_title}: ${ability_id[TempPlayer.ability2.id]}`;
				play.new_game.ability2_desc.textContent = ability_desc[TempPlayer.ability2.id];
				play.new_game.ult_title.textContent = `${ult_title}: ${ability_id[TempPlayer.ult.id]}`;
				play.new_game.ult_desc.textContent = ability_desc[TempPlayer.ult.id]
			}

			function update_subtitle(map) {
				Map.subtitle.textContent = Subtitle[map].text;
				show(Map.subtitle);
				setTimeout(function() {hide(Map.subtitle)}, Subtitle[map].duration)
			}

			function dialog(dialog) {
				Map.dialog_content.innerHTML = `${Teller[dialog.teller]}: <i>${dialog.text}</i>`;
				show(Map.dialog);
				setTimeout(function() {hide(Map.dialog)}, dialog.duration)
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
				UI.btn.save = $(".btn-save");
				UI.btn.create = $(".menu-new_game .btn-create");
				UI.btn.resume = $(".btn-resume");
				UI.btn.copy = $(".btn-copy");
				UI.btn.exit = $(".btn-exit");
				UI.btn.close_credits = $(".btn-close-credits");
				UI.btn.main_menu = $(".btn-main-menu");
				// menus
				UI.menu.main = $(".menu-main");
				UI.menu.play = $(".menu-play .scrollable");
				UI.menu.options = $(".menu-options .scrollable");
				UI.menu.load = $(".menu-load");
				UI.menu.credits = $(".menu-credits");
				// overlays
				UI.overlay.menu = $(".overlay-menu");
				UI.overlay.keybind = $(".overlay-keybind");
				UI.overlay.load = $(".overlay-load");
				UI.overlay.pause = $(".overlay-pause");
				// menu titles
				title.play = $(".content-play .title");
				title.options = $(".content-options .title");
				title.save = $(".content-save .title");
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
				play.new_game.ability1_title = $(".ability1_info .ability_title");
				play.new_game.ability1_desc = $(".ability1_info .ability_desc");
				play.new_game.ability2_title = $(".ability2_info .ability_title");
				play.new_game.ability2_desc = $(".ability2_info .ability_desc");
				play.new_game.ult_title = $(".ult_info .ability_title");
				play.new_game.ult_desc = $(".ult_info .ability_desc");
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
				// credit menu
				credits.title = $(".credit-title");
				credits.copyright = $("#copyright");
				credits.clarisse_job = $(".job.clarisse");
				credits.lean_job = $(".job.lean");
				credits.matteo_job = $(".job.matteo");
				// save menu
				save.tip = $(".save_tip");
				save.backup = $("#current_backup");
				// map elements
				Map.container = $(".map-container");
				Map.overlay = $(".overlay-map");
				Map.map = $("#map");
				Map.uppermap = $("#uppermap");
				Map.player = $("#player");
				Map.entities = $("#entities");
				Map.subtitle = $(".level-subtitle");
				Map.dialog = $(".dialog");
				Map.dialog_content = $(".dialog-content");
				Map.dialog_option1 = $(".dialog-option-1");
				Map.dialog_option2 = $(".dialog-option-2");

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
				<?php include "assets/ui/menu-save.html"; ?>
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
				<div id="entities"></div>
				<div class="level-subtitle"></div>
				<div class="dialog">
					<div class="dialog-content"></div>
					<button class="dialog-option dialog-option-1"></button>
					<button class="dialog-option dialog-option-2"></button>
				</div>
			</div>
			<?php include "assets/ui/menu-credits.html"; ?>
		</main>
	</body>

</html>