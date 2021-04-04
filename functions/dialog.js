function show_dialog(dialogBox, i) {
	show(dialogBox);
	setTimeout(function() {hide(dialogBox)}, i[2])
}

function dialog(i, nickname, persistent, f1, f2) {
	// i[0] = name
	// i[1] = text
	// i[2] = cooldown
	// i[3][0] = choice 1
	// i[3][1] = choice 2
	// nickname = player nickname
	// persistent = is persistent
	// f1 = choice 1 function
	// f2 = choice 2 function
	var dialogBox = document.querySelector(".game .dialog"),
		dialog = i[0] + " : <i>" + i[1] + "</i>";
	if (nickname !== undefined) {
		// this dialog must contain the player nickname
		// %s is the nickname place in the dialog
		// the nickname can be used several times in the same dialog
		dialog = dialog.split("%s").join(nickname)
	}
	dialogBox.querySelector(".dialogContent").innerHTML = dialog;
	(persistent === undefined) ? persistent = false : persistent; // setting persistent to false if undefined
	if (!persistent) show_dialog(dialogBox, i);
	else {
		dialogBox.querySelector("#dialogChoice1").innerHTML = "-> " + i[3][0];
		dialogBox.querySelector("#dialogChoice2").innerHTML = "-> " + i[3][1];
		show(dialogBox.querySelector("#dialogChoice1"));
		show(dialogBox.querySelector("#dialogChoice2"));
		show(dialogBox);
		dialogBox.querySelector("#dialogChoice1").onclick = function() {
			hide(dialogBox.querySelector("#dialogChoice1"));
			hide(dialogBox.querySelector("#dialogChoice2"));
			hide(dialogBox);
			f1()
		}
		dialogBox.querySelector("#dialogChoice2").onclick = function() {
			hide(dialogBox.querySelector("#dialogChoice1"));
			hide(dialogBox.querySelector("#dialogChoice2"));
			hide(dialogBox);
			f2()
		}
	}
}