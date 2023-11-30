<!doctype html>

<?php
// Recuperar fecha actual para limitar selectores date
$fechaActual = date('Y-m-d');

// Incluir archivo conn
include('db/db-analisis.php');

?>

<html>
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Análisis</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
	</head>
	<header>
		<?php include('header.html'); ?>
	</header>

    <body>
        <div class = "container">
            <h3 style="margin-top: 30px;">Análisis</h3>
            <p style="margin-top: 10px;">En esta pestaña es posible desplegar gráficas comparando métricas relevantes para observar su correlación.
            Selecciona una fecha inicial, una fecha final y el par de métricas que deseas visualizar.</p>
            <p style="margin-top: 10px;">Los datos están normalizados entre 0 y 1 con la finalidad de contar con una comparación congruente. 
            En caso de desear acceder a los datos sin normalizar, consultar la pestaña "Histórico".</p>
        </div>
        <div class = "container" style="margin-top: 30px;">
            <form name="sumbitForm" method="post" action="analisis.php?act=db-analisis">
                <div class = "row">
                    <h5 style="margin-top: 30px;">Selección de fechas y sensor</h5>
                    <div class = "col-sm text-center">
                        <label>Ingresa la fecha inicial:
                            <input type="date" name="fecha_inferior" max= <?php echo $fechaActual ?> >
                        </label>
                    </div>
                    <div class = "col-sm text-center">
                        <label>Ingresa la fecha final:
                            <input type="date" name="fecha_superior" max = <?php echo $fechaActual ?> >
                        </label>
                    </div>
                    <div class = "col-sm text-center">
                        <select class="form-select" name="sensorSelec">
                            <option selected>Seleccione sensor</option>
                            <option value="1">Sensor 1</option>
                            <option value="2">Sensor 2</option>
                            <option value="3">Sensor 3</option>
                        </select>
                    </div> 
                </div>
                <div class = "row" style="margin-top: 30px;">
                    <h5 style="margin-top: 30px;">Selección de primer métrica</h5>
                    <div class = "col-sm">
                        <input type="radio" id="pm25" name="met_1" value="PM25"/>
                        <label>PM 2.5 (Partículas suspendidas 2.5 micras)</label>
                    </div>
                    <div class = "col-sm">
                        <input type="radio" id="pm10" name="met_1" value="PM10"/>
                        <label>PM 10 (Partículas suspendidas 10 micras)</label>
                    </div>
                    <div class = "col-sm">
                        <input type="radio" id="o3" name="met_1" value="Ozone"/>
                        <label>O3 (Ozono)</label>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-sm">
                        <input type="radio" id="co" name="met_1" value="Carbon_Mono"/>
                        <label>CO (Monóxido de carbono)</label>
                    </div>
                    <div class = "col-sm">
                        <input type="radio" id="temp" name="met_1" value="Temperature"/>
                        <label>Temperatura</label>
                    </div>
                    <div class = "col-sm">
                        <input type="radio" id="hum" name="met_1" value="Humidity"/>
                        <label>Humedad relativa</label>
                    </div>
                </div>
                <div class = "row" style="margin-top: 30px;">
                    <h5 style="margin-top: 30px;">Selección de segunda métrica</h5>
                    <div class = "col-sm">
                        <input type="radio" id="pm25" name="met_2" value="PM25"/>
                        <label>PM 2.5 (Partículas suspendidas 2.5 micras)</label>
                    </div>
                    <div class = "col-sm">
                        <input type="radio" id="pm10" name="met_2" value="PM10"/>
                        <label>PM 10 (Partículas suspendidas 10 micras)</label>
                    </div>
                    <div class = "col-sm">
                        <input type="radio" id="o3" name="met_2" value="Ozone"/>
                        <label>O3 (Ozono)</label>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-sm">
                        <input type="radio" id="co" name="met_2" value="Carbon_Mono"/>
                        <label>CO (Monóxido de carbono)</label>
                    </div>
                    <div class = "col-sm">
                        <input type="radio" id="temp" name="met_2" value="Temperature"/>
                        <label>Temperatura</label>
                    </div>
                    <div class = "col-sm">
                        <input type="radio" id="hum" name="met_2" value="Humidity"/>
                        <label>Humedad relativa</label>
                    </div>
                </div>
                <div class = "row" style="margin-top: 30px;">
                    <input type="submit" class="btn btn-primary btn-lg" name="submit" value="Generar gráfica"/>
                </div>
            </form>
        </div>

        <!--- Gráfica --->
        <div class = "container" style="margin-top: 50px;">
            <?php if(isset($_POST['submit']) && isset($_POST['met_1']) && isset($_POST['met_2'])){ ?>
                <canvas id="comp_chart"></canvas>
            <?php } ?>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <!---Gráfica--->
        <script>
            
            new Chart(document.getElementById("comp_chart"), {
                type : 'line',
                data : {
                    labels : <?php echo json_encode($timeData); ?>,
                    datasets : [
                            {
                                data : <?php echo json_encode($normalizedData1); ?>,
                                label : met1Title,
                                borderColor : "#8ca083",
                                fill : false
                            },
                            {
                                data : <?php echo json_encode($normalizedData2); ?>,
                                label : met2Title,
                                borderColor : "#a78ab7",
                                fill : false
                            }
                        ]
                },
                options : {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Comparación de métricas',
                            font: {
                                size: 25
                            }
                        }
                    }
                }
            });
        </script>
    </body>
</html>