function options() {
	var options_menu = Game.UI.section.options_content.querySelector(".menu"),
		canUseKey = false;

	// applying keybinds on options menu
	options_menu.querySelector("#btn-key_forward").innerHTML = String.fromCharCode(Game.options.controls[0]);
	options_menu.querySelector("#btn-key_backward").innerHTML = String.fromCharCode(Game.options.controls[1]);
	options_menu.querySelector("#btn-key_strafeLeft").innerHTML = String.fromCharCode(Game.options.controls[2]);
	options_menu.querySelector("#btn-key_strafeRight").innerHTML = String.fromCharCode(Game.options.controls[3]);

	// opening window
	show(Game.UI.section.options);
	show(Game.UI.section.options_content);

	// changing a key
	// to-do
	/*options_menu.querySelectorAll(".key").forEach(function(e) {
		e.querySelector(".btn-key").addEventListener("click", function() {
			Game.UI.section.options.style.zIndex = 9;
			// changeKey(this)
		})
	});*/

	/*document.querySelector("#btn-key_forward").click = function(e) {changeKey(0, this)};
	document.querySelector("#btn-key_backward").click = function(e) {changeKey(1, this)};
	document.querySelector("#btn-key_strafeLeft").click = function(e) {changeKey(2, this)};
	document.querySelector("#btn-key_strafeRight").click = function(e) {changeKey(3, this)};*/

	function changeKey(id, key) {
		show(Game.UI.wrapper.options);
		window.addEventListener("keyup", function(e) {
			// check if the new bind is not already used
			for (i = 0; i < Game.options.controls.length; i++) {
				if (Game.options.controls[i] === e.keyCode) {
					alert("Vous ne pouvez pas utiliser cette touche car elle est déjà attribuée.")
				} else {
					key.innerHTML = String.fromCharCode(e.keyCode)
				}
			}
			hide(Game.UI.wrapper.options);
			Game.UI.section.options.style.zIndex = 999
		})
	}

	// closing with button
	Game.UI.section.options_content.querySelector(".btn-close").onclick = function() {
		hide(Game.UI.section.options_content);
		hide(Game.UI.section.options)
	}
	Game.UI.wrapper.options.querySelector(".btn-close").onclick = function() {hide(Game.UI.wrapper.options)}
}