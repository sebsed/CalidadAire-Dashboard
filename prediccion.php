<!doctype html>

<?php

// Incluir archivo conexión
include('db/db-prediccion.php');

?>

<html>
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Estimación</title>
        <link rel="stylesheet" href="css/estilos1.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
	</head>
	<header>
		<?php include('header.html'); ?>
	</header>

    <body>
        <div class = "container">
            <h3 style="margin-top: 30px;">Estimación</h3>
            <p style="margin-top: 10px;">En esta pestaña es posible obtener una estimación de calidad del aire a uno o tres días. La estimación de calidad automática se realiza 
            utilizando los datos más recientes obtenidos por los sensores. La estimación de calidad con datos se obtiene con métricas propuestas por el usuario.</p>
        </div>
        <div class = "container" style="margin-top: 30px;">
            <form name="sumbitForm" method="post" action="prediccion.php?act=db-prediccion">
                <div class = "row">
                    <div class = "col-sm">
                        <label>Selecciona el tipo de estimación que deseas obtener:</label>
                    </div>
                    <div class = "col-sm">
                        <select class="form-select" name="opcionSelec">
                            <option value="">Seleccione opción</option>
                            <option value="1">Estimación automática a 1 día</option>
                            <option value="2">Estimación automática a 3 días</option>
                            <option value="3">Estimación con datos (a 1 día)</option>
                        </select>
                    </div> 
                        
                </div>
                <div class = "row" style="margin-top: 30px;">
                    <input type="submit" class="btn btn-primary btn-lg" name="subOpcion" value="Seleccionar opción"/>
                </div>
                <!--- Desplegar la entrada de datos cuando se seleccione la tercera opción --->
                <?php if(isset($_POST['subOpcion']) && $_POST['opcionSelec'] == 3){ ?>
                <div class = "row" style="margin-top: 30px;">
                    <h5 style="margin-top: 30px;">Estimación con datos (a 1 día)</h5>
                    <p style="margin-top: 10px;">Ingresa valores correspondientes a las métricas para obtener un valor de calidad del aire estimado.</p>
                    <div class = "col-sm">
                        <label>PM 2.5 (Partículas suspendidas 2.5 micras)</label>
                        <input value="0" min="0" step="any" type="number" id="pm25" name="pm25" class="form-control" />
                    </div>
                    <div class = "col-sm">
                        <label>PM 10 (Partículas suspendidas 10 micras)</label>
                        <input value="0" min="0" step="any" type="number" id="pm10" name="pm10" class="form-control" />
                    </div>
                    <div class = "col-sm">
                        <label>O3 (Ozono)</label>
                        <input value="0" min="0" step="any" type="number" id="o3" name="o3" class="form-control" />
                    </div>
                </div>
                <div class = "row" style="margin-top: 10px;">
                    <div class = "col-sm">
                        <label>CO (Monóxido de carbono)</label>
                        <input value="0" min="0" step="any" type="number" id="co" name="co" class="form-control" />
                    </div>
                    <div class = "col-sm">
                        <label>Temperatura</label>
                        <input value="0" step="any" type="number" id="temp" name="temp" class="form-control" />
                    </div>
                    <div class = "col-sm">
                        <label>Humedad relativa</label>
                        <input value="0" min="0" step="any" type="number" id="hum" name="hum" class="form-control" />
                    </div>
                </div>
                <div class = "row" style="margin-top: 30px;">
                    <input type="submit" class="btn btn-primary btn-lg" name="submit" value="Obtener estimación"/>
                </div>
                <?php } ?>

            </form>
        </div>
        <div class = "container" style="margin-top: 30px;">
            <div class = "row">
                <?php if($mostrar){ ?>
                    <! semaforo –>
                    <div class = "col-2">
                        <div class="trafficLight">
                            <span class="colorful"></span>
                        </div>
                    </div>	
                            
                    <! Informacion del semaforo –>
                    <div class = "col-10">
                        <div class="card borde-semaforo">
                            <div class="card-body">
                            <h4 class="card-title">Estimación de riesgo:</h4>
                            <h5 class="card-subtitle mb-2" id="tituloIndice"></h5>
                            <p class="card-text" id="descIndice1"></p>
                            <p class="card-text" id="descIndice2"></p>
                            </div>
                        </div>
                    </div>
                
                <?php } ?>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <! colorear el semaforo y poner datos de indice–>
        <script>
            let totalCalculado = "<?= $totalCalculado ?>";
            let indiceCalidad = "<?= $indiceCalidad ?>";
            const descIndice1 = document.getElementById("descIndice1");
            const descIndice2 = document.getElementById("descIndice2");
            const tituloIndice = document.getElementById("tituloIndice");
            //console.log(valorSemaforo);
            
            //verde
            if (totalCalculado < 11){
                document.documentElement.style.setProperty('--my-start-color', '#9ACA3C');
                document.documentElement.style.setProperty('--my-main-color', '#d6e9b1');
                tituloIndice.innerHTML = "Riesgo bajo";
                descIndice1.innerHTML = "Indice estimado: " + indiceCalidad;
                descIndice2.innerHTML = "Existe poco o ningún riesgo para la salud. Se puede realizar cualquier actividad al aire libre.";
            }
            //amarillo
            else if (totalCalculado >= 11 && totalCalculado < 16){
                document.documentElement.style.setProperty('--my-start-color', '#F7EC0F');
                document.documentElement.style.setProperty('--my-main-color', '#fbf79f');
                tituloIndice.innerHTML = "Riesgo moderado";
                descIndice1.innerHTML = "Indice estimado: " + indiceCalidad;
                descIndice2.innerHTML = "Los grupos susceptibles pueden presentar síntomas en la salud. Las personas que son extremadamente susceptibles a la contaminación deben considerar limitar la exposición al aire libre.";
            }
            //rojo
            else if (totalCalculado >= 16){
                document.documentElement.style.setProperty('--my-start-color', '#ED2124'); 
                document.documentElement.style.setProperty('--my-main-color', '#f7a6a7');
                tituloIndice.innerHTML = "Riesgo alto";
                descIndice1.innerHTML = "Indice estimado: " + indiceCalidad;
                descIndice2.innerHTML = "Todos pueden presentar efectos en la salud; quienes pertenecen a grupos susceptibles experimentan efectos graves. Los niños, adultos mayores, personas que realizan actividad física intensa o con enfermedades respiratorias y cardiovasculares, deben evitar la exposicion al aire libre y el resto de la poblacion debe limitar la exposición al aire libre.";
            }
        </script>

    </body>
</html>