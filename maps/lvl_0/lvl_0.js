// lvl size = 21x17.5
// tavern used as a lobby
// the upper-bar section is not used as an upper level
// because the player can't and doesn't need to access it

lvl_0 = [
	[
		"spawn",
		"empty",
		[1, 1],
		[0, 0]
	],
	// floor
	[
		"floor",
		"brick",
		[19, 17],
		[0, 3]
	],
	// horizontal wall sides
	[
		"hwall1_side",
		"darkbrick_side",
		[19, 1],
		[0, 11]
	], [
		"hwall2_side",
		"darkbrick_side",
		[9, 1],
		[-6, -5]
	], [
		"hwall3_side",
		"darkbrick_side",
		[9, 1],
		[6, -5]
	],
	// vertical walls
	[
		"vwall1",
		"darkbrick",
		[1, 15],
		[-10, 3.5]
	], [
		"vwall2",
		"darkbrick",
		[1, 15],
		[10, 3.5]
	],
	// bar
	[
		"bar",
		"wood",
		[19, 1],
		[0, 7.5]
	], [
		"bar_side",
		"table_side",
		[19, 1],
		[0, 7]
	],
	// decorations
	[
		"pnj",
		"pnj",
		[1, 1],
		[0, 8.5]
	], [
		"chest1",
		"chest",
		[1, 1],
		[-1, 10.5]
	], [
		"chest1_side",
		"chest_side",
		[1, 1],
		[-1, 10]
	], [
		"chest2",
		"chest",
		[1, 1],
		[-7, 10.5]
	], [
		"chest2_side",
		"chest_side",
		[1, 1],
		[-7, 10]
	], [
		"chest3",
		"chest",
		[1, 1],
		[5, 10.5]
	], [
		"chest3_side",
		"chest_side",
		[1, 1],
		[5, 10]
	], [
		"carpet1",
		"carpet",
		[3, 1],
		[-6, 6]
	], [
		"carpet2",
		"carpet",
		[5, 2],
		[0, 5.5]
	], [
		"carpet3",
		"carpet",
		[3, 1],
		[6, 6]
	], [
		"carpet4",
		"carpet",
		[3, 2],
		[0, -4.5]
	], [
		"table1_side",
		"table_side",
		[3, 1],
		[-6, -1]
	], [
		"table2_side",
		"table_side",
		[3, 1],
		[6, -1]
	],
	// no entities in this level
	"noentity"
]

lvl_0_upper = [
	// weapons
	[
		"weapons",
		"wood",
		[3, 1],
		[-8, -4]
	],
	// horizontal walls
	[
		"hwall1",
		"darkbrick",
		[21, 1],
		[0, 11.5]
	], [
		"hwall2",
		"darkbrick",
		[9, 1],
		[-6, -4.5]
	], [
		"hwall3",
		"darkbrick",
		[9, 1],
		[6, -4.5]
	],
	// decorations
	[
		"table1",
		"wood",
		[3, 4],
		[-6, 1]
	], [
		"table2",
		"wood",
		[3, 4],
		[6, 1]
	],
	// map borders
	[
		"map_border",
		"border_bottom",
		[3, 1],
		[0, -5]
	]
]