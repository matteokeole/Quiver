function death() {
	gameEnded = true;
	document.querySelector("#player").style.backgroundImage = "url(assets/entity/something.png)";
	hide(Game.UI.section.hud);
	hide(Game.UI.wrapper.fight);
	show(Game.UI.section.death);
	setTimeout(function() {Game.UI.section.death.style.opacity = 1});
	Game.UI.section.death.querySelector(".return").onclick = function() {location.reload()}
}