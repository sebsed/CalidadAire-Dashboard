//var i = 0;
var tiempo = 500000;

var percentage = Math.round((ppm*100)/max);

//Change this Value to set the percentage
let totalRot = ((percentage / 100) * 180 * Math.PI) / 180;

let rotation = 0;
let doAnim = true;
let canvas = null;
let ctx = null;
let text = document.querySelector(".text-speed");
canvas = document.getElementById("canvas");
ctx = canvas.getContext("2d");
setTimeout(requestAnimationFrame(animate), 1500);

//timeDelay();
//resolvePromise();

//async function resolvePromise() {
	//let sumPromise = new Promise(function (resolve, reject) {
				//setTimeout(function () {
				  // resolve(anim);
				//}, tiempo);
			 //});
			//let result = await sumPromise;
			//text.innerHTML = ppm + "ppm";
			//text.style.color = "white";
			//window.cancelAnimationFrame(animate);
//}	 

//tiempo del delay
//function timeDelay() {
	//if (percentage <= 20){
		//tiempo = 3000;
	//}
	//else if (percentage <= 40 && percentage > 20){
		//tiempo = 3500;
	//}
	//else if (percentage <= 60 && percentage > 40){
		//tiempo = 4000;
	//}
	//else if (percentage <= 80 && percentage > 60){
		//tiempo = 4500;
	//}
	//else if (percentage <= 100 && percentage > 80){
		//tiempo = 5000;
	//}
//}

function calcPointsCirc(cx, cy, rad, dashLength) {
  var n = rad / dashLength,
    alpha = (Math.PI * 2) / n,
    pointObj = {},
    points = [],
    i = -1;

  while (i < n) {
    var theta = alpha * i,
      theta2 = alpha * (i + 1);

    points.push({
      x: Math.cos(theta) * rad + cx,
      y: Math.sin(theta) * rad + cy,
      ex: Math.cos(theta2) * rad + cx,
      ey: Math.sin(theta2) * rad + cy
    });
    i += 2;
  }
  return points;
}
function animate() {
	i += 1;
  //Clearing animation on every iteration
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  const center = {
    x: 175,
    y: 175
  };

  //main arc
  ctx.beginPath();
  if (rotation <= 0.2 * Math.PI){
    ctx.strokeStyle = "#3cb043";
  }
  else if (rotation > 0.2 * Math.PI && rotation <= 0.4 * Math.PI) {
    ctx.strokeStyle = "#FFFF00	";
  }
  else if (rotation > 0.4 * Math.PI && rotation <= 0.6 * Math.PI) {
    ctx.strokeStyle = "#ffa500";
  }
  else if (rotation > 0.6 * Math.PI && rotation <= 0.8 * Math.PI) {
    ctx.strokeStyle = "#FF0000";
  }
  else {
    ctx.strokeStyle = "#800080";
  }
  ctx.lineWidth = "3";
  let radius = 174;
  ctx.arc(center.x, center.y, radius, Math.PI, Math.PI + rotation);
  ctx.stroke();

  //Red Arc
  //if (rotation <= 0.75 * Math.PI) {
    //ctx.beginPath();
    //ctx.strokeStyle = "#FF9421";
    //ctx.lineWidth = "3";
    //ctx.arc(center.x, center.y, radius, 1.75 * Math.PI, 0);
    //ctx.stroke();
  //}

  //functions to draw dotted lines
  const DrawDottedLine = (x1, y1, x2, y2, dotRadius, dotCount, dotColor) => {
    var dx = x2 - x1;
    var dy = y2 - y1;
    let slopeOfLine = dy / dx;
    var degOfLine =
      Math.atan(slopeOfLine) * (180 / Math.PI) > 0
        ? Math.atan(slopeOfLine) * (180 / Math.PI)
        : 180 + Math.atan(slopeOfLine) * (180 / Math.PI);
    var degOfNeedle = rotation * (180 / Math.PI);

    if (rotation <= 0.2 * Math.PI){
    dotColor = "#3cb043";
    }
    else if (rotation > 0.2 * Math.PI && rotation <= 0.4 * Math.PI) {
    dotColor = "#FFFF00";
    }
    else if (rotation > 0.4 * Math.PI && rotation <= 0.6 * Math.PI) {
      dotColor = "#ffa500";
    }
    else if (rotation > 0.6 * Math.PI && rotation <= 0.8 * Math.PI) {
      dotColor = "#FF0000";
    }
    else {
    dotColor = "#800080";
    }
    
    var spaceX = dx / (dotCount - 1);
    var spaceY = dy / (dotCount - 1);
    var newX = x1;
    var newY = y1;
    for (var i = 0; i < dotCount; i++) {
      dotRadius = dotRadius >= 0.75 ? dotRadius - i * (0.5 / 15) : dotRadius;
      drawDot(newX, newY, dotRadius, `${dotColor}${100 - (i + 1)}`);
      newX += spaceX;
      newY += spaceY;
    }
  };
  const drawDot = (x, y, dotRadius, dotColor) => {
    ctx.beginPath();
    ctx.arc(x, y, dotRadius, 0, 2 * Math.PI, false);
    ctx.fillStyle = dotColor;
    ctx.fill();
  };
  let firstDottedLineDots = calcPointsCirc(center.x, center.y, 165, 1);
  for (let k = 0; k < firstDottedLineDots.length; k++) {
    let x = firstDottedLineDots[k].x;
    let y = firstDottedLineDots[k].y;
    DrawDottedLine(x, y, 175, 175, 1.75, 30, "#35FFFF");
  }

  //dummy circle to hide the line connecting to center
  ctx.beginPath();
  ctx.arc(center.x, center.y, 80, 2 * Math.PI, 0);
  ctx.fillStyle = "black";
  ctx.fill();

  //Speedometer triangle
  var x = -75,
  y = 0;
  ctx.save();
  ctx.beginPath();
  ctx.translate(175, 175);
  ctx.rotate(rotation);
  ctx.moveTo(x, y);
  ctx.lineTo(x + 10, y - 10);
  ctx.lineTo(x + 10, y + 10);
  ctx.closePath();
  ctx.fillStyle = rotation >= 0.75 * Math.PI ? "#ffffff" : "#ffFFFF";
  ctx.fill();
  ctx.restore();
  var i = 0;
  if (rotation < totalRot) {
    rotation += (1 * Math.PI) / 180;
    if (rotation > totalRot) {
      rotation -= (1 * Math.PI) / 180;
	  i = 1;
    }
  }

  text.innerHTML = Math.round((rotation / Math.PI) * 100 *(max/100)) + 0 + unidad;
  //detener el contador despues de cierto tiempo
  if (i != 1){
	  requestAnimationFrame(animate);
	}
	else{
	window.cancelAnimationFrame(animate);
	text.innerHTML = ppm + unidad;
	text.style.color = "white";
	}
}