<?php

function calcularCalidad($pm25, $pm10, $ozono, $co, $temperatura, $humedad){
    //EVALUACIÓN PARA P.M. 2.5
    if($pm25 < 12) {
        $pm25eval = 1; // Bueno
        }else if($pm25 >= 12 && $pm25 <= 35.4){
            $pm25eval = 2; // Moderado
            }else if ($pm25 >= 35.5 && $pm25 <=55.4) {
                $pm25eval = 3; //Insalubre para grupos sensibles
                }else {
                    $pm25eval = 4; //Insalubre para todos
                    }
		
    //EVALUACIÓN PARA P.M. 10
    if ($pm10 < 54) {
        $pm10eval = 1; // Bueno
        } else if ($pm10 >= 54 && $pm10 <= 154) {
            $pm10eval = 2; // Moderado
            } else if ($pm10 >= 155 && $pm10 <= 254) {
                $pm10eval = 3; // Insalubre para grupos sensibles
                } else {
                    $pm10eval = 4; // Insalubre para todos
                    }
		
    //Evaluación de ozono
    if ($ozono < 50) {
        $ozonoeval = 1; // Bueno
        } else if ($ozono >= 50 && $ozono <= 100) {
            $ozonoeval = 2; // Moderado
            } else if ($ozono >= 101 && $ozono <= 168) {
                $ozonoeval = 3; // Insalubre para grupos sensibles
                } else {
                    $ozonoeval = 4; // Insalubre para todos
                    }
		
		//Evaluación de C0
		if ($co < 400) {
			$coeval = 1; // Bueno
			} else if ($co >= 400 && $co <= 1000) {
				$coeval = 2; // Moderado
				} else if ($co >= 1001 && $co <= 2000) {
					$coeval = 3; // Insalubre para grupos sensibles
					} else {
						$coeval = 4; // Insalubre para todos
						}
		
		//Evaluación de temperatura
		if ($temperatura >= 20 && $temperatura <= 25) {
			$temperaturaeval = 1;
			} else if (temperatura > 25) {
				$temperaturaeval = 2;
				}else{
					$temperaturaeval = 3;
					}
		
		//Evaluación de la humedad
		if ($humedad >= 30 && $humedad <= 60) {
			$humedadeval = 1;
			} else if ($humedad < 30 || $humedad > 60) {
				$humedadeval = 2;
				}
		
		//Evaluacion de la calidad de aire
		$total = $pm25eval + $pm10eval + $ozonoeval + $coeval + $temperaturaeval + $humedadeval;
		$x = ($total * 6.6666666666666666666666666666667) + (-6.6666666666666666666666666666667*21+100);
		
		if($total < 11) {
			//echo '<script type="text/javascript"> window.onload = function () { alert("La calidad del aire sería favorable dados los datos introducidos, con índice de '.$x.'. Todas las personas pueden realizar actividades al aire libre."); } </script>'; 
            return '<h3 style="margin-top: 30px;">Resultados</h3><p style="margin-top: 10px;">La calidad del aire sería favorable dados los datos introducidos, con índice de '.$x.'. Todas las personas pueden realizar actividades al aire libre</p>';
			}else if($total >= 11 && $total < 16){
			    //echo '<script type="text/javascript"> window.onload = function () { alert("La calidad del aire sería moderada dados los datos introducidos, con índice de '.$x.'. Se recomienda que los grupos sensibles eviten realizar actividades al aire libre."); } </script>'; 
                return '<h3 style="margin-top: 30px;">Resultados</h3><p style="margin-top: 10px;">La calidad del aire sería moderada dados los datos introducidos, con índice de '.$x.'. Se recomienda que los grupos sensibles eviten realizar actividades al aire libre.</p>';
				}else if($total >= 16){
                    //echo '<script type="text/javascript"> window.onload = function () { alert("La calidad del aire sería mala dados los datos introducidos, con índice de '.$x.'. Todas las personas deben evitar realizar actividades al aire libre."); } </script>'; 
                    return '<h3 style="margin-top: 30px;">Resultados</h3><p style="margin-top: 10px;">La calidad del aire sería mala dados los datos introducidos, con índice de '.$x.'. Todas las personas deben evitar realizar actividades al aire libre.</p>';
					}
}

function obtenerCalidadFecha($fechaLimite){
    // Conexión a la base de datos 
    $host = '';
    $dbname = '';
    $username = '';
    $password = '';

    $query = "SELECT AVG(PM25) AS promPM25, AVG(PM10) AS promPM10, AVG(Ozone) AS promOzone, AVG(Carbon_Mono) AS promCarbon, AVG(Temperature) AS promTemp, AVG(Humidity) AS promHum FROM report WHERE Timestamp >= :fecha";

    // Ejecutar el query
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':fecha', $fechaLimite, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo $data;
    
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    } 

    // Guardar valores
    // Si $data no está vacío
    if (isset($data) && !empty($data)) {
        foreach ($data as $row) {
            $promPM25[] = $row['promPM25'];
            $promPM10[] = $row['promPM10'];
            $promOzone[] = $row['promOzone'];
            $promCarbon[] = $row['promCarbon'];
            $promTemp[] = $row['promTemp'];
            $promHum[] = $row['promHum'];
        }
    }

    // Llamar la funcion para evaluar calidad del aire
    //echo $promPM25[0]."\n";
    //echo $promOzone[0]."\n";
    //echo $promTemp[0]."\n";
    return calcularCalidad($promPM25[0],$promPM10[0],$promOzone[0],$promCarbon[0],$promTemp[0],$promHum[0]);

}



// Si se ha hecho clic en el botón submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['opcionSelec'] == 1 && isset($_POST['subOpcion'])){
        // ***** Se tiene que cambiar por la fecha actual cuando se tenga la base de datos final
        // Por ahora, se limita a la ultima fecha obtenida en el reporte ($fecha)
        $fecha_actual = date('Y-m-d');
        $fecha = strtotime('2023-08-14');
        $fecha = date('Y-m-d', $fecha);

        $fecha_limite = strtotime('-1 day', strtotime($fecha));
        $fecha_limite = date('Y-m-d', $fecha_limite);
        //echo $fecha_limite;
        $salidaFuncion = obtenerCalidadFecha($fecha_limite);
    }

    if($_POST['opcionSelec'] == 2){
        // ***** Se tiene que cambiar por la fecha actual cuando se tenga la base de datos final
        // Por ahora, se limita a la ultima fecha obtenida en el reporte ($fecha)
        $fecha_actual = date('Y-m-d');
        $fecha = strtotime('2023-08-14');
        $fecha = date('Y-m-d', $fecha);

        $fecha_limite = strtotime('-3 day', strtotime($fecha));
        $fecha_limite = date('Y-m-d', $fecha_limite);
        //echo $fecha_actual;
        //echo $fecha_limite;
        $salidaFuncion = obtenerCalidadFecha($fecha_limite);
    }

    if (isset($_POST['submit'])){
        $salidaFuncion = calcularCalidad($_POST['pm25'],$_POST['pm10'],$_POST['o3'],$_POST['co'],$_POST['temp'],$_POST['hum']);
    }
}

?>