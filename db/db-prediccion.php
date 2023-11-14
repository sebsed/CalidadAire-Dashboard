<?php

function calcularCalidad($pm25, $pm10, $ozono, $co, $temperatura, $humedad){
    echo 'DENTRO DE FUNCION';
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
			echo '<script type="text/javascript"> window.onload = function () { alert("La calidad del aire sería favorable dados los datos introducidos. Todas las personas pueden realizar actividades al aire libre."); } </script>'; 
            echo '<p>La calidad del aire sería favorable dados los datos introducidos. Todas las personas pueden realizar actividades al aire libre.</p>';
            return 'buena';
			}else if($total >= 11 && $total < 16){
			    echo '<script type="text/javascript"> window.onload = function () { alert("La calidad del aire sería moderada dados los datos introducidos. Se recomienda que los grupos sensibles eviten realizar actividades al aire libre."); } </script>'; 
                echo '<p>La calidad del aire sería media.</p>';
				}else if($total >= 16){
                    echo '<script type="text/javascript"> window.onload = function () { alert("La calidad del aire sería mala dados los datos introducidos. Todas las personas deben evitar realizar actividades al aire libre."); } </script>'; 
                    echo '<p>La calidad del aire sería mala</p>';
					}
}

// Conexión a la base de datos 
$host = '';
$dbname = '';
$username = '';
$password = '';

// Si se ha hecho clic en el botón submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['subOpcion']) && $_POST['opcionSelec'] == 1){

    }

    if (isset($_POST['submit'])){
        echo 'Hola a todos';
        echo calcularCalidad($_POST['pm25'],$_POST['pm10'],$_POST['o3'],$_POST['co'],$_POST['temp'],$_POST['hum']);
    }
}

?>