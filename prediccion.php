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

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    </body>
</html>