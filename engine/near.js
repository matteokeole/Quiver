function near(x, y, lvl) {
	switch (lvl) {
		case 0:
			// LEVEL 0 CUSTOM CASES
			if (x >= -1 && x <= 1 && y >= -6 && y <= -5) {
				if (!player.isCustomCaseNear && player.canLevelUp) {
					// a custom case is near
					player.isCustomCaseNear = true;
					change_level(lvl_1, lvl_1_upper) // entering level 1
				}
			} else player.isCustomCaseNear = false; // no custom cases near
			break;
		case 1:
			// LEVEL 1 ENEMIES
			// archer skeleton
			if (x > 12 && x < 14 && y >= 2 && y <= 3.5) {
				if (!player.isEnemyNear && !Backup.entity.Entity0.isDead && !Backup.entity.Entity0.hasFled) {
					// an enemy is near
					player.isEnemyNear = true;
					// reseting player direction
					player.direction.top = false;
					player.direction.bottom = false;
					player.direction.left = false;
					player.direction.right = false;
					player.movement.off();
					start_fight(Backup, Backup.entity.Entity0) // opening fight menu
				}
			}
			// guard skeleton
			else if (x > 21 && x < 23 && y >= 2 && y <= 3.5) {
				if (!player.isEnemyNear && !Backup.entity.Entity1.isDead && !Backup.entity.Entity1.hasFled) {
					// an enemy is near
					player.isEnemyNear = true;
					// reseting player direction
					player.direction.top = false;
					player.direction.bottom = false;
					player.direction.left = false;
					player.direction.right = false;
					player.movement.off();
					start_fight(Backup, Backup.entity.Entity1) // opening fight menu
				}
			}
			// goblin
			else if (x >= 17 && x <= 18 && y > 14 && y < 16) {
				if (!player.isEnemyNear && !Backup.entity.Entity2.isDead && !Backup.entity.Entity2.hasFled) {
					// an enemy is near
					player.isEnemyNear = true;
					// reseting player direction
					player.direction.top = false;
					player.direction.bottom = false;
					player.direction.left = false;
					player.direction.right = false;
					player.movement.off();
					start_fight(Backup, Backup.entity.Entity2) // opening fight menu
				}
			} else player.isEnemyNear = false; // no enemies near

			// LEVEL 1 CUSTOM CASES
			// map border
			if (x >= -2 && x <= -1 && y >= -1 && y <= 0.5) {
				if (!player.isCustomCaseNear) {
					// a custom case is near
					player.isCustomCaseNear = true;
					dialog(Dialog[7]) // border dialog
				}
			}
			// chest room
			else if ((x > 26 && x < 28 && y >= 2 && y <= 3.5) || (x > 26 && x < 28 && y >= 8 && y <= 9.5)) {
				if (!player.isCustomCaseNear && !player.hasVisitedChestRoom) {
					// a custom case is near
					player.isCustomCaseNear = true;
					player.hasVisitedChestRoom = true;
					dialog(Dialog[8]) // chest room dialog
				}
			}
			// chest
			else if (x >= 31.1 && x <= 32.9 && y >= 10.6 && y <= 11.6) {
				if (!player.isCustomCaseNear) {
					// a custom case is near
					player.isCustomCaseNear = true;
					dialog(Dialog[9]) // chest dialog
				}
			}
			// corridor monster
			else if (x >= 10 && x <= 11 && y > 9.5 && y < 11.5) {
				if (!player.isCustomCaseNear && !player.hasVisitedCorridor) {
					// a custom case is near
					player.isCustomCaseNear = true;
					player.hasVisitedCorridor = true;
					dialog(Dialog[10]) // corridor monster dialog
				}
			}
			// false exit
			else if (x >= 3 && x <= 4 && y >= 19 && y <= 21.5) {
				if (!player.isCustomCaseNear) {
					// a custom case is near
					player.isCustomCaseNear = true;
					dialog(Dialog[11]) // false exit dialog
				}
			}
			// exit
			else if (x >= 37 && x <= 38 && y >= 19 && y <= 21.5) {
				if (!player.isCustomCaseNear) {
					// a custom case is near
					player.isCustomCaseNear = true;
					change_level(lvl_2, lvl_2_upper) // entering level 2
				}
			} else player.isCustomCaseNear = false; // no custom cases near
			break;
		case 2:
			// LEVEL 2 ENEMIES
			// guard skeleton
			if (x >= -1 && x <= 1 && y > -9 && y < -7) {
				if (!player.isEnemyNear && !Backup.entity.Entity3.isDead && !Backup.entity.Entity3.hasFled) {
					// an enemy is near
					player.isEnemyNear = true;
					// reseting player direction
					player.direction.top = false;
					player.direction.bottom = false;
					player.direction.left = false;
					player.direction.right = false;
					player.movement.off();
					start_fight(Backup, Backup.entity.Entity3) // opening fight menu
				}
			}
			// goblin
			else if (x > 7 && x < 9 && y >= -14 && y <= -11.5) {
				if (!player.isEnemyNear && !Backup.entity.Entity4.isDead && !Backup.entity.Entity4.hasFled) {
					// an enemy is near
					player.isEnemyNear = true;
					// reseting player direction
					player.direction.top = false;
					player.direction.bottom = false;
					player.direction.left = false;
					player.direction.right = false;
					player.movement.off();
					start_fight(Backup, Backup.entity.Entity4) // opening fight menu
				}
			}
			// archer skeleton (top map)
			else if (x > 18 && x < 20 && y >= -14 && y <= -11.5) {
				if (!player.isEnemyNear && !Backup.entity.Entity5.isDead && !Backup.entity.Entity5.hasFled) {
					// an enemy is near
					player.isEnemyNear = true;
					// reseting player direction
					player.direction.top = false;
					player.direction.bottom = false;
					player.direction.left = false;
					player.direction.right = false;
					player.movement.off();
					start_fight(Backup, Backup.entity.Entity5) // opening fight menu
				}
			}
			// archer skeleton (bottom map)
			else if (x > 8 && x < 10 && y >= -19 && y <= -17.5) {
				if (!player.isEnemyNear && !Backup.entity.Entity6.isDead && !Backup.entity.Entity6.hasFled) {
					// an enemy is near
					player.isEnemyNear = true;
					// reseting player direction
					player.direction.top = false;
					player.direction.bottom = false;
					player.direction.left = false;
					player.direction.right = false;
					player.movement.off();
					start_fight(Backup, Backup.entity.Entity6) // opening fight menu
				}
			} else player.isEnemyNear = false; // no enemy near

			// LEVEL 2 CUSTOM CASES
			// corridor noise
			if (x > 1 && x < 3 && y >= -14 && y <= -11.5) {
				if (!player.isCustomCaseNear && !player.hasHeardNoise) {
					// a custom case is near
					player.isCustomCaseNear = true;
					player.hasHeardNoise = true;
					dialog(Dialog[13]) // corridor noise dialog
				}
			}
			// chest
			else if (x >= 13.1 && x <= 14.9 && y >= -5.4 && y <= -4.4) {
				if (!player.isCustomCaseNear) {
					// a custom case is near
					player.isCustomCaseNear = true;
					dialog(Dialog[14]) // chest dialog
				}
			}
			// map border 1
			else if (x >= -1 && x <= 1 && y >= 1 && y <= 2) {
				if (!player.isCustomCaseNear) {
					// a custom case is near
					player.isCustomCaseNear = true;
					dialog(Dialog[7]) // border 1 dialog
				}
			}
			// map border 2
			else if (x >= 6 && x <= 7 && y >= -24 && y <= -22.5) {
				if (!player.isCustomCaseNear) {
					// a custom case is near
					player.isCustomCaseNear = true;
					dialog(Dialog[15]) // border 2 dialog
				}
			}
			// diamond
			else if ((x > 26 && x <= 27 && y >= -13.5 && y <= -12.5) || (x > 26 && x <= 27 && y >= -13 && y <= -12) || (x >= 26 && x <= 27 && y > -13.5 && y < -12)) {
				if (!player.isCustomCaseNear && !player.hasTakenDiamond) {
					// a custom case is near
					player.isCustomCaseNear = true;
					player.hasTakenDiamond = true;
					end()
				}
			} else player.isCustomCaseNear = false; // no custom cases near
			break
	}
}