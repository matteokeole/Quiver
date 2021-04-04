function class_info(e) {
	var info = document.querySelector(".class_info"),
		info_span = info.querySelector("span"),
		mage_info = "",
		rogue_info = "",
		paladin_info = "";

	with (Entity) {
		mage_info =
		"--- " + Mage.name + " ---<br><br><br>" +
		"Sante : " + Mage.health + "<br>" +
		"Mana : " + Mage.mp + "<br>" +
		"Resistance : " + Mage.resistance + "<br><br>" +
		"Attaque 1 : \"" + Mage.attack1.name + "\"<br>" +
		"Degats : " + Mage.attack1.damage + "<br>" +
		"Cout en mana : " + Mage.attack1.cost + "<br><br>" +
		"Attaque 2 : \"" + Mage.attack2.name + "\"<br>" +
		"Degats : " + Mage.attack2.damage + "<br>" +
		"Cout en mana : " + Mage.attack2.cost + "<br><br>" +
		"Ultime : \"" + Mage.ult.name + "\"<br>" +
		"Degats : " + Mage.ult.damage + " a tous les ennemis du niveau<br>" +
		"Cout en mana : " + Mage.ult.cost + "<br><br>";
		rogue_info =
		"--- " + Rogue.name + " ---<br><br><br>" +
		"Sante : " + Rogue.health + "<br>" +
		"Mana : " + Rogue.mp + "<br>" +
		"Resistance : " + Rogue.resistance + "<br><br>" +
		"Attaque 1 : \"" + Rogue.attack1.name + "\"<br>" +
		"Degats : " + Rogue.attack1.damage + "<br>" +
		"Cout en mana : " + Rogue.attack1.cost + "<br><br>" +
		"Attaque 2 : \"" + Rogue.attack2.name + "\"<br>" +
		"Multiplie vos degats par " + Rogue.attack2.damageMult + " pour le tour suivant<br>" +
		"Cout en mana : " + Rogue.attack2.cost + "<br><br>" +
		"Ultime : \"" + Rogue.ult.name + "\"<br>" +
		"Votre ennemi a " + Rogue.ult.scareMult + " fois plus de chance de s'enfuir au tour suivant<br>" +
		"Cout en mana : " + Rogue.ult.cost + "<br><br>";
		paladin_info =
		"--- " + Paladin.name + " ---<br><br><br>" +
		"Sante : " + Paladin.health + "<br>" +
		"Mana : " + Paladin.mp + "<br>" +
		"Resistance : " + Paladin.resistance + "<br><br>" +
		"Attaque 1 : \"" + Paladin.attack1.name + "\"<br>" +
		"Degats : " + Paladin.attack1.damage + "<br>" +
		"Cout en mana : " + Paladin.attack1.cost + "<br><br>" +
		"Attaque 2 : \"" + Paladin.attack2.name + "\"<br>" +
		"Vous bloquez la prochaine attaque de votre ennemi.<br>" +
		"Cout en mana : " + Paladin.attack2.cost + "<br><br>" +
		"Ultime : \"" + Paladin.ult.name + "\"<br>" +
		"Vous retrouvez " + Paladin.ult.healAmount + " points de sante<br>" +
		"Cout en mana : " + Paladin.ult.cost + "<br><br>"
	}
	show(info);
	switch (e.innerHTML) {
		case "Mage":
			info_span.innerHTML = mage_info;
			break;
		case "Roublard":
			info_span.innerHTML = rogue_info;
			break;
		case "Paladin":
			info_span.innerHTML = paladin_info;
			break
	}
	info.querySelector(".b2").onclick = function() {hide(info)} // closing
}