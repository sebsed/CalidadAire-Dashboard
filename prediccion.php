<!doctype html>

<html>
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Predicción</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
	</head>
	<header>
		<?php include('header.html'); ?>
	</header>

    <body>
        <div class = "container">
            <h3 style="margin-top: 30px;">Predicción</h3>
            <p style="margin-top: 10px;">En esta pestaña es posible obtener una estimación de calidad del aire a uno o tres días. Existe la opción de hacer el cálculo con datos actuales o con datos propuestos.</p>
        </div>
        <div class = "container" style="margin-top: 30px;">
            <form name="sumbitForm" method="post" action="analisis.php?act=db-analisis">
                <div class = "row">
                    <h5 style="margin-top: 30px;">Predicción automática</h5>
                    <div class = "col-sm">
                        <label>Selecciona la cantidad de días a predecir: </label>
                    </div>
                    <div class = "col-sm">
                        <input type="radio" id="dia_1" name="pred_auto" value="1"/>
                        <label>1 día</label>
                    </div>
                    <div class = "col-sm">
                        <input type="radio" id="dia_3" name="pred_auto" value="3"/>
                        <label>3 días</label>
                    </div>
                </div>
                <div class = "row" style="margin-top: 30px;">
                    <h5 style="margin-top: 30px;">Predicción con datos (a un día)</h5>
                    <div class = "col-sm">
                        <label>PM 2.5 (Partículas suspendidas 2.5 micras)</label>
                        <input value="0" min="0" type="number" id="pm25" id="pm25" class="form-control" />
                    </div>
                    <div class = "col-sm">
                        <label>PM 10 (Partículas suspendidas 10 micras)</label>
                        <input value="0" min="0" type="number" id="pm10" id="pm10" class="form-control" />
                    </div>
                    <div class = "col-sm">
                        <label>O3 (Ozono)</label>
                        <input value="0" min="0" type="number" id="o3" id="o3" class="form-control" />
                    </div>
                </div>
                <div class = "row" style="margin-top: 10px;">
                    <div class = "col-sm">
                        <label>CO (Monóxido de carbono)</label>
                        <input value="0" min="0" type="number" id="co" id="co" class="form-control" />
                    </div>
                    <div class = "col-sm">
                        <label>Temperatura</label>
                        <input value="0" min="0" type="number" id="temp" id="temp" class="form-control" />
                    </div>
                    <div class = "col-sm">
                        <label>Humedad relativa</label>
                        <input value="0" min="0" type="number" id="hum" id="hum" class="form-control" />
                    </div>
                </div>
                <div class = "row" style="margin-top: 30px;">
                    <input type="submit" class="btn btn-primary btn-lg" name="submit" value="Obtener predicción"/>
                </div>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    </body>
</html>