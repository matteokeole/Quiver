<!DOCTYPE html>

<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="data:">
		<style>
			html, body {
				width: 100%;
				height: 100%;
				margin: 0;
				overflow: hidden
			}

			.textures-data {display: none}

			canvas {
				background-color: #222;
				image-rendering: crisp-edges
			}
		</style>
		<title>Quiver Beta</title>
	</head>

	<body>
		<div class="textures-data"></div>
		<canvas id="canvas"></canvas>
		<script>
			const generate = function(levelName) {
				// Canvas init
				const canvas = document.querySelector("#canvas"),
				ctx = canvas.getContext("2d");
				ctx.canvas.width  = window.innerWidth;
				ctx.canvas.height = window.innerHeight;
				// Request the JSON map file
				let path = `maps/${levelName}.json`;
				let mapRequest = new XMLHttpRequest();
				mapRequest.open("GET", path);
				mapRequest.responseType = "json";
				mapRequest.send();
				mapRequest.addEventListener("load", function() {
					// Request accepted, get file content and generate the map
					let r = this.response[levelName],
					map = r.map,
					uppermap = r.uppermap,
					entities = r.entity,
					next_level = r.next_level,
					scale_multiplier = 64;
					// Map parts
					for (let i = 0; i < map.length; i++) {
						let part = new Image(0, 0);
						part.src = `assets/textures/map/${map[i].texture}`;
						texturesData.appendChild(part);
						part.addEventListener("load", function() {
							if (map[i].size[0] > 1 || map[i].size[1] > 1) {
								// Create a pattern to drawing an image repetition
								let pattern = ctx.createPattern(part, "repeat");
								ctx.fillStyle = pattern;
								ctx.fillRect(
									((window.innerWidth / 2) + scale_multiplier * (map[i].origin[0] - map[i].size[0] / 2)),
									((window.innerHeight / 2) + scale_multiplier * (-map[i].origin[1] - map[i].size[1] / 2)),
									(scale_multiplier * map[i].size[0]),
									(scale_multiplier * map[i].size[1])
								)
							} else {
								console.warn("s")
								// The image can be drawn without pattern
								ctx.drawImage(
									part,
									(scale_multiplier * (map[i].origin[0] - map[i].size[0])),
									(scale_multiplier * -map[i].origin[1]),
									(scale_multiplier * map[i].size[0]),
									(scale_multiplier * map[i].size[1])
								)
							}
						})
					}
					// Uppermap parts
					for (let i = 0; i < uppermap.length; i++) {
						let part = new Image(0, 0);
						part.src = `assets/textures/map/${uppermap[i].texture}`;
						texturesData.appendChild(part);
						part.addEventListener("load", function() {
							if (uppermap[i].size[0] > 1 || uppermap[i].size[1] > 1) {
								// Create a pattern to drawing an image repetition
								let pattern = ctx.createPattern(part, "repeat");
								ctx.fillStyle = pattern;
								ctx.fillRect(
									((window.innerWidth / 2) + scale_multiplier * (uppermap[i].origin[0] - uppermap[i].size[0] / 2)),
									((window.innerHeight / 2) + scale_multiplier * (-uppermap[i].origin[1] - uppermap[i].size[1] / 2)),
									(scale_multiplier * uppermap[i].size[0]),
									(scale_multiplier * uppermap[i].size[1])
								)
							} else {
								// The image can be drawn without pattern
								ctx.drawImage(
									part,
									(scale_multiplier * (uppermap[i].origin[0] - uppermap[i].size[0])),
									(scale_multiplier * -uppermap[i].origin[1]),
									(scale_multiplier * uppermap[i].size[0]),
									(scale_multiplier * uppermap[i].size[1])
								)
							}
						})
					}
					/*for (i = 0; i < uppermap.length; i++) {
						part = document.createElement("div");
						part.className = `part ${uppermap[i].part}`;
						part.style.width = `${scale_multiplier * uppermap[i].size[0]}px`;
						part.style.height = `${scale_multiplier * uppermap[i].size[1]}px`;
						part.style.transform = `translateX(${scale_multiplier * uppermap[i].origin[0]}px) translateY(${(scale_multiplier * -uppermap[i].origin[1])}px)`;
						part.style.backgroundImage = `url(assets/textures/map/${uppermap[i].texture})`;
						Map.uppermap.append(part)
					}*/
					// Entities
					/*for (i = 0; i < entities.length; i++) {
						part = document.createElement("div");
						part.className = `entity ${entities[i].entity}`;
						part.style.width = `${scale_multiplier}px`;
						part.style.height = `${scale_multiplier}px`;
						part.style.transform = `translateX(${scale_multiplier * entities[i].origin[0]}px) translateY(${(scale_multiplier * -entities[i].origin[1])}px) rotateY(${entities[i].orientation === "right" ? 0 : 180}deg)`;
						part.style.backgroundImage = `url(assets/textures/entity/${entities[i].type}.png)`;
						if (backup.entity[entities[i].entity.substr(entities[i].entity.length - 1)].health !== 0) Map.entities.append(part)
					}*/
				})
				console.info(`Generated ${path}.`)
			},
			texturesData = document.querySelector(".textures-data");
			window.addEventListener("load", function() {
				generate("lobby");
			})
		</script>
	</body>

</html>