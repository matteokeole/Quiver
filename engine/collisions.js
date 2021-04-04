function collide(x, y, canMove, lvl) {
	// else can't be used cause of a bug where the player can move even when the pause menu is opened

	switch (lvl) {
		case 0:
			// LEVEL 0 COLLISIONS
			// top
			if (x >= -9 && x <= 9 && y >= 6.5 && y <= 7.5) canMove.top = false; // hwall1
			else if (x > -8 && x < -4 && y >= -1.5 && y <= -0.5) canMove.top = false; // table1
			else if (x > 4 && x < 8 && y >= -1.5 && y <= -0.5) canMove.top = false; // table2
			else canMove.top = true;
			// bottom
			if (x >= -9 && x < -1 && y >= -5 && y <= -4) canMove.bottom = false; // hwall2
			else if (x >= -9 && x < -6 && y >= -4.5 && y <= -3.5) canMove.bottom = false; // weapons
			else if (x > 1 && x <= 9 && y >= -5 && y <= -4) canMove.bottom = false; // hwall3
			else if (x >= -1 && x <= 1 && y >= -6 && y <= -5) canMove.bottom = false; // carpet4
			else if (x > -8 && x < -4 && y <= 3 && y >= 2) canMove.bottom = false; // table1
			else if (x > 4 && x < 8 && y <= 3 && y >= 2) canMove.bottom = false; // table2
			else canMove.bottom = true;
			// left
			if (x >= -11 && x <= -9 && y >= -4 && y <= 6.5) canMove.left = false; // vwall1
			else if (x >= -2 && x <= -1 && y > -5.5 && y < -4) canMove.left = false; // hwall2
			else if (x >= -7 && x <= -6 && y >= -4.5 && y < -3.5) canMove.left = false; // weapons
			else if (x >= -5 && x <= -4 && y > -1.5 && y < 3) canMove.left = false; // table1
			else if (x >= 7 && x <= 8 && y > -1.5 && y < 3) canMove.left = false; // table2
			else canMove.left = true;
			// right
			if (x >= 9 && x <= 11 && y >= -4 && y <= 6.5) canMove.right = false; // vwall2
			else if (x >= 1 && x <= 2 && y > -5.5 && y < -4) canMove.right = false; // hwall3
			else if (x >= -8 && x <= -7 && y > -1.5 && y < 3) canMove.right = false; // table1
			else if (x >= 4 && x <= 5 && y > -1.5 && y < 3) canMove.right = false; // table2
			else canMove.right = true;
			break;
		case 1:
			// LEVEL 1 COLLISIONS
			// top
			if (x >= -1 && x < 10 && y >= 0.5 && y <= 1.5) canMove.top = false; // hwall1
			else if (x >= 10 && x < 15 && y >= 3.5 && y <= 4.5) canMove.top = false; // hwall3
			else if (x > 16 && x < 24 && y >= 3.5 && y <= 4.5) canMove.top = false; // hwall4
			else if (x >= 31 && x <= 35 && y >= 11.5 && y <= 12.5) canMove.top = false; // hwall9
			else if (x > 31.1 && x < 32.9 && y >= 10.6 && y <= 11.4) canMove.top = false; // chest
			else if (x >= 31 && x < 34 && y >= 4.5 && y <= 5.5) canMove.top = false; // table
			else if (x >= 24 && x < 31 && y >= 9.5 && y <= 10.5) canMove.top = false; // hwall10
			else if (x > 25 && x < 31 && y >= 3.5 && y <= 4.5) canMove.top = false; // hwall7
			else if (x > 11 && x < 17 && y >= 9.5 && y <= 10.5) canMove.top = false; // hwall13
			else if (x >= 4 && x <= 37 && y >= 21.5 && y <= 22.5) canMove.top = false; // hwall17
			else canMove.top = true;
			// bottom
			if (x >= -1 && x <= 11 && y >= -2 && y <= -1) canMove.bottom = false; // hwall2
			else if (x > 11 && x < 31 && y >= 1 && y <= 2) canMove.bottom = false; // hwall5
			else if (x >= 31 && x <= 35 && y >= 0 && y <= 1) canMove.bottom = false; // hwall6
			else if (x >= 31 && x < 34 && y >= 6 && y <= 7) canMove.bottom = false; // table
			else if (x > 25 && x < 31 && y >= 7 && y <= 8) canMove.bottom = false; // hwall8
			else if (x >= 10 && x < 15 && y >= 7 && y <= 8) canMove.bottom = false; // hwall11
			else if (x > 16 && x <= 18 && y >= 7 && y <= 8) canMove.bottom = false; // hwall12
			else if (x > 11 && x < 17 && y >= 18 && y <= 19) canMove.bottom = false; // hwall14
			else if (x >= 4 && x < 10 && y >= 18 && y <= 19) canMove.bottom = false; // hwall15
			else if (x > 18 && x <= 37 && y >= 18 && y <= 19) canMove.bottom = false; // hwall16
			else canMove.bottom = true;
			// left
			if (x >= -2 && x <= -1 && y >= -1 && y <= 0.5) canMove.left = false; // map_border1
			else if (x >= 9 && x <= 10 && y > 0.5 && y <= 3.5) canMove.left = false; // vwall1
			else if (x >= 30 && x <= 31 && y >= 1 && y < 2) canMove.left = false; // hwall5
			else if (x >= 31.9 && x <= 32.9 && y > 10.6 && y <= 11.5) canMove.left = false; // chest
			else if (x >= 33 && x <= 34 && y > 4.5 && y < 7) canMove.left = false; // table
			else if (x >= 30 && x <= 31 && y > 9.5 && y <= 11.5) canMove.left = false; // vwall6
			else if (x >= 30 && x <= 31 && y > 3.5 && y < 8) canMove.left = false; // vwall4
			else if (x >= 23 && x <= 24 && y > 3.5 && y <= 9.5) canMove.left = false; // vwall7
			else if (x >= 14 && x <= 15 && y > 3.5 && y < 8) canMove.left = false; // vwall8
			else if (x >= 9 && x <= 10 && y >= 8 && y < 19) canMove.left = false; // vwall10
			else if (x >= 16 && x <= 17 && y > 9.5 && y < 19) canMove.left = false; // vwall13
			else if (x >= 3 && x <= 4 && y >= 19 && y <= 21.5) canMove.left = false; // map_border2
			else canMove.left = true;
			// right
			if (x >= 11 && x <= 12 && y >= -1 && y < 2) canMove.right = false; // vwall2
			else if (x >= 35 && x <= 36 && y >= 1 && y <= 11.5) canMove.right = false; // vwall5
			else if (x >= 31.1 && x <= 32.1 && y > 10.6 && y <= 11.5) canMove.right = false; // chest
			else if (x >= 25 && x <= 26 && y > 3.5 && y < 8) canMove.right = false; // vwall3
			else if (x >= 16 && x <= 17 && y > 3.5 && y < 8) canMove.right = false; // vwall9
			else if (x >= 18 && x <= 19 && y >= 8 && y < 19) canMove.right = false; // vwall11
			else if (x >= 11 && x <= 12 && y > 9.5 && y < 19) canMove.right = false; // vwall12
			else if (x >= 37 && x <= 38 && y >= 19 && y <= 21.5) canMove.right = false; // map_border3
			else canMove.right = true;
			break;
		case 2:
			// LEVEL 2 COLLISIONS
			// top
			if (x >= -1 && x <= 1 && y >= 1 && y <= 2) canMove.top = false; // map_border1
			else if (x > 1 && x < 11 && y >= -11.5 && y <= -10.5) canMove.top = false; // hwall1
			else if (x > 12 && x <= 27 && y >= -11.5 && y <= -10.5) canMove.top = false; // hwall2
			else if (x > 20 && x < 22 && y >= -12.5 && y <= -11.5) canMove.top = false; // hwall4
			else if (x > 26 && x <= 27 && y >= -13.5 && y <= -12.5) canMove.top = false; // table2
			else if (x >= 7 && x <= 16 && y >= -4.5 && y <= -3.5) canMove.top = false; // hwall6
			else if (x > 7 && x < 11 && y >= -7.5 && y <= -6.5) canMove.top = false; // table1
			else if (x > 13.1 && x < 14.9 && y >= -5.4 && y <= -4.4) canMove.top = false; // chest
			else if (x > 1 && x <= 19 && y >= -17.5 && y <= -16.5) canMove.top = false; // hwall9
			else if (x >= 7 && x < 11 && y >= -22.5 && y <= -21.5) canMove.top = false; // hwall12
			else canMove.top = true;
			// bottom
			if (x > 1 && x <= 27 && y >= -15 && y <= -14) canMove.bottom = false; // hwall3
			else if (x > 20 && x < 22 && y >= -14 && y <= -13) canMove.bottom = false; // hwall5
			else if (x > 26 && x <= 27 && y >= -13 && y <= -12) canMove.bottom = false; // table2
			else if (x >= 7 && x < 11 && y >= -9 && y <= -8) canMove.bottom = false; // hwall7
			else if (x > 12 && x <= 16 && y >= -9 && y <= -8) canMove.bottom = false; // hwall8
			else if (x > 7 && x < 11 && y >= -6 && y <= -5) canMove.bottom = false; // table1
			else if (x >= -1 && x < 11 && y >= -20 && y <= -19) canMove.bottom = false; // hwall10
			else if (x > 12 && x <= 19 && y >= -20 && y <= -19) canMove.bottom = false; // hwall11
			else if (x >= 7 && x <= 12 && y >= -25 && y <= -24) canMove.bottom = false; // hwall13
			else canMove.bottom = true;
			// left
			if (x >= -2 && x <= -1 && y >= -19 && y <= 1) canMove.left = false; // vwall1
			else if (x >= 21 && x <= 22 && y > -12.5 && y <= -11.5) canMove.left = false; // hwall4
			else if (x >= 21 && x <= 22 && y >= -14 && y < -13) canMove.left = false; // hwall5
			else if (x >= 10 && x <= 11 && y > -11.5 && y < -8) canMove.left = false; // vwall7
			else if (x >= 6 && x <= 7 && y >= -8 && y <= -4.5) canMove.left = false; // vwall5
			else if (x >= 10 && x <= 11 && y > -7.5 && y < -5) canMove.left = false; // table1
			else if (x >= 13.9 && x <= 14.9 && y > -5.4 && y <= -4.5) canMove.left = false; // chest
			else if (x >= 10 && x <= 11 && y > -22.5 && y < -19) canMove.left = false; // vwall10
			else if (x >= 6 && x <= 7 && y >= -24 && y <= -22.5) canMove.left = false; // map_border3
			else canMove.left = true;
			// right
			if (x >= 1 && x <= 2 && y > -11.5 && y <= 1) canMove.right = false; // vwall2
			else if (x >= 1 && x <= 2 && y > -17.5 && y < -14) canMove.right = false; // vwall3
			else if (x >= 27 && x <= 28 && y >= -14 && y <= -11.5) canMove.right = false; // vwall4
			else if (x >= 20 && x <= 21 && y > -12.5 && y <= -11.5) canMove.right = false; // hwall4
			else if (x >= 20 && x <= 21 && y >= -14 && y < -13) canMove.right = false; // hwall5
			else if (x >= 26 && x <= 27 && y > -13.5 && y < -12) canMove.right = false; // table2
			else if (x >= 12 && x <= 13 && y > -11.5 && y < -8) canMove.right = false; // vwall8
			else if (x >= 16 && x <= 17 && y >= -8 && y <= -4.5) canMove.right = false; // vwall6
			else if (x >= 7 && x <= 8 && y > -7.5 && y < -5) canMove.right = false; // table1
			else if (x >= 13.1 && x <= 14.1 && y > -5.4 && y <= -4.5) canMove.right = false; // chest
			else if (x >= 19 && x <= 20 && y >= -19 && y <= -17.5) canMove.right = false; // vwall9
			else if (x >= 12 && x <= 13 && y >= -24 && y < -19) canMove.right = false; // vwall11
			else canMove.right = true;
			break
	}
}