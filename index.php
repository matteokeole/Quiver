<!--
	Quiver, a rogue-like web video game!
	Made by Clarisse Eynard, Léan Houdayer and Mattéo Legagneux.
	Copyright (C) 2021. All rights reserved.
-->

<!DOCTYPE html>

<html prefix="og: http://ogp.me/ns#" lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="A rogue-like web video game!">
		<meta name="keywords" content="jeu, vidéo, web, 2d, rogue-like, dungeon, donjon, clarisse, eynard, léan, houdayer, mattéo, legagneux">
		<meta name="author" content="Clarisse Eynard, Léan Houdayer, Mattéo Legagneux">
		<meta name="version" content="1.1.4">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta property="og:type" content="website">
		<meta property="og:url" content="https://quivergame.000webhostapp.com">
		<meta property="og:site_name" content="matteoo34's GitHub">
		<meta property="og:title" content="Quiver">
		<meta property="og:description" content="A rogue-like web video game!">
		<meta property="og:image" content="https://quivergame.000webhostapp.com/assets/textures/menu/background.jpg">
		<meta name="twitter:card" content="summary_large_image">
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="apple-touch-icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="assets/ui/dialog.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/footer.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/hud.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-credits.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-death.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu-fight.css">
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
		<link rel="stylesheet" type="text/css" href="assets/ui/main.css">
		<title lang="en" translate="no">Quiver</title>
	</head>

	<body>
		<noscript>
			<p>
				This game needs JavaScript to work.<br>
				Enable JavaScript in your browser, then retry.<br><br>
				<a href="">Reload</a>
			</p>
			<div class="version">1.1.4</div>
		</noscript>
		<main>
			<!-- Game content goes here -->
			<div id="console">
				<div class="console-title">Console</div>
				<div class="x"></div>
				<div class="y"></div>
			</div>
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
			<div class="overlay overlay-death">
				<?php include "assets/ui/menu-death.html"; ?>
			</div>
			<div class="map-container">
				<div class="overlay overlay-map"></div>
				<div id="player"></div>
				<div id="map"></div>
				<div id="uppermap"></div>
				<div id="entities"></div>
				<div class="hud">
					<div class="nickname"></div>
					<div class="health_info"></div>
					<progress class="health_value"></progress>
					<div class="mana_info"></div>
					<progress class="mana_value"></progress>
				</div>
				<div class="level-subtitle"></div>
				<div class="dialog">
					<div class="dialog-content"></div>
					<button class="dialog-option dialog-option-1"></button>
					<button class="dialog-option dialog-option-2"></button>
				</div>
				<?php include "assets/ui/menu-fight.html"; ?>
			</div>
			<?php include "assets/ui/menu-credits.html"; ?>
			<?php include "assets/ui/footer.html"; ?>
		</main>
		<script>
			const Game = {
				init: function() {
					$("meta[name='version']").setAttribute("version", this.version); // Set the current version as metadata
					this.resize(); // Print display infos on load
					footer.version.textContent = `Quiver ${this.version} - Copyright (C) 2021`; // Print the current version on the footer
					$("title").textContent = `Quiver ${this.version}`; // Print the window title on the last version
					Game.lang("en_US"); // Default language is english (this setting can be modified in options)
					// Reset inputs
					document.addEventListener("keydown", function(e) {
						if (e.keyCode === Key.console) ($("#console").style.display !== "block") ? show($("#console")) : hide($("#console"))
					});
					let empty = "";
					play.new_game.player_name.value = empty;
					play.new_game.game_name.value = empty;
					play.launch_backup.backup.value = empty;
					save.backup.value = empty;
					show($("main")); // Open game window
					document.querySelectorAll(".btn:not([disabled]), .option-name").forEach(function(e) {
						// Click sound
						e.addEventListener("click", function() {play_sound("click")})
					});
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
					// About section
					let howtoplay,
					updates_history,
					req1 = new XMLHttpRequest(),
					req2 = new XMLHttpRequest();
					// Request play guide
					req1.open("GET", "howtoplay.txt");
					req1.send();
					req1.addEventListener("load", function() {howtoplay = this.response});
					// Request updates history
					req2.open("GET", "updates_history.txt");
					req2.send();
					req2.addEventListener("load", function() {updates_history = this.response})
					$(".option-name.tutorial").addEventListener("click", function() {alert(howtoplay)});
					$(".option-name.updates").addEventListener("click", function() {alert(updates_history)});
					$(".option-name.credits").addEventListener("click", Game.open_credits);
					UI.btn.save.addEventListener("click", function() {Game.update_save_backup(window["Backup"])});
					UI.btn.close_credits.addEventListener("click", Game.close_credits);
					UI.btn.copy.addEventListener("click", function() {
						// Copy backup content
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
					// Music volume
					// Chrome/Safari/Firefox
					$(".music .volume").addEventListener("input", function() {set_volume_nb(this, this.nextElementSibling, "music")});
					// IE
					$(".music .volume").addEventListener("change", function() {set_volume_nb(this, this.nextElementSibling, "music")});
					// Volume number
					$(".music .volume_nb").addEventListener("keyup", function() {set_volume_range(this, this.previousElementSibling, "music")});
					// Sound volume
					// Chrome/Safari/Firefox
					$(".sound .volume").addEventListener("input", function() {set_volume_nb(this, this.nextElementSibling, "sound")});
					// IE
					$(".sound .volume").addEventListener("change", function() {set_volume_nb(this, this.nextElementSibling, "sound")});
					// Volume number
					$(".sound .volume_nb").addEventListener("keyup", function() {set_volume_range(this, this.previousElementSibling, "sound")})
				},
				check_input_value: function(input) {
					if (/^\s+$/.test(input.value) || input.value.length === 0) {
						// The input value is composed only of whitespaces or is empty
						(input.getAttribute("id") === "player_name") ? is_player_name_ok = false : is_game_name_ok = false;
						play.new_game.play.setAttribute("disabled", "disabled");
						play.new_game.play.removeEventListener("click", Game.launch_new_game)
					}
					else if (input.getAttribute("id") === "player_name") is_player_name_ok = true;
					else is_game_name_ok = true; // No errors
					Game.check_new_game_validity()
				},
				check_new_game_validity: function() {
					if (is_player_name_ok && is_game_name_ok && is_character_selected) {
						play.new_game.play.removeAttribute("disabled");
						play.new_game.play.addEventListener("click", Game.launch_new_game)
					}
				},
				lang: function(l) {
					// Change the UI language
					// l -> language [str]
					// JSON lang file request
					let path = `assets/lang/${l}.json`,
						request = new XMLHttpRequest();
					request.open("GET", path);
					request.responseType = "json";
					request.send();
					request.addEventListener("load", function() {
						// Request accepted
						let r = this.response[l]; // Get file content
						$("html").setAttribute("lang", r["lang"]);
						// Apply new language to the interface
						// Buttons
						document.querySelectorAll(".btn-class").forEach(function(e) {e.querySelector(".character_title").textContent = r.character[e.classList[2]]["name.text"]});
						document.querySelectorAll(".btn-options").forEach(function(e) {e.textContent = r["options.text"]});
						document.querySelectorAll(".btn-main-menu").forEach(function(e) {e.textContent = r["main_menu.text"]});
						UI.btn.play.textContent = r["play.text"];
						UI.btn.resume.textContent = r["resume.text"];
						UI.btn.save.textContent = r["save.text"];
						UI.btn.copy.textContent = r["copy.text"];
						copy_success = r["copy_success.text"];
						UI.btn.exit.textContent = r["exit.text"];
						UI.btn.close_credits.textContent = r["close.text"];
						// Menu titles
						title.play.textContent = r["play.text"];
						title.options.textContent = r["options.text"];
						title.save.textContent = r["save.text"];
						title.pause.textContent = r["pause.text"];
						// Play menu
						// New Game section
						play.new_game.subtitle.textContent = r["new_game.text"];
						play.new_game.player_name_tip.textContent = r.new_game["player_name_tip.text"];
						play.new_game.player_name.setAttribute("placeholder", r.new_game["player_name_placeholder.text"]);
						play.new_game.game_name_tip.textContent = r.new_game["game_name_tip.text"];
						play.new_game.game_name.setAttribute("placeholder", r.new_game["game_name_placeholder.text"]);
						play.new_game.character_selection_tip.textContent = r.new_game["character_selection_tip.text"];
						play.new_game.play.textContent = r["new_game.text"];
						// Launch Backup section
						play.launch_backup.subtitle.textContent = r["launch_backup.text"];
						play.launch_backup.open.textContent = r["open_backup.text"];
						play.launch_backup.backup_tip.textContent = r["backup_tip.text"];
						play.launch_backup.launch.textContent = r["launch_this_backup.text"];
						// Options menu
						// Keybinds settings
						options.keybind.subtitle.textContent = r.options["keybind.text"];
						options.keybind.forward.firstChild.textContent = r.options["keybind:forward.text"];
						options.keybind.backward.firstChild.textContent = r.options["keybind:backward.text"];
						options.keybind.left.firstChild.textContent = r.options["keybind:left.text"];
						options.keybind.right.firstChild.textContent = r.options["keybind:right.text"];
						options.keybind.console.firstChild.textContent = r.options["keybind:console.text"];
						// Keybinds menu
						keybind.cancel.textContent = r["cancel.text"];
						keybind.apply.textContent = r["apply.text"];
						keybind_tip = r["keybind_tip.text"];
						// Audio settings
						options.audio.subtitle.textContent = r.options["audio.text"];
						options.audio.music.firstChild.textContent = r.options["audio:music.text"];
						options.audio.sound.firstChild.textContent = r.options["audio:sound.text"];
						// Language settings
						options.lang.subtitle.textContent = r.options["lang.text"];
						options.lang.en_US.firstChild.textContent = r.options["lang:en_US.text"];
						options.lang.es_ES.firstChild.textContent = r.options["lang:es_ES.text"];
						options.lang.fr_FR.firstChild.textContent = r.options["lang:fr_FR.text"];
						// About settings
						options.about.subtitle.textContent = r.options["about.text"];
						options.about.tutorial.textContent = r.options["about:tutorial.text"];
						options.about.updates.textContent = r.options["about:updates.text"];
						options.about.credits.textContent = r.options["about:credits.text"];
						// Credits menu
						credits.title.textContent = r["credits.text"];
						copyright = r.credits["copyright.text"];
						copyright_fool = r.credits["copyright_fool.text"];
						credits.clarisse_job.textContent = r.credits["clarisse_job.text"];
						credits.lean_job.textContent = r.credits["lean_job.text"];
						credits.matteo_job.textContent = r.credits["matteo_job.text"];
						// Save menu
						save.tip.textContent = r["save_tip.text"];
						// Abilities names
						ability_id.fireball = r.character.mage["fireball.text"];
						ability_id.wand = r.character.mage["wand.text"];
						ability_id.lightning = r.character.mage["lightning.text"];
						ability_id.double_daggers = r.character.rogue["double_daggers.text"];
						ability_id.stealth = r.character.rogue["stealth.text"];
						ability_id.discretion = r.character.rogue["discretion.text"];
						ability_id.sword_strike = r.character.paladin["sword_strike.text"];
						ability_id.parade = r.character.paladin["parade.text"];
						ability_id.regeneration = r.character.paladin["regeneration.text"];
						// Abilities descriptions
						ability_desc.fireball = r.character.mage["fireball_desc.text"];
						ability_desc.wand = r.character.mage["wand_desc.text"];
						ability_desc.lightning = r.character.mage["lightning_desc.text"];
						ability_desc.double_daggers = r.character.rogue["double_daggers_desc.text"];
						ability_desc.stealth = r.character.rogue["stealth_desc.text"];
						ability_desc.discretion = r.character.rogue["discretion_desc.text"];
						ability_desc.sword_strike = r.character.paladin["sword_strike_desc.text"];
						ability_desc.parade = r.character.paladin["parade_desc.text"];
						ability_desc.regeneration = r.character.paladin["regeneration_desc.text"];
						// Stats & abilities titles
						health = r["health.text"];
						mana = r["mana.text"];
						ability1_title = r["ability1_title.text"];
						ability2_title = r["ability2_title.text"];
						ult_title = r["ult_title.text"];
						// Enemy abilities names
						enemy_ability_id.kick = r.enemy_ability["kick.text"];
						enemy_ability_id.hammer = r.enemy_ability["hammer.text"];
						enemy_ability_id.dagger = r.enemy_ability["dagger.text"];
						enemy_ability_id.arrow = r.enemy_ability["arrow.text"];
						enemy_ability_id.heal = r.enemy_ability["heal.text"];
						// Parades
						player_block_text = r["player_block.text"];
						enemy_block_text = r["enemy_block.text"];
						// Death menu
						UI.overlay.death.querySelector(".death_title").textContent = r["death_title.text"];
						UI.overlay.death.querySelector(".death_subtitle").textContent = r["death_subtitle.text"];
						// Alerts
						json_error = r.error["json_error.text"];
						copy_success = r["copy_success.text"];
						// Level subtitles
						Subtitle.lobby.text = r.subtitle["lobby.text"];
						Subtitle.dungeon.text = r.subtitle["dungeon.text"];
						Subtitle.diamond.text = r.subtitle["diamond.text"];
						// Teller names
						Teller.narrator = r.teller["narrator.text"];
						Teller.innkeeper = r.teller["innkeeper.text"];
						Teller.goblin = r.teller["goblin.text"];
						// Dialogs
						// Lobby dialogs
						for (i = 0; i < Dialog.lobby.length; i++) {Dialog.lobby[i].text = r.dialog.lobby[i].text}
						Dialog.lobby[2].options[0] = r.dialog.lobby[2].options[0];
						Dialog.lobby[2].options[1] = r.dialog.lobby[2].options[1];
						for (i = 0; i < Dialog.dungeon.length; i++) {Dialog.dungeon[i].text = r.dialog.dungeon[i].text} // dungeon dialogs
						for (i = 0; i < Dialog.diamond.length; i++) {Dialog.diamond[i].text = r.dialog.diamond[i].text} // diamond dialogs
						for (i = 0; i < Dialog.misc.kill.length; i++) {Dialog.misc.kill[i].text = r.dialog.misc.kill[i].text} // kill dialogs
						for (i = 0; i < Dialog.misc.flight.length; i++) {Dialog.misc.flight[i].text = r.dialog.misc.flight[i].text} // flight dialogs
						// Character info & fight menu
						fight.title.textContent = r["fight.text"];
						if (character_selected !== "") {
							select_character(character_selected)
							let TempPlayer = new Character(character_selected);
							fight.btn_ability1.querySelector(".ability_name").textContent = ability_id[TempPlayer.ability1.id];
							fight.btn_ability2.querySelector(".ability_name").textContent = ability_id[TempPlayer.ability2.id];
							fight.btn_ult.querySelector(".ability_name").textContent = ability_id[TempPlayer.ult.id]
						}
						fight.btn_flee.textContent = r["flee.text"];
						// HUD updates
						if (window["Backup"] !== undefined) {
							update_hud(window["Backup"]);
							if (current_enemy !== undefined) update_mini_hud(window["Backup"], current_enemy_id, current_enemy)
						}
					});
					// Show a check icon on the selected language
					document.querySelectorAll(".option.lang .option-name").forEach(function(e) {e.querySelector(".icon").style.opacity = 0});
					$(`.option.lang .option-name.${l}`).querySelector(".icon").style.opacity = 1
				},
				create_backup: function(player) {
					let Backup = {
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
							shield: player.shield,
							pos: [0, 0],
							orientation: "right"
						},
						entity: [{
								health: Entity.skeleton2.health_max,
								scare: Entity.skeleton2.scare
							}, {
								health: Entity.skeleton1.health_max,
								scare: Entity.skeleton1.scare
							}, {
								health: Entity.goblin.health_max,
								scare: Entity.goblin.scare
							}, {
								health: Entity.skeleton1.health_max,
								scare: Entity.skeleton1.scare
							}, {
								health: Entity.goblin.health_max,
								scare: Entity.goblin.scare
							}, {
								health: Entity.skeleton2.health_max,
								scare: Entity.skeleton2.scare
							}, {
								health: Entity.skeleton2.health_max,
								scare: Entity.skeleton2.scare
						}]
					}
					return Backup
				},
				launch_new_game: function() {
					Game.toggle_menu("menu-play", "close");
					// Init player
					let Player = new Character(character_selected);
					Player.canMove = player.canMove;
					Player.direction = player.direction;
					Player.movement = player.movement;
					TempPlayer = undefined;
					window["Backup"] = Game.create_backup(Player);
					Game.load(window["Backup"], Player)
				},
				open_backup: function(e) {
					let file = e.target.files[0];
					if (!file) return;
					let reader = new FileReader();
					reader.onload = function(e) {
						show($(".container-backup"), "flex"); // Open the menu
						try {
							let temp = JSON.parse(e.target.result); // e.target.result = backup content
							// Show backup content
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
					// Init the player
					character_selected = window["Backup"].player.character;
					let Player = new Character(character_selected);
					Player.canMove = player.canMove;
					Player.direction = player.direction;
					Player.movement = player.movement;
					Game.load(window["Backup"], Player)
				},
				update_save_backup: function(backup) {save.backup.value = JSON.stringify(backup, null, "\t")},
				load: function(backup, player) {
					document.removeEventListener("keydown", esc);
					// Show loading screen
					show(UI.overlay.load);
					UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style["animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style.backgroundColor = "#000";
					Game.generate(backup); // Generate the map
					Map.player.style.backgroundImage = `url(assets/textures/entity/${player.texture.idle})`;
					update_hud(backup); // Update HUD values
					// Load overlay/screen animations
					setTimeout(function() {
						hide(footer.container);
						show(UI.menu.load, "flex")
					}, 600);
					setTimeout(function() {
						// show(footer.container);
						UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style["animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style.backgroundColor = "transparent"
					}, 1200);
					setTimeout(function() {
						UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_in";
						UI.overlay.load.style["animation-name"] = "overlay_load_fade_in";
						UI.overlay.load.style.backgroundColor = "#000";
						Game.start(backup, player); // Start game
						hide(UI.menu.main)
					}, 4600);
					// Hide loading screen and showing game
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
					// Request the map data
					let path = `maps/${backup.player.level}.json`,
						map_request = new XMLHttpRequest();
					map_request.open("GET", path);
					map_request.responseType = "json";
					map_request.send();
					map_request.addEventListener("load", function() {
						// Request accepted, get file content and generate the map
						let r = this.response[backup.player.level],
							map = r.map,
							uppermap = r.uppermap,
							entities = r.entity,
							next_level = r.next_level,
							part,
							scale_multiplier = 64;
						// Map parts
						for (i = 0; i < map.length; i++) {
							part = document.createElement("div");
							part.className = `part ${map[i].part}`;
							part.style.width = `${scale_multiplier * map[i].size[0]}px`;
							part.style.height = `${scale_multiplier * map[i].size[1]}px`;
							part.style.transform = `translateX(${scale_multiplier * map[i].origin[0]}px) translateY(${(scale_multiplier * -map[i].origin[1])}px)`;
							part.style.backgroundImage = `url(assets/textures/map/${map[i].texture})`;
							Map.map.append(part)
						}
						// Uppermap parts
						for (i = 0; i < uppermap.length; i++) {
							part = document.createElement("div");
							part.className = `part ${uppermap[i].part}`;
							part.style.width = `${scale_multiplier * uppermap[i].size[0]}px`;
							part.style.height = `${scale_multiplier * uppermap[i].size[1]}px`;
							part.style.transform = `translateX(${scale_multiplier * uppermap[i].origin[0]}px) translateY(${(scale_multiplier * -uppermap[i].origin[1])}px)`;
							part.style.backgroundImage = `url(assets/textures/map/${uppermap[i].texture})`;
							Map.uppermap.append(part)
						}
						// Entities
						for (i = 0; i < entities.length; i++) {
							part = document.createElement("div");
							part.className = `entity ${entities[i].entity}`;
							part.style.width = `${scale_multiplier}px`;
							part.style.height = `${scale_multiplier}px`;
							part.style.transform = `translateX(${scale_multiplier * entities[i].origin[0]}px) translateY(${(scale_multiplier * -entities[i].origin[1])}px) rotateY(${entities[i].orientation === "right" ? 0 : 180}deg)`;
							part.style.backgroundImage = `url(assets/textures/entity/${entities[i].type}.png)`;
							if (backup.entity[entities[i].entity.substr(entities[i].entity.length - 1)].health !== 0) Map.entities.append(part)
						}
					})
				},
				start: function(backup, player) {
					UI.menu.options.querySelector(".about").removeChild($(".option-name.credits")); // Remove credit button from the option menu when playing the game
					document.addEventListener("keydown", pause_menu);
					UI.btn.resume.addEventListener("click", function() {
						footer.container.style.opacity = 0;
						UI.overlay.pause.style["-webkit-animation-name"] = "overlay_pause_fade_out";
						UI.overlay.pause.style["animation-name"] = "overlay_pause_fade_out";
						Map.container.classList.remove("blur");
						setTimeout(function() {
							hide(UI.overlay.pause);
							hide(footer.container);
							player.movement.on()
						}, 200)
					});
					// Player movement
					player.movement.on();
					window.requestAnimationFrame(player.movement.move);
					Game.story(backup.player.level) // Level subtitle & dialogs
				},
				end: function() {
					player.speed = 0; // Set speed to 0 so the player can't move
					player.movement.off(); // Disable player movement
					Game.open_credits() // Open credit menu
				},
				story: function(map) {
					update_subtitle(map);
					switch (map) {
						case "lobby": // Show lobby dialogs
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
										// Continue the game
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
										// Alternative end
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
											// Close pause menu if opened
											UI.overlay.pause.style["-webkit-animation-name"] = "overlay_pause_fade_out";
											UI.overlay.pause.style["animation-name"] = "overlay_pause_fade_out";
											Map.container.classList.remove("blur");
											setTimeout(function() {
												hide(UI.overlay.pause);
												player.movement.on()
											}, 200);
											// Close save menu if opened
											Game.toggle_menu("menu-save", "close");
											// Close options menu if opened
											Game.toggle_menu("menu-options", "close");
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
				fight: function(backup, enemy_id, enemy) {
					player.movement.off();
					in_fight = true;
					current_enemy_id = enemy_id;
					current_enemy = enemy;
					rogue_damage_mult = 1; // Reset Rogue damage multiplier (Stealth ability)
					TempPlayer = new Character(backup.player.character);
					fight.player_avatar.style.backgroundImage = `url(assets/textures/entity/${TempPlayer.texture.idle})`;
					fight.enemy_avatar.style.backgroundImage = `url(assets/textures/entity/${enemy}.png)`;
					fight.btn_ability1.querySelector(".ability_name").textContent = ability_id[TempPlayer.ability1.id];
					fight.btn_ability1.querySelector(".cost").textContent = TempPlayer.ability1.cost;
					fight.btn_ability2.querySelector(".ability_name").textContent = ability_id[TempPlayer.ability2.id];
					fight.btn_ability2.querySelector(".cost").textContent = TempPlayer.ability2.cost;
					fight.btn_ult.querySelector(".ability_name").textContent = ability_id[TempPlayer.ult.id];
					fight.btn_ult.querySelector(".cost").textContent = TempPlayer.ult.cost;
					update_mini_hud(backup, enemy_id, enemy);
					update_ability_use(backup);
					show(UI.menu.fight);
					show(UI.menu.fight.querySelector(".actions"));
					fight.btn_ability1.addEventListener("click", ability1);
					fight.btn_ability2.addEventListener("click", ability2);
					fight.btn_ult.addEventListener("click", ult);
					fight.btn_flee.addEventListener("click", flee)
				},
				death: function() {
					game_ended = true;
					play_sound("pop");
					// Close pause menu if opened
					document.removeEventListener("keydown", pause_menu);
					UI.overlay.pause.style["-webkit-animation-name"] = "overlay_pause_fade_out";
					UI.overlay.pause.style["animation-name"] = "overlay_pause_fade_out";
					Map.container.classList.remove("blur");
					setTimeout(function() {hide(UI.overlay.pause)}, 200);
					// Close save menu if opened
					Game.toggle_menu("menu-save", "close");
					// Close option menu if opened
					Game.toggle_menu("menu-options", "close");
					player.movement.off();
					hide(hud.container);
					hide(UI.menu.fight);
					Map.player.style.backgroundImage = "url(assets/textures/entity/something.png)";
					Map.container.classList.add("grayscale");
					show(UI.overlay.death, "flex");
					UI.overlay.death.style["-webkit-animation-name"] = "overlay_pause_fade_in";
					UI.overlay.death.style["animation-name"] = "overlay_pause_fade_in"
				},
				update_map: function(backup, map) {
					while (Map.map.firstChild) {Map.map.removeChild(Map.map.lastChild)}
					while (Map.uppermap.firstChild) {Map.uppermap.removeChild(Map.uppermap.lastChild)}
					while (Map.entities.firstChild) {Map.entities.removeChild(Map.entities.lastChild)}
					backup.player.level = map; // Disable lobby collisions and enabling dungeon collisions
					// Set player coords to (0, 0)
					backup.player.pos[0] = 0;
					backup.player.pos[1] = 0;
					// Give full health and mana to the player when it passes to the next level
					backup.player.health = backup.player.health_max;
					backup.player.mana = backup.player.mana_max;
					update_hud(backup);
					Game.generate(backup, map); // Generate the next map
					Game.story(map) // Next map story (subtitle/dialogs)
				},
				toggle_menu: function(m, s) {
					// Toggle the display of a specified menu
					// m -> menu [str]
					// s -> status (1: open or 0: close) [number]
					let menu = UI.overlay.menu.querySelector(`.${m}`),
						st = menu.querySelector(".scrollbox-top"),
						sb = menu.querySelector(".scrollbox-bottom"),
						content = menu.querySelector(".content"),
						scrollable = content.querySelector(".scrollable");
					switch (s) {
						case "open": // Open the menu
							if (play.launch_backup.backup_info.classList.contains("error")) play.launch_backup.backup_info.textContent = ""; // Remove JSON error info
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
							scrollable.scrollTop = 0; // Scroll to top (animation-less)
							scrollable.style.scrollBehavior = "smooth";
							setTimeout(function() {
								content.style.visibility = "visible";
								document.addEventListener("keydown", esc)
							}, 200);
							break;
						case "close": // Close with button or with the Escape key
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
				// Keybinds methods
				open_keybind: function(k) {
					current_key = k;
					keybind.title.textContent = current_key.firstChild.textContent;
					keybind.tip.textContent = keybind_tip;
					show(UI.overlay.keybind, "flex"); // Show the menu
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
						let donut = Math.floor(6 * Math.random() + 1);
						credits.copyright.textContent = (donut === 1) ? copyright_fool : copyright;
						if (game_ended) {
							hide(UI.btn.close_credits);
							show($(".menu-credits .btn-main-menu"))
						} else {
							hide($(".menu-credits .btn-main-menu"));
							show(UI.btn.close_credits)
						}
						show(UI.menu.credits, "flex");
						hide(footer.container)
					}, 600);
					setTimeout(function() {
						UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style["animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style.backgroundColor = "transparent";
						if (!game_ended) document.addEventListener("keydown", esc);
						setTimeout(function() {hide(UI.overlay.load)}, 600)
					}, 1200)
				},
				close_credits: function() {
					document.removeEventListener("keydown", esc);
					show(UI.overlay.load);
					UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style["animation-name"] = "overlay_load_fade_in";
					UI.overlay.load.style.backgroundColor = "#000";
					setTimeout(function() {
						hide(UI.menu.credits);
						show(footer.container)
					}, 600);
					setTimeout(function() {
						UI.overlay.load.style["-webkit-animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style["animation-name"] = "overlay_load_fade_out";
						UI.overlay.load.style.backgroundColor = "transparent";
						setTimeout(function() {hide(UI.overlay.load)}, 600)
					}, 1200)
				},
				resize: function() {
					// Print display infos on the footer
					footer.display.textContent = `Display: ${window.innerWidth} * ${window.innerHeight}`
				},
				version: "1.1.4"
			}

			let UI = {
				btn: {
					play: null,
					options: null,
					create: null,
					save: null,
					resume: null,
					copy: null,
					exit: null,
					close_credits: null
				},
				menu: {
					main: null,
					play: null,
					options: null,
					load: null,
					fight: null,
					credits: null
				},
				overlay: {
					menu: null,
					keybind: null,
					load: null,
					pause: null,
					death: null
				}
			},
			title = {
				play: null,
				options: null,
				save: null,
				pause: null
			},
			play = {
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
			},
			options = {
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
					tutorial: null,
					updates: null,
					credits: null
				}
			},
			keybind = {
				title: null,
				tip: null,
				cancel: null,
				apply: null
			},
			credits = {
				title: null,
				copyright: null,
				clarisse_job: null,
				lean_job: null,
				matteo_job: null
			},
			load = {
				loading: null
			},
			save = {
				tip: null,
				backup: null
			},
			Map = {
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

			let Key = {
				forward: 90,
				backward: 83,
				left: 81,
				right: 68,
				console: 112
			},
			player = {
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
						// Enable player movement
						player.canMove.top = true;
						player.canMove.bottom = true;
						player.canMove.left = true;
						player.canMove.right = true;
						window.addEventListener("keydown", player.movement.keydown);
						window.addEventListener("keyup", player.movement.keyup)
					},
					off: function() {
						// Disallow player movement
						player.canMove.top = false;
						player.canMove.bottom = false;
						player.canMove.left = false;
						player.canMove.right = false;
						player.direction.top = false;
						player.direction.bottom = false;
						player.direction.left = false;
						player.direction.right = false;
						window.removeEventListener("keydown", player.movement.keydown);
						window.removeEventListener("keyup", player.movement.keyup)
					},
					keydown: function(e) {
						// Press key
						switch (e.keyCode) {
							case Key.forward: // Forward key
								player.canMove.top ? player.direction.top = true : player.direction.top = false;
								break;
							case Key.backward: // Backward key
								player.canMove.bottom ? player.direction.bottom = true : player.direction.bottom = false;
								break;
							case Key.left: // Left key
								player.canMove.left ? player.direction.left = true : player.direction.left = false;
								break;
							case Key.right: // Right key
								player.canMove.right ? player.direction.right = true : player.direction.right = false
						}
					},
					keyup: function(e) {
						// Release key
						switch (e.keyCode) {
							case Key.forward: // Forward key
								player.direction.top = false;
								break;
							case Key.backward: // Backward key
								player.direction.bottom = false;
								break;
							case Key.left: // Left key
								player.direction.left = false;
								break;
							case Key.right: // Right key
								player.direction.right = false
						}
					},
					move: function() {
						player.test_collision(window["Backup"].player.pos[0].toFixed(1), window["Backup"].player.pos[1].toFixed(1));
						if (player.direction.top && player.canMove.top) window["Backup"].player.pos[1] += player.speed;
						if (player.direction.bottom && player.canMove.bottom) window["Backup"].player.pos[1] -= player.speed;
						if (player.direction.left && player.canMove.left) {
							window["Backup"].player.pos[0] -= player.speed;
							// Change player orientation
							Map.player.style.transform = "rotateY(180deg)";
							window["Backup"].player.orientation = "left"
						}
						if (player.direction.right && player.canMove.right) {
							window["Backup"].player.pos[0] += player.speed;
							// Change player orientation
							Map.player.style.transform = "rotateY(0)";
							window["Backup"].player.orientation = "right"
						}
						// Console coordinates
						$("#console .x").textContent = `Player X: ${window["Backup"].player.pos[0].toFixed(1)}`;
						$("#console .y").textContent = `Player Y: ${window["Backup"].player.pos[1].toFixed(1)}`;
						// Move the player
						player.movement.tp(window["Backup"].player.pos[0].toFixed(1), window["Backup"].player.pos[1].toFixed(1));
						window.requestAnimationFrame(player.movement.move)
					},
					tp: function(x, y) {
						let t = "translateX(" + -x * 64 + "px) translateY(" + y * 64 + "px)";
						Map.map.style.transform = t;
						Map.uppermap.style.transform = t;
						Map.entities.style.transform = t
					}
				},
				test_collision: function(x, y) {
					switch (window["Backup"].player.level) {
						case "lobby": // Lobby collision list
							// Top collisions
							if (x >= -9 && x <= 9 && y >= 6.5 && y <= 7.5) player.canMove.top = false; // hwall1
							else if (x > -8 && x < -4 && y >= -1.5 && y <= -0.5) player.canMove.top = false; // table1
							else if (x > 4 && x < 8 && y >= -1.5 && y <= -0.5) player.canMove.top = false; // table2
							else player.canMove.top = true;
							// Bottom collisions
							if (x >= -9 && x < -1 && y >= -5 && y <= -4) player.canMove.bottom = false; // hwall2
							else if (x >= -9 && x < -6 && y >= -4.5 && y <= -3.5) player.canMove.bottom = false; // weapons
							else if (x > 1 && x <= 9 && y >= -5 && y <= -4) player.canMove.bottom = false; // hwall3
							else if (x >= -1 && x <= 1 && y >= -6 && y <= -5) player.canMove.bottom = false; // carpet4
							else if (x > -8 && x < -4 && y <= 3 && y >= 2) player.canMove.bottom = false; // table1
							else if (x > 4 && x < 8 && y <= 3 && y >= 2) player.canMove.bottom = false; // table2
							else player.canMove.bottom = true;
							// Left collisions
							if (x >= -11 && x <= -9 && y >= -4 && y <= 6.5) player.canMove.left = false; // vwall1
							else if (x >= -2 && x <= -1 && y > -5.5 && y < -4) player.canMove.left = false; // hwall2
							else if (x >= -7 && x <= -6 && y >= -4.5 && y < -3.5) player.canMove.left = false; // weapons
							else if (x >= -5 && x <= -4 && y > -1.5 && y < 3) player.canMove.left = false; // table1
							else if (x >= 7 && x <= 8 && y > -1.5 && y < 3) player.canMove.left = false; // table2
							else player.canMove.left = true;
							// Right collisions
							if (x >= 9 && x <= 11 && y >= -4 && y <= 6.5) player.canMove.right = false; // vwall2
							else if (x >= 1 && x <= 2 && y > -5.5 && y < -4) player.canMove.right = false; // hwall3
							else if (x >= -8 && x <= -7 && y > -1.5 && y < 3) player.canMove.right = false; // table1
							else if (x >= 4 && x <= 5 && y > -1.5 && y < 3) player.canMove.right = false; // table2
							else player.canMove.right = true;
							// Custom cases
							if (x >= -1 && x <= 1 && y >= -6 && y <= -5) {
								// Exit
								if (!custom_case_near && can_enter_dungeon) {
									custom_case_near = true; // Custom case near
									Game.update_map(window["Backup"], "dungeon") // Enter the dungeon
								}
							}
							else custom_case_near = false; // No custom cases near
							break;
						case "dungeon": // Dungeon level collision list
							// Top collisions
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
							// Bottom collisions
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
							// Left collisions
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
							// Right collisions
							if (x >= 11 && x <= 12 && y >= -1 && y < 2) player.canMove.right = false; // vwall2
							else if (x >= 35 && x <= 36 && y >= 1 && y <= 11.5) player.canMove.right = false; // vwall5
							else if (x >= 31.1 && x <= 32.1 && y > 10.6 && y <= 11.5) player.canMove.right = false; // chest
							else if (x >= 25 && x <= 26 && y > 3.5 && y < 8) player.canMove.right = false; // vwall3
							else if (x >= 16 && x <= 17 && y > 3.5 && y < 8) player.canMove.right = false; // vwall9
							else if (x >= 18 && x <= 19 && y >= 8 && y < 19) player.canMove.right = false; // vwall11
							else if (x >= 11 && x <= 12 && y > 9.5 && y < 19) player.canMove.right = false; // vwall12
							else if (x >= 37 && x <= 38 && y >= 19 && y <= 21.5) player.canMove.right = false; // map_border3
							else player.canMove.right = true;
							// Enemies
							if (x > 12 && x < 14 && y >= 2 && y <= 3.5) {
								// Archer skeleton
								if (!enemy_near && window["Backup"].entity[0].health !== 0) {
									enemy_near = true;
									Game.fight(window["Backup"], 0, "skeleton2")
								}
							}
							else if (x > 21 && x < 23 && y >= 2 && y <= 3.5) {
								// Guard skeleton
								if (!enemy_near && window["Backup"].entity[1].health !== 0) {
									enemy_near = true;
									Game.fight(window["Backup"], 1, "skeleton1")
								}
							}
							else if (x >= 17 && x <= 18 && y > 14 && y < 16) {
								// Goblin
								if (!enemy_near && window["Backup"].entity[2].health !== 0) {
									enemy_near = true;
									Game.fight(window["Backup"], 2, "goblin")
								}
							}
							else enemy_near = false;
							// Custom cases
							if (x >= -2 && x <= -1 && y >= -1 && y <= 0.5) {
								// Map border
								if (!custom_case_near) {
									custom_case_near = true; // Custom case near
									dialog(Dialog.dungeon[1]) // Border dialog
								}
							}
							else if ((x > 26 && x < 28 && y >= 2 && y <= 3.5) || (x > 26 && x < 28 && y >= 8 && y <= 9.5)) {
								// Chest room
								if (!custom_case_near && !chest_room_visited) {
									custom_case_near = true; // Custom case near
									chest_room_visited = true;
									dialog(Dialog.dungeon[2]) // Chest room dialog
								}
							}
							else if (x >= 31.1 && x <= 32.9 && y >= 10.6 && y <= 11.6) {
								// Chest
								if (!custom_case_near) {
									custom_case_near = true; // Custom case near
									dialog(Dialog.dungeon[3]) // Chest dialog
								}
							}
							else if (x >= 10 && x <= 11 && y > 9.5 && y < 11.5) {
								// Corridor monster
								if (!custom_case_near && !corridor_visited) {
									custom_case_near = true; // Custom case near
									corridor_visited = true;
									dialog(Dialog.dungeon[4]) // Corridor monster dialog
								}
							}
							else if (x >= 3 && x <= 4 && y >= 19 && y <= 21.5) {
								// False exit
								if (!custom_case_near) {
									custom_case_near = true; // Custom case near
									dialog(Dialog.dungeon[5]) // False exit dialog
								}
							}
							else if (x >= 37 && x <= 38 && y >= 19 && y <= 21.5) {
								// True exit
								if (!custom_case_near) {
									custom_case_near = true; // Custom case near
									Game.update_map(window["Backup"], "diamond") // Enter "diamond" level
								}
							}
							else custom_case_near = false; // No custom cases near
							break;
						case "diamond": // Diamond level collision list
							// Top collisions
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
							// Bottom collisions
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
							// Left collisions
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
							// Right collisions
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
							// Enemies
							if (x >= -1 && x <= 1 && y > -9 && y < -7) {
								// Guard skeleton
								if (!enemy_near && window["Backup"].entity[3].health !== 0) {
									enemy_near = true;
									Game.fight(window["Backup"], 3, "skeleton1")
								}
							}
							else if (x > 7 && x < 9 && y >= -14 && y <= -11.5) {
								// Goblin
								if (!enemy_near && window["Backup"].entity[4].health !== 0) {
									enemy_near = true;
									Game.fight(window["Backup"], 4, "goblin")
								}
							}
							else if (x > 18 && x < 20 && y >= -14 && y <= -11.5) {
								// Archer skeleton 1
								if (!enemy_near && window["Backup"].entity[5].health !== 0) {
									enemy_near = true;
									Game.fight(window["Backup"], 5, "skeleton2")
								}
							}
							else if (x > 8 && x < 10 && y >= -19 && y <= -17.5) {
								// Archer skeleton 2
								if (!enemy_near && window["Backup"].entity[6].health !== 0) {
									enemy_near = true;
									Game.fight(window["Backup"], 6, "skeleton2")
								}
							}
							else enemy_near = false;
							// Custom cases
							if (x > 1 && x < 3 && y >= -14 && y <= -11.5) {
								// Goblin noise
								if (!custom_case_near && !goblin_noise_done) {
									custom_case_near = true; // Custom case near
									goblin_noise_done = true;
									dialog(Dialog.diamond[1]) // Goblin noise dialog
								}
							}
							else if (x >= 13.1 && x <= 14.9 && y >= -5.4 && y <= -4.4) {
								// Chest
								if (!custom_case_near) {
									custom_case_near = true; // Custom case near
									dialog(Dialog.diamond[2]) // Chest dialog
								}
							}
							else if (x >= -1 && x <= 1 && y >= 1 && y <= 2) {
								// Map border 1
								if (!custom_case_near) {
									custom_case_near = true; // Custom case near
									dialog(Dialog.dungeon[1]) // Border 1 dialog
								}
							}
							else if (x >= 6 && x <= 7 && y >= -24 && y <= -22.5) {
								// Map border 2
								if (!custom_case_near) {
									custom_case_near = true; // Custom case near
									dialog(Dialog.diamond[3]) // Border 2 dialog
								}
							}
							else if ((x > 26 && x <= 27 && y >= -13.5 && y <= -12.5) || (x > 26 && x <= 27 && y >= -13 && y <= -12) || (x >= 26 && x <= 27 && y > -13.5 && y < -12)) {
								// Diamond
								if (!custom_case_near && !diamond_taken) {
									custom_case_near = true; // Custom case near
									diamond_taken = true;
									game_ended = true;
									Map.uppermap.removeChild(Map.uppermap.querySelector(".diamond")); // Mask the diamond
									player.movement.off();
									play_sound("diamond");
									dialog(Dialog.diamond[4]);
									setTimeout(Game.end, Dialog.diamond[4].duration) // Main ending
								}
							}
							else custom_case_near = false
					}
				}
			},
			ability_id = {
				fireball: "",
				wand: "",
				lightning: "",
				double_daggers: "",
				stealth: "",
				discretion: "",
				sword_strike: "",
				parade: "",
				regeneration: ""
			},
			ability_desc = {
				fireball: "",
				wand: "",
				lightning: "",
				double_daggers: "",
				stealth: "",
				discretion: "",
				sword_strike: "",
				parade: "",
				regeneration: ""
			},
			Entity = {
				goblin: {
					id: "goblin",
					texture: "goblin.png",
					health_max: 20,
					shield: 0,
					heal: 3,
					scare: 3,
					ability1: {
						id: "kick",
						damage: 1
					},
					ability2: {
						id: "hammer",
						damage: 2
					},
					loot: {
						death: {
							health: 4,
							mana: 6
						},
						flight: {
							health: 3,
							mana: 4
						}
					}
				},
				skeleton1: {
					id: "skeleton1",
					texture: "skeleton1.png",
					health_max: 20,
					shield: 2,
					heal: 2,
					scare: 1,
					ability1: {
						id: "dagger",
						damage: 2
					},
					loot: {
						death: {
							health: 5,
							mana: 8
						},
						flight: {
							health: 2,
							mana: 4
						}
					}
				},
				skeleton2: {
					id: "skeleton2",
					texture: "skeleton2.png",
					health_max: 20,
					shield: 1,
					scare: 2,
					ability1: {
						id: "arrow",
						damage: 3
					},
					loot: {
						death: {
							health: 3,
							mana: 7
						},
						flight: {
							health: 1,
							mana: 3
						}
					}
				}
			},
			enemy_ability_id = {
				kick: "",
				hammer: "",
				dagger: "",
				heal: "",
				arrow: ""
			},
			hud = {
				container: null,
				nickname: null,
				health_info: null,
				health_value: null,
				mana_info: null,
				mana_value: null
			},
			mini_hud = {
				container: null,
				health_info: null,
				health_value: null,
				mana_info: null,
				mana_value: null,
				enemy_health_info: null,
				enemy_health_value: null
			},
			Subtitle = {
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
			},
			Teller = {
				narrator: null,
				innkeeper: null,
				goblin: null
			},
			Dialog = {
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
							teller: "narrator",
							text: null,
							duration: 5000
						}, {
							teller: "narrator",
							text: null,
							duration: 5000
						}, {
							teller: "narrator",
							text: null,
							duration: 5000
						}
					],
					flight: [
						{
							teller: "narrator",
							text: null,
							duration: 5000
						}, {
							teller: "narrator",
							text: null,
							duration: 5000
						}, {
							teller: "narrator",
							text: null,
							duration: 5000
						}
					]
				}
			},
			fight = {
				title: null,
				player_attack: null,
				enemy_attack: null,
				player_avatar: null,
				enemy_avatar: null,
				btn_ability1: null,
				btn_ability2: null,
				btn_ult: null,
				btn_flee: null
			},
			footer = {
				container: null,
				display: null,
				version: null
			},
			// Volume
			Volume = {
				music: 1,
				sound: 1
			},
			// Other variables
			raw_menus = ["menu-play", "menu-options", "menu-save"],
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
			player_block_text = "",
			enemy_block_text = "",
			json_error = "",
			copy_success = "",
			enemy_near = false,
			custom_case_near = false,
			can_enter_dungeon = false,
			in_fight = false,
			current_enemy_id = 0,
			current_enemy = "",
			chest_room_visited = false,
			corridor_visited = false,
			goblin_noise_done = false,
			diamond_taken = false,
			game_ended = false,
			copyright = "",
			copyright_fool = "",
			health = "",
			mana = "",
			rogue_damage_mult = 1,
			rogue_discretion = false;

			// Game secondary functions
			const $ = function(e) {return document.querySelector(e)},
			Character = function(c) {
				switch (c) {
					case "mage":
						this.id = "mage";
						this.texture = {
							idle: "mage.png",
							walking: "mage.png"
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
							damage: 4
						};
						this.ability2 = {
							id: "stealth",
							cost: 4,
							damage: 0,
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
						}
				}
			},
			show = function(e, v) {return e.style.display = (v === undefined) ? "block" : v},
			hide = function(e) {return e.style.display = "none"},
			esc = function(e) {
				if (e.keyCode === 27) {
					if (UI.overlay.keybind.style.display === "flex") Game.close_keybind();
					else if (UI.menu.credits.style.display === "flex") Game.close_credits();
					else {
						for (i = 0; i < raw_menus.length; i++) {
							if ($(`.${raw_menus[i]}`).style.display === "flex") Game.toggle_menu(raw_menus[i], "close")
						}
					}
				}
			},
			pause_menu = function(e) {
				if (e.keyCode === 27 && UI.overlay.menu.style.display !== "flex") {
					if (UI.overlay.pause.style.display === "flex") {
						// Hide pause menu
						UI.overlay.pause.style["-webkit-animation-name"] = "overlay_pause_fade_out";
						UI.overlay.pause.style["animation-name"] = "overlay_pause_fade_out";
						Map.container.classList.remove("blur");
						setTimeout(function() {
							hide(UI.overlay.pause);
							footer.container.style.opacity = 0;
							setTimeout(function() {hide(footer.container)}, 200);
							if (!in_fight) player.movement.on()
						}, 200)
					} else {
						// Show pause menu (if the game isn't finished)
						if (!game_ended) {
							player.movement.off();
							show(footer.container);
							footer.container.style.opacity = 1;
							Map.container.classList.add("blur");
							show(UI.overlay.pause, "flex");
							UI.overlay.pause.style["-webkit-animation-name"] = "overlay_pause_fade_in";
							UI.overlay.pause.style["animation-name"] = "overlay_pause_fade_in"
						}
					}
				}
			},
			select_character = function(c) {
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
			},
			update_hud = function(backup) {
				hud.nickname.textContent = backup.player.nickname;
				hud.health_info.textContent = `${health} (${backup.player.health}/${backup.player.health_max})`;
				hud.health_value.setAttribute("max", backup.player.health_max);
				hud.health_value.setAttribute("value", backup.player.health);
				hud.mana_info.textContent = `${mana} (${backup.player.mana}/${backup.player.mana_max})`;
				hud.mana_value.setAttribute("max", backup.player.mana_max);
				hud.mana_value.setAttribute("value", backup.player.mana)
			},
			update_mini_hud = function(backup, enemy_id, enemy) {
				mini_hud.health_info.textContent = `${health} (${backup.player.health}/${backup.player.health_max})`;
				mini_hud.health_value.setAttribute("max", backup.player.health_max);
				mini_hud.health_value.setAttribute("value", backup.player.health);
				mini_hud.mana_info.textContent = `${mana} (${backup.player.mana}/${backup.player.mana_max})`;
				mini_hud.mana_value.setAttribute("max", backup.player.mana_max);
				mini_hud.mana_value.setAttribute("value", backup.player.mana);
				mini_hud.enemy_health_info.textContent = `${health} (${backup.entity[enemy_id].health}/${Entity[enemy].health_max})`;
				mini_hud.enemy_health_value.setAttribute("max", Entity[enemy].health_max);
				mini_hud.enemy_health_value.setAttribute("value", backup.entity[enemy_id].health)
			},
			update_subtitle = function(map) {
				Map.subtitle.textContent = Subtitle[map].text;
				show(Map.subtitle);
				setTimeout(function() {hide(Map.subtitle)}, Subtitle[map].duration)
			},
			dialog = function(dialog) {
				Map.dialog_content.innerHTML = `${Teller[dialog.teller]}: <i>${dialog.text}</i>`;
				show(Map.dialog);
				setTimeout(function() {hide(Map.dialog)}, dialog.duration)
			},
			show_player_attack = function(text) {
				hide(UI.menu.fight.querySelector(".actions"));
				$(".player_attack").textContent = text;
				show($(".player_attack"));
				setTimeout(function() {
					hide($(".player_attack"));
					enemy_turn(window["Backup"], current_enemy_id, current_enemy)
				}, 3000)
			},
			show_enemy_attack = function(text) {
				hide(UI.menu.fight.querySelector(".actions"));
				$(".enemy_attack").textContent = text;
				show($(".enemy_attack"));
				setTimeout(function() {
					hide($(".enemy_attack"));
					update_ability_use(window["Backup"]);
					show(UI.menu.fight.querySelector(".actions"))
				}, 3000)
			},
			update_ability_use = function(backup) {
				if (backup.player.mana - fight.btn_ability1.querySelector(".cost").textContent < 0) {
					fight.btn_ability1.setAttribute("disabled", "disabled");
					fight.btn_ability1.querySelector(".ability_name").style.color = "#776952"
				} else {
					fight.btn_ability1.removeAttribute("disabled");
					fight.btn_ability1.querySelector(".ability_name").style.color = "#000"
				}
				if (backup.player.mana - fight.btn_ability2.querySelector(".cost").textContent < 0) {
					fight.btn_ability2.setAttribute("disabled", "disabled");
					fight.btn_ability2.querySelector(".ability_name").style.color = "#776952"
				} else {
					fight.btn_ability2.removeAttribute("disabled");
					fight.btn_ability2.querySelector(".ability_name").style.color = "#000"
				}
				if (backup.player.mana - fight.btn_ult.querySelector(".cost").textContent < 0) {
					fight.btn_ult.setAttribute("disabled", "disabled");
					fight.btn_ult.querySelector(".ability_name").style.color = "#776952"
				} else {
					fight.btn_ult.removeAttribute("disabled");
					fight.btn_ult.querySelector(".ability_name").style.color = "#000"
				}
			},
			ability1 = function() {
				if (window["Backup"].player.mana - fight.btn_ability1.querySelector(".cost").textContent >= 0) {
					// Enough mana to use an ability
					window["Backup"].player.mana -= fight.btn_ability1.querySelector(".cost").textContent;
					if (enemy_block_test(current_enemy)) enemy_block(window["Backup"], current_enemy_id, current_enemy);
					else if (window["Backup"].entity[current_enemy_id].health - TempPlayer.ability1.damage * rogue_damage_mult <= 0) enemy_death(window["Backup"], current_enemy_id, current_enemy);
					else {
						// The enemy is alive and didn't fled
						window["Backup"].entity[current_enemy_id].health -= TempPlayer.ability1.damage * rogue_damage_mult;
						switch (window["Backup"].player.character) {
							case "mage":
								// Fireball ability
								play_sound("fireball");
								break;
							case "rogue":
								// Double Daggers ability
								rogue_damage_mult = 1; // Reset Rogue damage multiplier (Stealth ability)
								play_sound("dagger");
								break;
							case "paladin":
								// Sword Strike ability
								play_sound("dagger");
								break
						}
						// Update HUDs
						update_mini_hud(window["Backup"], current_enemy_id, current_enemy);
						update_ability_use(window["Backup"]);
						show_player_attack(ability_id[TempPlayer.ability1.id])
					}
				}
			},
			ability2 = function() {
				if (window["Backup"].player.mana - fight.btn_ability2.querySelector(".cost").textContent >= 0) {
					// Enough mana to use an ability
					window["Backup"].player.mana -= fight.btn_ability2.querySelector(".cost").textContent;
					if (enemy_block_test(current_enemy)) enemy_block(window["Backup"], current_enemy_id, current_enemy);
					else if (window["Backup"].entity[current_enemy_id].health - TempPlayer.ability1.damage * rogue_damage_mult <= 0) enemy_death(window["Backup"], current_enemy_id, current_enemy);
					else {
						// The enemy is alive and didn't fled
						switch (TempPlayer.id) {
							case "mage":
								// Wand ability
								window["Backup"].entity[current_enemy_id].health -= TempPlayer.ability2.damage;
								play_sound("wand");
								break;
							case "rogue":
								// Stealth ability
								rogue_damage_mult = 2;
								play_sound("stealth");
								break;
							case "paladin":
								// Parade ability
								window["Backup"].player.shield = 20; // Parade sets the shield to its max value for 1 round, so you're fully blocked
								play_sound("parade");
								break
						}
						// Update HUDs
						update_mini_hud(window["Backup"], current_enemy_id, current_enemy);
						update_ability_use(window["Backup"]);
						show_player_attack(ability_id[TempPlayer.ability2.id])
					}
				}
			},
			ult = function() {
				if (window["Backup"].player.mana - fight.btn_ult.querySelector(".cost").textContent >= 0) {
					// Enough mana to use an ability
					window["Backup"].player.mana -= fight.btn_ult.querySelector(".cost").textContent;
					if (enemy_block_test(current_enemy)) enemy_block(window["Backup"], current_enemy_id, current_enemy);
					else if (window["Backup"].entity[current_enemy_id].health - TempPlayer.ability1.damage * rogue_damage_mult <= 0) enemy_death(window["Backup"], current_enemy_id, current_enemy);
					else {
						// The enemy is alive and didn't fled
						switch (TempPlayer.id) {
							case "mage":
								// Lightning ability - if the enemy you're fighting blocks, no enemy will be hit
								switch (window["Backup"].player.level) {
									case "dungeon":
										window["Backup"].entity[0].health -= TempPlayer.ult.damage;
										window["Backup"].entity[1].health -= TempPlayer.ult.damage;
										window["Backup"].entity[2].health -= TempPlayer.ult.damage;
										break;
									case "diamond":
										window["Backup"].entity[3].health -= TempPlayer.ult.damage;
										window["Backup"].entity[4].health -= TempPlayer.ult.damage;
										window["Backup"].entity[5].health -= TempPlayer.ult.damage;
										window["Backup"].entity[6].health -= TempPlayer.ult.damage;
										break
								}
								play_sound("lightning");
								break;
							case "rogue":
								// Discretion ability
								rogue_discretion = true;
								window["Backup"].entity[current_enemy_id].scare *= 3;
								play_sound("discretion");
								break;
							case "paladin":
								// Regeneration ability
								(window["Backup"].player.health > 20) ? window["Backup"].player.health = 25 : window["Backup"].player.health += 5;
								play_sound("heal");
								break
						}
						// Update HUDs
						update_mini_hud(window["Backup"], current_enemy_id, current_enemy);
						update_ability_use(window["Backup"]);
						show_player_attack(ability_id[TempPlayer.ult.id])
					}
				}
			},
			flee = function() {
				if (confirm("Are you sure you want to run away?\nYou'll recover all your mana points but you'll lost 8 health points.")) {
					fight.btn_flee.removeEventListener("click", flee);
					player.movement.on()
					in_fight = false;
					enemy_near = false;
					// Replace the player
					switch (current_enemy_id) {
						case 0: // Archer skeleton
							window["Backup"].player.pos[0] -= 1;
							break;
						case 1: // Guard skeleton
							window["Backup"].player.pos[0] -= 1;
							break;
						case 2: // Goblin
							(window["Backup"].player.pos[1] < 15) ? window["Backup"].player.pos[1] -= 1 : window["Backup"].player.pos[1] += 1;
							break;
						case 3: // Guard skeleton
							window["Backup"].player.pos[1] += 1;
							break;
						case 4: // Goblin
							window["Backup"].player.pos[0] -= 1;
							break;
						case 5: // Archer skeleton
							window["Backup"].player.pos[0] -= 1;
							break;
						case 6: // Archer skeleton
							window["Backup"].player.pos[0] -= 1;
							break
					}
					hide(UI.menu.fight);
					(window["Backup"].player.health <= 8) ? Game.death() : window["Backup"].player.health -= 8;
					window["Backup"].player.mana = window["Backup"].player.mana_max;
					update_hud(window["Backup"])
				}
			},
			enemy_turn = function(backup, enemy_id, enemy) {
				// Enemy flight test
				let flight_test = Math.floor(20 * Math.random() + 1);
				if (flight_test <= backup.entity[enemy_id].scare) {
					in_fight = false;
					enemy_near = false;
					backup.entity[enemy_id].health = 0;
					(backup.player.health + Entity[enemy].loot.flight.health > backup.player.health_max) ? backup.player.health = backup.player.health_max : backup.player.health += Entity[enemy].loot.flight.health;
					(backup.player.mana + Entity[enemy].loot.flight.mana > backup.player.mana_max) ? backup.player.mana = backup.player.mana_max : backup.player.mana += Entity[enemy].loot.flight.mana;
					update_hud(backup);
					Map.entities.removeChild($(`.entity${enemy_id}`));
					hide(UI.menu.fight);
					player.movement.on();
					play_sound("cash");
					switch (enemy) {
						case "goblin":
							dialog(Dialog.misc.flight[0]);
							break;
						case "skeleton1":
							dialog(Dialog.misc.flight[1]);
							break;
						case "skeleton2":
							dialog(Dialog.misc.flight[2])
					}
				}
				else {
					// The enemy didn't fled
					if (rogue_discretion) {
						rogue_discretion = false;
						backup.entity[enemy_id].scare /= 3
					}
					if (player_block_test(backup)) {
						play_sound("parade");
						show_enemy_attack(player_block_text)
					}
					else {
						let enemy_attack_choice = Math.floor(6 * Math.random() + 1);
						switch (enemy) {
							case "goblin":
								if (enemy_attack_choice <= 3) {
									// Kick ability
									(backup.player.health - Entity[enemy].ability1.damage <= 0) ? Game.death() : backup.player.health -= Entity[enemy].ability1.damage;
									play_sound("kick");
									update_mini_hud(backup, enemy_id, enemy);
									show_enemy_attack(enemy_ability_id.kick)
								}
								else if (enemy_attack_choice > 3 && enemy_attack_choice <= 5) {
									// Hammer ability
									(backup.player.health - Entity[enemy].ability2.damage <= 0) ? Game.death() : backup.player.health -= Entity[enemy].ability2.damage;
									play_sound("hammer");
									update_mini_hud(backup, enemy_id, enemy);
									show_enemy_attack(enemy_ability_id.hammer)
								}
								else {
									// Heal ability
									(backup.entity[enemy_id].health > 17) ? backup.entity[enemy_id].health = 20 : backup.entity[enemy_id].health += 3;
									play_sound("heal");
									update_mini_hud(backup, enemy_id, enemy);
									show_enemy_attack(enemy_ability_id.heal)
								}
								break;
							case "skeleton1":
								if (enemy_attack_choice <= 4) {
									// Dagger ability
									(backup.player.health - Entity[enemy].ability1.damage <= 0) ? Game.death() : backup.player.health -= Entity[enemy].ability1.damage;
									play_sound("dagger");
									update_mini_hud(backup, enemy_id, enemy);
									show_enemy_attack(enemy_ability_id.dagger)
								}
								else {
									// Heal ability
									(backup.entity[enemy_id].health > 18) ? backup.entity[enemy_id].health = 20 : backup.entity[enemy_id].health += 2;
									play_sound("heal");
									update_mini_hud(backup, enemy_id, enemy);
									show_enemy_attack(enemy_ability_id.heal)
								}
								break;
							case "skeleton2":
								// The archer has only one attack
								(backup.player.health - Entity[enemy].ability1.damage <= 0) ? Game.death() : backup.player.health -= Entity[enemy].ability1.damage;
								play_sound("arrow");
								update_mini_hud(backup, enemy_id, enemy);
								show_enemy_attack(enemy_ability_id.arrow)
						}
					}
				}
			},
			enemy_death = function(backup, enemy_id, enemy) {
				in_fight = false;
				enemy_near = false;
				backup.entity[enemy_id].health = 0;
				(backup.player.health + Entity[enemy].loot.death.health > backup.player.health_max) ? backup.player.health = backup.player.health_max : backup.player.health += Entity[enemy].loot.death.health;
				(backup.player.mana + Entity[enemy].loot.death.mana > backup.player.mana_max) ? backup.player.mana = backup.player.mana_max : backup.player.mana += Entity[enemy].loot.death.mana;
				update_hud(backup);
				Map.entities.removeChild($(`.entity${enemy_id}`));
				hide(UI.menu.fight);
				player.movement.on();
				play_sound("cash");
				switch (enemy) {
					case "goblin":
						dialog(Dialog.misc.kill[0]);
						break;
					case "skeleton1":
						dialog(Dialog.misc.kill[1]);
						break;
					case "skeleton2":
						dialog(Dialog.misc.kill[2]);
						break
				}
			},
			enemy_block_test = function(enemy) {
				let block_test = false,
					x = Math.floor(20 * Math.random() + 1);
				if (x <= Entity[enemy].shield) block_test = true;
				return block_test
			},
			enemy_block = function(backup, enemy_id, enemy) {
				play_sound("parade");
				update_mini_hud(backup, enemy_id, enemy);
				show_player_attack(enemy_block_text)
			},
			player_block_test = function(backup) {
				let block_test = false,
					x = Math.floor(20 * Math.random() + 1);
				if (x <= backup.player.shield) block_test = true;
				if (backup.player.shield === 20) backup.player.shield = 3;
				return block_test
			},
			now = function() {
				let D = new Date();
				return `${D.getFullYear()}-${D.getMonth() + 1}-${D.getDate()}-${D.getHours()}-${D.getMinutes()}-${D.getSeconds()}`
			},
			convert_date = function(date) {
				date = date.split("-");
				return `${date[0]}/${date[1]}/${date[2]} ${date[3]}:${date[4]}:${date[5]}`
			},
			set_volume_range = function(input, target, source) {
				if (input.value.length !== 0 && /^\d*\.?\d*$/.test(input.value)) {
					// a number has been entered
					if (input.value >= 0 && input.value <= 100) {
						if (source === "sound") {
							Volume.sound = input.value / 100;
							Sound.click.volume = Volume.sound;
							Sound.fireball.volume = Volume.sound;
							Sound.wand.volume = Volume.sound;
							Sound.lightning.volume = Volume.sound;
							Sound.dagger.volume = Volume.sound;
							Sound.stealth.volume = Volume.sound;
							Sound.discretion.volume = Volume.sound;
							Sound.parade.volume = Volume.sound;
							Sound.heal.volume = Volume.sound;
							Sound.kick.volume = Volume.sound;
							Sound.hammer.volume = Volume.sound;
							Sound.arrow.volume = Volume.sound;
							Sound.cash.volume = Volume.sound;
							Sound.pop.volume = Volume.sound;
							Sound.diamond.volume = Volume.sound
						} else Volume.music = input.value / 100;
						return target.value = input.value
					}
				}
			},
			set_volume_nb = function(input, target, source) {
				if (source === "sound") {
					Volume.sound = input.value / 100;
					Sound.click.volume = Volume.sound;
					Sound.fireball.volume = Volume.sound;
					Sound.wand.volume = Volume.sound;
					Sound.lightning.volume = Volume.sound;
					Sound.dagger.volume = Volume.sound;
					Sound.stealth.volume = Volume.sound;
					Sound.discretion.volume = Volume.sound;
					Sound.parade.volume = Volume.sound;
					Sound.heal.volume = Volume.sound;
					Sound.kick.volume = Volume.sound;
					Sound.hammer.volume = Volume.sound;
					Sound.arrow.volume = Volume.sound;
					Sound.cash.volume = Volume.sound;
					Sound.pop.volume = Volume.sound;
					Sound.diamond.volume = Volume.sound
				} else Volume.music = input.value / 100;
				return target.value = input.value
			},
			play_sound = function(sound, loop) {
				Sound[sound].currentTime = 0;
				Sound[sound].loop = loop;
				Sound[sound].play()
			},
			// Musics
			Music = {},
			// Sounds
			Sound = {
				click: new Audio("assets/sounds/click.mp3"),
				fireball: new Audio("assets/sounds/fireball.mp3"),
				wand: new Audio("assets/sounds/wand.mp3"),
				lightning: new Audio("assets/sounds/lightning.mp3"),
				dagger: new Audio("assets/sounds/dagger.mp3"),
				stealth: new Audio("assets/sounds/stealth.mp3"),
				discretion: new Audio("assets/sounds/discretion.mp3"),
				parade: new Audio("assets/sounds/parade.mp3"),
				heal: new Audio("assets/sounds/heal.mp3"),
				kick: new Audio("assets/sounds/kick.mp3"),
				hammer: new Audio("assets/sounds/hammer.mp3"),
				arrow: new Audio("assets/sounds/arrow.mp3"),
				cash: new Audio("assets/sounds/cash.mp3"),
				pop: new Audio("assets/sounds/pop.mp3"),
				diamond: new Audio("assets/sounds/diamond.mp3")
			};
			// DOM selection
			// Buttons
			UI.btn.play = $(".btn-play");
			UI.btn.options = $(".btn-options");
			UI.btn.save = $(".btn-save");
			UI.btn.create = $(".menu-new_game .btn-create");
			UI.btn.resume = $(".btn-resume");
			UI.btn.copy = $(".btn-copy");
			UI.btn.exit = $(".btn-exit");
			UI.btn.close_credits = $(".btn-close-credits");
			// Menus
			UI.menu.main = $(".menu-main");
			UI.menu.play = $(".menu-play .scrollable");
			UI.menu.options = $(".menu-options .scrollable");
			UI.menu.load = $(".menu-load");
			UI.menu.fight = $(".menu-fight");
			UI.menu.credits = $(".menu-credits");
			// Overlays
			UI.overlay.menu = $(".overlay-menu");
			UI.overlay.keybind = $(".overlay-keybind");
			UI.overlay.load = $(".overlay-load");
			UI.overlay.pause = $(".overlay-pause");
			UI.overlay.death = $(".overlay-death");
			// Menu titles
			title.play = $(".content-play .title");
			title.options = $(".content-options .title");
			title.save = $(".content-save .title");
			title.pause = $(".pause-title");
			// Play menu
			// New game section
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
			// Launch backup section
			play.launch_backup.subtitle = $(".launch_backup .subtitle");
			play.launch_backup.open = $(".launch_backup .btn-open_backup");
			play.launch_backup.backup_tip = $(".launch_backup .backup_tip");
			play.launch_backup.backup = $(".launch_backup #backup");
			play.launch_backup.backup_info = $(".launch_backup .backup_info");
			play.launch_backup.launch = $(".launch_backup .btn-launch_backup");
			// Options menu
			// Keybinds settings
			options.keybind.subtitle = $(".option.keybind .subtitle");
			options.keybind.forward = $(".option.keybind .forward");
			options.keybind.backward = $(".option.keybind .backward");
			options.keybind.left = $(".option.keybind .left");
			options.keybind.right = $(".option.keybind .right");
			options.keybind.console = $(".option.keybind .console");
			// Keybinds menu
			keybind.title = $(".keybind-title");
			keybind.tip = $(".keybind-tip");
			keybind.cancel = $(".menu-keybind .actions .btn[data-keybind='cancel']");
			keybind.apply = $(".menu-keybind .actions .btn[data-keybind='apply']");
			// Audio settings
			options.audio.subtitle = $(".option.audio .subtitle");
			options.audio.music = $(".option.audio .music");
			options.audio.sound = $(".option.audio .sound");
			// Language settings
			options.lang.subtitle = $(".option.lang .subtitle");
			options.lang.en_US = $(".option.lang .en_US");
			options.lang.es_ES = $(".option.lang .es_ES");
			options.lang.fr_FR = $(".option.lang .fr_FR");
			// About settings
			options.about.subtitle = $(".option.about .subtitle");
			options.about.tutorial = $(".option.about .tutorial");
			options.about.updates = $(".option.about .updates");
			options.about.credits = $(".option.about .credits");
			// Credits menu
			credits.title = $(".credit-title");
			credits.copyright = $("#copyright");
			credits.clarisse_job = $(".job.clarisse");
			credits.lean_job = $(".job.lean");
			credits.matteo_job = $(".job.matteo");
			// Load menu
			load.loading = $(".loading");
			// Save menu
			save.tip = $(".save_tip");
			save.backup = $("#current_backup");
			// Map elements
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
			// HUD
			hud.container = $(".hud");
			hud.nickname = $(".hud .nickname");
			hud.health_info = $(".hud .health_info");
			hud.health_value = $(".hud .health_value");
			hud.mana_info = $(".hud .mana_info");
			hud.mana_value = $(".hud .mana_value");
			// Mini HUD (fight menu)
			mini_hud.container = $(".mini_hud");
			mini_hud.health_info = $(".mini_hud .health_info");
			mini_hud.health_value = $(".mini_hud .health_value");
			mini_hud.mana_info = $(".mini_hud .mana_info");
			mini_hud.mana_value = $(".mini_hud .mana_value");
			mini_hud.enemy_health_info = $(".mini_hud .enemy_health_info");
			mini_hud.enemy_health_value = $(".mini_hud .enemy_health_value");
			// Fight menu
			fight.title = $(".menu-fight .fight");
			fight.player_attack = $(".menu-fight .player_attack");
			fight.enemy_attack = $(".menu-fight .enemy_attack");
			fight.player_avatar = $(".menu-fight .player_avatar .avatar");
			fight.enemy_avatar = $(".menu-fight .enemy_avatar .avatar");
			fight.btn_ability1 = $(".menu-fight .actions .btn-ability1");
			fight.btn_ability2 = $(".menu-fight .actions .btn-ability2");
			fight.btn_ult = $(".menu-fight .actions .btn-ult");
			fight.btn_flee = $(".menu-fight .actions .btn-flee");
			// Footer
			footer.container = $("footer");
			footer.display = $("footer .display");
			footer.version = $("footer .version");
			// Launch game
			Game.init();
			// Window size
			window.addEventListener("resize", Game.resize)
		</script>
	</body>

</html>