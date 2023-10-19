<!doctype html>

<?php
// Recuperar fecha actual para limitar selectores date
$fechaActual = date('Y-m-d');

// Incluir archivo conn
include('db-historico.php');
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Histórico</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
	</head>
	<header>
		<?php include('header.html'); ?>
	</header>

    <body>
        <div class = "container">
            <h3 style="margin-top: 30px;">Datos históricos</h3>
            <p style="margin-top: 10px;">En esta pestaña es posible desplegar gráficas correspondientes a los distintos contaminantes, a la temperatura y a la humedad relativa.
            Selecciona una fecha inicial, una fecha final y las métricas que deseas visualizar.</p>
        </div>
        <div class = "container" style="margin-top: 30px;">
            <h5 style="margin-top: 30px;">Selección de fechas y sensor</h5>
            <form method="post" action="historico.php?act=db-historico">
                <div class = "row">
                    <div class = "col-sm text-center">
                        <label>Ingresa la fecha inicial:
                            <input type="date" name="fecha_inferior" max= <?php echo $fechaActual ?> >
                        </label>
                    </div>
                    <div class = "col-sm text-center">
                        <label>Ingresa la fecha final:
                            <input type="date" name="fecha_superior" max = <?php echo $fechaActual?> >
                        </label>
                    </div>
                    <div class = "col-sm text-center">
                        <select class="form-select" aria-label="Default select example" name="sensorSelec">
                            <option selected>Seleccione sensor</option>
                            <option value="1">Sensor 1</option>
                            <option value="2">Sensor 2</option>
                            <option value="3">Sensor 3</option>
                        </select>
                    </div> 
                </div>
                <div class = "row" style="margin-top: 30px;">
                    <h5 style="margin-top: 30px;">Selección de métricas</h5>
                    <div class = "col-sm">
                        <input type="checkbox" id="pm25" name="cb_pm25"/>
                        <label>PM 2.5 (Partículas suspendidas 2.5 micras)</label>
                    </div>
                    <div class = "col-sm">
                        <input type="checkbox" id="pm10" name="cb_pm10"/>
                        <label>PM 10 (Partículas suspendidas 10 micras)</label>
                    </div>
                    <div class = "col-sm">
                        <input type="checkbox" id="o3" name="cb_o3"/>
                        <label>O3 (Ozono)</label>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-sm">
                        <input type="checkbox" id="co" name="cb_co"/>
                        <label>CO (Monóxido de carbono)</label>
                    </div>
                    <div class = "col-sm">
                        <input type="checkbox" id="temp" name="cb_temp"/>
                        <label>Temperatura</label>
                    </div>
                    <div class = "col-sm">
                        <input type="checkbox" id="hum" name="cb_hum"/>
                        <label>Humedad relativa</label>
                    </div>
                </div>
                <div class = "row" style="margin-top: 30px;">
                    <input type="submit" class="btn btn-primary btn-lg" name="submit" value="Generar gráficas"/>
                </div>
            </form>
        </div>
        <!--- Gráficas --->
        <div class = "container" style="margin-top: 50px;">
            <?php if(isset($_POST['submit']) && isset($_POST['cb_pm25'])){ ?>
            <canvas id="pm25_chart"></canvas>
            <?php } ?>
        </div>
        <div class = "container" style="margin-top: 50px;">
            <?php if(isset($_POST['submit']) && isset($_POST['cb_pm10'])){ ?>
            <canvas id="pm10_chart"></canvas>
            <?php } ?>
        </div>
        <div class = "container" style="margin-top: 50px;">
            <?php if(isset($_POST['submit']) && isset($_POST['cb_o3'])){ ?>
            <canvas id="o3_chart"></canvas>
            <?php } ?>
        </div>
        <div class = "container" style="margin-top: 50px;">
            <?php if(isset($_POST['submit']) && isset($_POST['cb_co'])){ ?>
            <canvas id="co_chart"></canvas>
            <?php } ?>
        </div>
        <div class = "container" style="margin-top: 50px;">
            <?php if(isset($_POST['submit']) && isset($_POST['cb_temp'])){ ?>
            <canvas id="temp_chart"></canvas>
            <?php } ?>
        </div>
        <div class = "container" style="margin-top: 50px;">
            <?php if(isset($_POST['submit']) && isset($_POST['cb_hum'])){ ?>
            <canvas id="hum_chart"></canvas>
            <?php } ?>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


        <!---Gráfica PM2.5--->
        <script>
            new Chart(document.getElementById("pm25_chart"), {
                type : 'line',
                data : {
                    labels : <?php echo json_encode($timeData); ?>,
                    datasets : [
                            {
                                data : <?php echo json_encode($pm25Data); ?>,
                                label : "PM 2.5",
                                borderColor : "#3cba9f",
                                fill : false
                            }]
                },
                options : {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Partículas suspendidas 2.5 micras',
                            font: {
                                size: 25
                            }
                        }
                    }
                }
            });
        </script>

        <!---Gráfica PM10--->
        <script>
            new Chart(document.getElementById("pm10_chart"), {
                type : 'line',
                data : {
                    labels : <?php echo json_encode($timeData); ?>,
                    datasets : [
                            {
                                data : <?php echo json_encode($pm10Data); ?>,
                                label : "PM 10",
                                borderColor : "#FF5733",
                                fill : false
                            }]
                },
                options : {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Partículas suspendidas 10 micras',
                            font: {
                                size: 25
                            }
                        }
                    }
                }
            });
        </script>

        <!---Gráfica Ozono--->
        <script>
            new Chart(document.getElementById("o3_chart"), {
                type : 'line',
                data : {
                    labels : <?php echo json_encode($timeData); ?>,
                    datasets : [
                            {
                                data : <?php echo json_encode($ozoneData); ?>,
                                label : "Ozono",
                                borderColor : "#33B5FF",
                                fill : false
                            }]
                },
                options : {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Ozono',
                            font: {
                                size: 25
                            }
                        }
                    }
                }
            });
        </script>

        <!---Gráfica Monoxido de carbono--->
        <script>
            new Chart(document.getElementById("co_chart"), {
                type : 'line',
                data : {
                    labels : <?php echo json_encode($timeData); ?>,
                    datasets : [
                            {
                                data : <?php echo json_encode($carbonData); ?>,
                                label : "Monóxido de carbono",
                                borderColor : "#8633FF",
                                fill : false
                            }]
                },
                options : {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monóxido de carbono',
                            font: {
                                size: 25
                            }
                        }
                    }
                }
            });
        </script>

        <!---Gráfica Temperatura--->
        <script>
            new Chart(document.getElementById("temp_chart"), {
                type : 'line',
                data : {
                    labels : <?php echo json_encode($timeData); ?>,
                    datasets : [
                            {
                                data : <?php echo json_encode($tempData); ?>,
                                label : "Temperatura",
                                borderColor : "#FFE633",
                                fill : false
                            }]
                },
                options : {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Temperatura',
                            font: {
                                size: 25
                            }
                        }
                    }
                }
            });
        </script>

        <!---Gráfica Humedad--->
        <script>
            new Chart(document.getElementById("hum_chart"), {
                type : 'line',
                data : {
                    labels : <?php echo json_encode($timeData); ?>,
                    datasets : [
                            {
                                data : <?php echo json_encode($humData); ?>,
                                label : "Humedad",
                                borderColor : "#FF33DA",
                                fill : false
                            }]
                },
                options : {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Humedad',
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