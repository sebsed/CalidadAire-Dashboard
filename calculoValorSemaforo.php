<?php
//calculo valor del semaforo

//obtener de base de datos
//query de datos de ultimas 8 horas
$query= "SELECT AVG(PM25) AS promedioPMDos,
	AVG(PM10) AS promedioPMDiez,
    AVG(Carbon_Mono) AS promedioMonoxido,
    AVG(Ozone) AS promedioOzono FROM report
	WHERE Timestamp IS NOT NULL
	AND Timestamp >= DATE_ADD('" . $fechaReciente . "', INTERVAL -8 HOUR)";
	//echo $query;
$result = $conn->query($query);
$rowPromedio = $result->fetch_assoc();

//mediciones
$promedioPM25 = $rowPromedio['promedioPMDos'];
$promedioPM10 = $rowPromedio['promedioPMDiez'];
$promedioMonoxido = $rowPromedio['promedioMonoxido'] /450;
$promedioOzono = $rowPromedio['promedioOzono'] / 450;


// $promedioPM25 = 50;
// $promedioPM10 = 170;
// $promedioMonoxido = 6;
// $promedioOzono = 0.011;

//normalizar valores para compararlos
$normalizarPM25 = $promedioPM25 / $maximoPM25;
$normalizarPM10 = $promedioPM10 / $maximoPM10;
$normalizarMonoxido = $promedioMonoxido / $maximoMonoxido;
$normalizarOzono = $promedioOzono / $maximoOzono;
//echo $normalizarPM25 . "aaaa" . $normalizarPM10 . "aaaa" .$normalizarMonoxido . "aaaa" .$normalizarOzono;

//obtener el mas grande
$array = compact('normalizarPM25', 'normalizarPM10', 'normalizarMonoxido', 'normalizarOzono');
arsort($array);
$masGrande = key($array);

//definir color del semaforo
if ($masGrande == "normalizarPM25") {
	$valorSemaforo = colorSemaforo($valorPM25, 25, 45, 79, 147);
	$contaminante = "PM 2.5";
	//echo $contaminante . $valorSemaforo;
}
else if ($masGrande == "normalizarPM10") {
	$valorSemaforo = colorSemaforo($valorPM10, 50, 75, 155, 235);
	$contaminante = "PM 10";
	//echo $contaminante . $valorSemaforo;
} 
else if ($masGrande == "normalizarMonoxido") {
	$valorSemaforo = colorSemaforo($valorMonoxido, 8.75, 11, 13.3, 15.5);
	$contaminante = "Monoxido";
	//echo $contaminante . $valorSemaforo;
} 
else if ($masGrande == "normalizarOzono") {
	$valorSemaforo = colorSemaforo($valorOzono, 0.051, 0.070, 0.092, 0.114);
	$contaminante = "Ozono";
	//echo $contaminante . $valorSemaforo;
} 
else {
	$valorSemaforo = "error";
}

//calcular color de semaforo
function colorSemaforo($valorContaminante, $limiteBueno, $limiteAceptable, $limiteMalo, $limiteMuyMalo) {
    //clasificar en los 5 semaforos 
	if ($valorContaminante <= $limiteBueno){
		return "bueno";
	}
	else if ($valorContaminante > $limiteBueno && $valorContaminante <= $limiteAceptable){
		return "aceptable";
	}
	else if ($valorContaminante > $limiteAceptable && $valorContaminante <= $limiteMalo){
		return "malo";
	}
	else if ($valorContaminante > $limiteMalo && $valorContaminante <= $limiteMuyMalo){
		return "muyMalo";
	}
	else if ($valorContaminante >= $limiteMalo){
		return "extremo";
	}
}

?>