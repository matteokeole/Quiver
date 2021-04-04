function subtitle(st) {
	// st[0] = text
	// st[1] = cooldown
	var subtitleBox = document.querySelector(".game .subtitle");
	subtitleBox.innerHTML = st[0];
	show(subtitleBox);
	setTimeout(function() {hide(subtitleBox)}, st[1])
}