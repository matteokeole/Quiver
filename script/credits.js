function credits() {
	var copyright = Game.UI.section.credits.querySelector("#copyright"),
		donut = 0;

	// mmh donut
	donut = Math.floor(6 * Math.random() + 1);
	if (donut === 1) copyright.innerHTML = "Copyright (C) 2021<br>Donut distribute!";
	else copyright.innerHTML = "Copyright (C) 2021<br>Do not distribute!";

	// opening
	show(Game.UI.section.credits_overlay);
	show(Game.UI.section.credits);
	// fade out
	setTimeout(function() {hide(Game.UI.section.credits_overlay)}, 2000);

	// closing with button
	Game.UI.section.credits.querySelector(".btn-close").onclick = close
}

function end_credits() {
	var copyright = Game.UI.section.credits.querySelector("#copyright"),
		donut = 0;

	// mmh donut
	donut = Math.floor(6 * Math.random() + 1);
	if (donut === 1) copyright.innerHTML = "Copyright (C) 2021<br>Donut distribute!";
	else copyright.innerHTML = "Copyright (C) 2021<br>Do not distribute!";

	Game.UI.section.credits.style.background = "#000";
	hide(Game.UI.section.credits.querySelector(".btn-close"));
	show(Game.UI.section.credits.querySelector(".return"));

	// opening
	player.isInCredits = true;
	show(Game.UI.section.credits_overlay);
	show(Game.UI.section.credits);
	// fade out
	setTimeout(function() {hide(Game.UI.section.credits_overlay)}, 2000);

	// returning to the main menu
	Game.UI.section.credits.querySelector(".return").onclick = function() {location.reload()}
}