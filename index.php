<!DOCTYPE html>

<html lang="fr">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="A rogue-like web video game.">
		<meta name="version" content="1.1.0">
		<meta name="author" content="Clarisse Eynard, Léan Houdayer, Mattéo Legagneux">
		<meta name="copyright" content="© 2021 Quiver. All right reserved.">
		<link rel="stylesheet" type="text/css" href="assets/font/font.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/noscript.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/global.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu/menu.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/menu/options.css">
		<link rel="stylesheet" type="text/css" href="assets/ui/button.css">
		<style type="text/css">
			body {
				margin: 0;
				background-color: #000;
				cursor: default
			}

			::selection {background-color: rgba(0, 0, 0, 0.2)} /* text selection color */
		</style>
		<script type="text/javascript" src="script/main.js"></script>
		<title>Quiver</title>
	</head>

	<body>
		<noscript>
			<p>
				Ce jeu nécessite JavaScript pour fonctionner.<br>
				Activez JavaScript dans votre navigateur puis réessayez.<br><br>
				<a href="">Actualiser</a>
			</p>
			<div class="version">Version : 1.1.0</div>
		</noscript>
		<main>
			<?php include "assets/ui/menu/menu-main.html"; ?>
			<div class="overlay">
				<?php include "assets/ui/menu/menu-new_game.html"; ?>
				<?php include "assets/ui/menu/menu-launch_backup.html"; ?>
				<?php include "assets/ui/menu/menu-options.html"; ?>
			</div>
		</main>
	</body>

</html>