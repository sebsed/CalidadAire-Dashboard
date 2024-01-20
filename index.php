<?php

include('db/database.php');

//variables de particulas
//obtener ultimo registro del query
$sql = "SELECT * FROM report Order By Timestamp Desc";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
//var_dump ($row);

$valorPM25 = bcdiv($row['PM25'], 1, 2);;
//echo bcdiv($row['PM25'], 1, 2);
$valorPM10 = bcdiv($row['PM10'], 1, 2);;
$valorMonoxido = bcdiv($row['Carbon_Mono'] /450, 1, 2);;
$valorOzono = bcdiv($row['Ozone'] / 467, 1, 2);;
$valorHumedad = bcdiv($row['Humidity'], 1, 2);;
$valorTemperatura = bcdiv($row['Temperature'], 1, 2);;

///valor mas alto de las variables
$maximoPM25 = 147; 
$maximoPM10 = 235; 
$maximoMonoxido = 15.5; 
$maximoOzono = 0.114; 
$maximoHumedad = 100; 
$maximoTemperatura = 60; 

//variables para semaforo
$maximoVelocimetro = 0;
$busqueda = "";
$titulo = "";
	
//saber que boton se presiono
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Something posted
	
	if (isset($_POST['boton1'])) {
		establecerVelocimetro("PM 2.5", $maximoPM25, $valorPM25);
	}
	else if (isset($_POST['boton2'])) {
		establecerVelocimetro("PM 10", $maximoPM10, $valorPM10);
	} 
	else if (isset($_POST['boton3'])) {
		establecerVelocimetro("Monoxido de carbono", $maximoMonoxido, $valorMonoxido);
	} 
	else if (isset($_POST['boton4'])) {
		establecerVelocimetro("Ozono", $maximoOzono, $valorOzono);
	} 
	else if (isset($_POST['boton5'])) {
		establecerVelocimetro("Humedad", $maximoHumedad, $valorHumedad);
	} 
	else if (isset($_POST['boton6'])) {
		establecerVelocimetro("Temperatura", $maximoTemperatura, $valorTemperatura);
	} 
}
else {
	//primera vez en la pagina
	//hacer calculo de medicion mas alta
	
	//normalizar valores para compararlos
	$normalizarPM25 = $valorPM25 / $maximoPM25;
	$normalizarPM10 = $valorPM10 / $maximoPM10;
	$normalizarMonoxido = $valorMonoxido / $maximoMonoxido;
	$normalizarOzono = $valorOzono / $maximoOzono;
	$normalizarHumedad = $valorHumedad / $maximoHumedad;
	$normalizarTemperatura = $valorTemperatura / $maximoTemperatura;
	
	//buscar valor mas grande
	$array = compact('normalizarPM25', 'normalizarPM10', 'normalizarMonoxido', 'normalizarOzono', 'normalizarHumedad', 'normalizarTemperatura');
	arsort($array);
	$masGrande = key($array);
	
	//asignar datos al velocimetro
	if ($masGrande == "normalizarPM25") {
		establecerVelocimetro("PM 2.5", $maximoPM25, $valorPM25);
	}
	else if ($masGrande == "normalizarPM10") {
		establecerVelocimetro("PM 10", $maximoPM10, $valorPM10);
	} 
	else if ($masGrande == "normalizarMonoxido") {
		establecerVelocimetro("Monoxido de carbono", $maximoMonoxido, $valorMonoxido);
	} 
	else if ($masGrande == "normalizarOzono") {
		establecerVelocimetro("Ozono", $maximoOzono, $valorOzono);
	} 
	else if ($masGrande == "normalizarHumedad") {
		establecerVelocimetro("Humedad", $maximoHumedad, $valorHumedad);
	} 
	else if ($masGrande == "normalizarTemperatura") {
		establecerVelocimetro("Temperatura", $maximoTemperatura, $valorTemperatura);
	}
}

function establecerVelocimetro($titulo1, $maximo1, $valorx){
	//variables conserven al salir de la funcion
	global $maximoVelocimetro, $titulo, $num;
	$titulo = $titulo1;
	$maximoVelocimetro = $maximo1;	
	$num = $valorx;
}

//obtener dato para velocimetro

//sacar hora actual
$fechaReciente = $row['Timestamp'];
$time = strtotime($fechaReciente);
$fechMedicion = date('Y-m-d', $time);
$horaMedicion = date('H:i', $time);

?>

<?php
//color del semaforo
//retorna contaminante, que es titulo del semafoto
//retorna valor semaforo que es color del semaforo
include('calculoValorSemaforo.php');

?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Calidad del aire</title>
		<link rel="stylesheet" href="css/estilos1.css">
		<link rel="icon" type="image/x-icon" href="https://cdn-icons-png.flaticon.com/512/2534/2534233.png">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
	</head>
	<header>
		<?php include('header.html'); ?>
	</header>
<body>
	<div class = "container-fluid">
		
		<! Primer fila –>
		<div class = "row mt-4 text-center">
			
			<! Barra de progreso –>
			<div class = "col-lg-6">
				<div class = "row">
					<div class = "col-lg-6 ">
						<h3> Medicion de: <?= $titulo ?></h3>
					</div>
					<div class = "col-lg-6">
						<h6> Hora medición: <?= $fechaReciente ?></h6>
					</div>	
				</div>
				<canvas id="canvas" height="200" width="350" class="m-auto">
				</canvas>
				
				<p class="text-speed"></p>
				<div class="card borde-semaforo">
					<div class="card-body">
						<h5 class="card-title" id="tituloVelocimetro">¿Que es?</h5>
						<p class="card-text text-start" id="descParticulas1"></p>
						<p class="card-text text-start" id="descParticulas2"></p>
					</div>
				</div>
				
				<! Botones de la barra de progreso –>
				<form action="" method="POST">
					<table class="table table-striped">
					  <thead>
						<tr>
						  <th scope="col">Presiona el boton para ver la escala</th>
						  <th scope="col">Medicion actual:</th>
						</tr>
					  </thead>
					  <tbody>
						<tr>
						  <th scope="row">
							<button class="btn btn-primary botonesTabla" type="submit" name="boton1">PM 2.5</button></th>
							<td><?php echo $valorPM25 . " ppm" ?></td>
						</tr>
						<tr>
						  <th scope="row"><button class="btn btn-primary botonesTabla" type="submit" name="boton2">PM 10</button></th>
						  <td><?php echo$valorPM10 . " ppm"?></td>
						</tr>
						<tr>
						  <th scope="row"><button class="btn btn-primary botonesTabla" type="submit" name="boton3">Monoxido de carbono</button></th>
						  <td><?php echo $valorMonoxido . " ppm"?></td>
						</tr>
						<tr>
						  <th scope="row"><button class="btn btn-primary botonesTabla" type="submit" name="boton4">Ozono</button></th>
						  <td><?php echo $valorOzono . " ppm"?></td>
						</tr>
						<tr>
						  <th scope="row"><button class="btn btn-primary botonesTabla" type="submit" name="boton5">Humedad</button></th>
						  <td><?php echo $valorHumedad . " %"?></td>
						</tr>
						<tr>
						  <th scope="row"><button class="btn btn-primary botonesTabla" type="submit" name="boton6">Temperatura</button></th>
						  <td><?php echo $valorTemperatura . " C"?></td>
						</tr>
					  </tbody>
					</table>
				</form>
			</div>		
			
			<! Columna de la derecha –>
			<div class = "col-lg-6">
			
				<! Primera fila, imagen de google maps –>
				<div class = "row mt-2">
					
					<! semaforo –>
					<div class = "col-md-3 col-lg-4 col-xl-3 col-4">
						<div class="trafficLight">
							<span class="colorful"></span>
						</div>
					</div>	
					
					<! Informacion del semaforo –>
					<div class = "col-md-9 col-lg-8 col-xl-9 col-8">
						<div class="card borde-semaforo">
						  <div class="card-body">
							<h4 class="card-title">Riesgo actual: <?= $contaminante ?> </h4>
							<h5 class="card-subtitle mb-2" id="tituloIndice"></h5>
							<p class="card-text" id="descIndice1"></p>
							<p class="card-text" id="descIndice2"></p>
						  </div>
						</div>
					</div>
				</div>
				
				<! Segunda fila, info de los sensores –>
				
				<div class = "row mt-2">
					<div class = "col-12">
						<div class="accordion" id="accordionPanelsStayOpenExample">
							<div class="accordion-item">
								<h2 class="accordion-header" id="panelsStayOpen-headingOne">
									<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
										Mediciones del Sensor de Paseo de la Reforma
									</button>
								</h2>
								<div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
									<div class="accordion-body">
										<div class = "row">
											<div class = "col 6">
												Fecha de medicion
											</div>
											<div class = "col 6">
												<? $fechaReciente ?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												PM 2.php echo5
											</div>
											<div class = "col 6">
												<?php echo $valorPM25 . " ppm"?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												PM 10
											</div>
											<div class = "col 6">
												<?php echo $valorPM10 . " ppm"?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												Monoxido de carbono
											</div>
											<div class = "col 6">
												<?php echo $valorMonoxido . " ppm"?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												Nitrogeno
											</div>
											<div class = "col 6">
												<?php echo $valorOzono . " ppm"?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												Humedad
											</div>
											<div class = "col 6">
												<?php echo $valorHumedad . " %"?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												Temperatura
											</div>
											<div class = "col 6">
												<?php echo $valorHumedad . " C"?> 
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="panelsStayOpen-headingTwo">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
									Mediciones del Sensor de Vasco de Quiroga
								</button>
								</h2>
								<div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
									<div class="accordion-body">
									<div class = "row">
											<div class = "col 6">
												Fecha de medicion
											</div>
											<div class = "col 6">
												<? $fechaReciente ?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												PM 2.php echo5
											</div>
											<div class = "col 6">
												<?php echo $valorPM25 . " ppm"?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												PM 10
											</div>
											<div class = "col 6">
												<?php echo $valorPM10 . " ppm"?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												Monoxido de carbono
											</div>
											<div class = "col 6">
												<?php echo $valorMonoxido . " ppm"?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												Nitrogeno
											</div>
											<div class = "col 6">
												<?php echo $valorOzono . " ppm"?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												Humedad
											</div>
											<div class = "col 6">
												<?php echo $valorHumedad . " %"?> 
											</div>
										</div>
										<div class = "row">
											<div class = "col 6">
												Temperatura
											</div>
											<div class = "col 6">
												<?php echo $valorHumedad . " C"?> 
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="panelsStayOpen-headingThree">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
										Mediciones del Sensor 3
									</button>
								</h2>
								<div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
									<div class="accordion-body">
										<p>Proximamente en funcionamiento</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<! Tercera fila, imagen google maps –>
				<div class = "row">
					<div class = "col-12">
						<iframe src="https://www.google.com/maps/d/u/0/embed?mid=19e33Gu7s5kpvxNoMVP6Ul2tPg2iKnnc&ehbc=2E312F&noprof=1" width="100%" height="450"></iframe>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>	
		
<! Esto siempre va al final –>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer">
</script>

<! colorear el semaforo y poner datos de indice–>
<script>
	let valorSemaforo = "<?= $valorSemaforo ?>";
	const descIndice1 = document.getElementById("descIndice1");
	const descIndice2 = document.getElementById("descIndice2");
	const tituloIndice = document.getElementById("tituloIndice");
	console.log(valorSemaforo);
	
	//verde
	if (valorSemaforo === "bueno"){
		document.documentElement.style.setProperty('--my-start-color', '#9ACA3C');
		document.documentElement.style.setProperty('--my-main-color', '#d6e9b1');
		tituloIndice.innerHTML = "Riesgo bajo";
		descIndice1.innerHTML = "Existe poco o ningún riesgo para la salud";
		descIndice2.innerHTML = "Se puede realizar cualquier actividad al aire libre";
	}
	//amarillo
	else if (valorSemaforo === "aceptable"){
		document.documentElement.style.setProperty('--my-start-color', '#F7EC0F');
		document.documentElement.style.setProperty('--my-main-color', '#fbf79f');
		tituloIndice.innerHTML = "Riesgo moderado";
		descIndice1.innerHTML = "Los grupos susceptibles pueden presentar síntomas en la salud";
		descIndice2.innerHTML = "Las personas que son extremadamente susceptibles a la contaminación deben considerar limitar la exposición al aire libre";
	}
	//naranja
	else if (valorSemaforo === "malo"){
		document.documentElement.style.setProperty('--my-start-color', '#F8991D'); 
		document.documentElement.style.setProperty('--my-main-color', '#fcd6a4');
		tituloIndice.innerHTML = "Riesgo alto";
		descIndice1.innerHTML = "Los grupos susceptibles presentan efectos en la salud";
		descIndice2.innerHTML = "Los niños, adultos mayores, personas con en la salud enfermedades respiratorias y cardiovasculares, así como personas que realizan actividad física al aire libre deben limitar la exposición al aire libre";
	}
	//rojo
	else if (valorSemaforo === "muyMalo"){
		document.documentElement.style.setProperty('--my-start-color', '#ED2124'); 
		document.documentElement.style.setProperty('--my-main-color', '#f7a6a7');
		tituloIndice.innerHTML = "Riesgo muy alto";
		descIndice1.innerHTML = "Todos pueden presentar efectos en la salud; quienes pertenecen a grupos susceptibles experimentan efectos graves";
		descIndice2.innerHTML = "Los niños, adultos mayores, personas que realizan actividad física intensa o con enfermedades respiratorias y cardiovasculares, deben evitar la exposicion al aire libre y el resto de la poblacion debe limitar la exposición al aire libre";
	}
	//morado
	else if (valorSemaforo === "extremo"){
		document.documentElement.style.setProperty('--my-start-color', '#7D287D');
		document.documentElement.style.setProperty('--my-main-color', '#cba9cb');
		tituloIndice.innerHTML = "Riesgo extremadamente alto";
		descIndice1.innerHTML = "Toda la población tiene probabilidades de experimentar efetos graves en la salud";
		descIndice2.innerHTML = "Toda la población debe evitar la exposicion al aire libre";
	}
	//guinda, descontinuado
	else if (valorSemaforo === "error" ){
		document.documentElement.style.setProperty('--my-start-color', '#7E0023');
		document.documentElement.style.setProperty('--my-main-color', '#cb99a7');
		tituloIndice.innerHTML = "PELIGRO";
		descIndice1.innerHTML = "Toda la población experimenta efectos graves en la salud";
		descIndice2.innerHTML = "Suspención de actividades al aire libre";
	}
</script>

<script type="text/javascript">
	//variables de texto
	let particula = "<?= $titulo ?>";
	const descParticulas1 = document.getElementById("descParticulas1");
	const descParticulas2 = document.getElementById("descParticulas2");
	const tituloVelocimetro = document.getElementById("tituloVelocimetro");

	//descripcion de que es cada particula/parametro
	if (particula == "PM 2.5"){
		tituloVelocimetro.innerHTML = "La NOM-025-SSA1-2021 establece el valores límites de:<br>41 µg/m3 para el promedio 24 horas y 10 µg/m3 para el promedio anual";
		descParticulas1.innerHTML = "Son partículas muy pequeñas en el aire que tiene un diámetro de 2.5 micrómetros o menos (menor que el grosor de un cabello humano). Es una mezcla que puede incluir sustancias químicas orgánicas, polvo, hollín y metales.";
		descParticulas2.innerHTML = "Puede provenir de los automóviles, camiones, fábricas, quema de madera y otras actividades";
	}
	else if (particula == "PM 10"){
		tituloVelocimetro.innerHTML = "La NOM-025-SSA1-2021 establece el valores límites de:<br>70 µg/m3 para el promedio 24 horas y 36 µg/m3 para el promedio anual.";
		descParticulas1.innerHTML = "Son aquellas partículas sólidas o líquidas de polvo, cenizas, hollín, partículas metálicas, cemento o polen, dispersas en la atmósfera, y cuyo diámetro varía entre 2,5 y 10 µm";
		descParticulas2.innerHTML = "Provocado por la quema de combustibles fosibles en industria y automoviles, levantamiento de polvo del suelo, ceniza volcania e incendios forestales";
	}
	else if (particula == "Monoxido de carbono"){
		tituloVelocimetro.innerHTML = "La NOM-021-SSA1-2021 establece dos valores límite para el CO:<br>26 ppm para el máximo del promedio horario y 9 ppm para el máximo del promedio de 8 horas.";
		descParticulas1.innerHTML = "Los autos son la fuente más importante de Monóxido de Carbono";
		descParticulas2.innerHTML = "La intoxicación por este contaminante es uno de los tipos más comunes de envenenamiento, puede inhabilitar el transporte de oxígeno hacia las células y provocar mareos, dolor de cabeza, nauseas, estados de inconsciencia e inclusive la muerte";
	}
	else if (particula == "Ozono"){
		tituloVelocimetro.innerHTML = "La NOM-020-SSA1-2021 establece los valores limites de:<br>Concentraciones menores de 0.090 ppm en el promedio horario y 0.065ppm en el promedio de 8 horas";
		descParticulas1.innerHTML = "El ozono troposférico se encuentra a nivel de superficie, en áreas urbanas se produce cuando los óxidos de nitrógeno (NOX) y los compuestos orgánicos volátiles (COV) reaccionan en la atmósfera en presencia de luz solar.";
		descParticulas2.innerHTML = "El ozono es un fuerte oxidante que en altas concentraciones produce irritación en los ojos y en las vías respiratorias, disminuyendo la función respiratoria.";
	}
	else if (particula == "Humedad"){
		tituloVelocimetro.innerHTML = "La humedad es un componente natural de la atmósfera y procede de la cantidad de vapor de agua existente en el aire";
		descParticulas1.innerHTML = "Para que puedan considerarse saludables, los niveles de humedad relativa en interior deben permanecer entre el 30 y el 60%";
		descParticulas2.innerHTML = "Niveles altos de humedad pueden propiciar la aparición de moho, enfermedades respiratorias y la aparición de ácaros de polvo<br>Niveles bajos de humedad pueden provoca piel seca, mayor contagio de gripe y disminucion del rendimiento ";
	}
	else if (particula == "Temperatura"){
		tituloVelocimetro.innerHTML = "La temperatura es el grado de calor o frío que hay en un lugar";
		descParticulas1.innerHTML = "La base es la temperatura del aire local, pero la medición también incluye la radiación térmica de los dispositivos y sistemas de calefacción, así como la temperatura de radiación de las superficies circundantes";
		descParticulas2.innerHTML = "";
	}
	
</script>
	
<script type="text/javascript">
	//variables para velocimetro
	let ppm = "<?= floatval($num) ?>";
	let max = "<?= floatval($maximoVelocimetro) ?>";
	var unidad = "";
	
	if (particula == "Humedad"){
		unidad = "%";
	}
	else if (particula == "Temperatura"){
		unidad = "°C";
	}
	else{
		unidad = "ppm";
	}
	
	//quitar ventana emergente de formulario
	if ( window.history.replaceState ) {
		window.history.replaceState( null, null, window.location.href );
	}
	//refrescar cada hora la pagina
	setTimeout(() => {
		document.location.reload();
	}, 3600000);

</script>

<script type="text/javascript" src="js/velocimetro.js" > </script>
	
</html>		