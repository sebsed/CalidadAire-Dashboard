<?php
// Conexión a la base de datos 
$host = '';
$dbname = '';
$username = '';
$password = '';

// Si se ha hecho clic en el botón submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])){

        // Recupera los valores de los inputs de fecha
        $fecha_inferior = $_POST['fecha_inferior'];
        $fecha_superior = $_POST['fecha_superior'];

        if($fecha_inferior > $fecha_superior){
            echo '<script type="text/javascript"> window.onload = function () { alert("La fecha inferior no puede ser mayor a la superior. Por favor selecciona un rango correcto."); } </script>'; 
        }
        else{
            // Cuando se necesita obtener gráficas de pestaña de análisis
            if(isset($_POST['met_1']) && isset($_POST['met_2'])){
                $query = "SELECT Timestamp, ".$_POST['met_1'].", ".$_POST['met_2']." FROM report WHERE Timestamp BETWEEN :fecha_inferior AND :fecha_superior";
            }
            else{
                // Consulta SQL para obtener los datos dentro del rango de fechas
                $query = "SELECT Timestamp, PM25, PM10, Ozone, Carbon_Mono, Temperature, Humidity FROM report WHERE Timestamp BETWEEN :fecha_inferior AND :fecha_superior";
            }

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':fecha_inferior', $fecha_inferior, PDO::PARAM_STR);
                $stmt->bindParam(':fecha_superior', $fecha_superior, PDO::PARAM_STR);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //echo $data;
            
            } catch (PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
            } 
        }
    }
}

// Inicializar arreglos para cada métrica
$timeData = [];
$pm25Data = [];
$pm10Data = [];
$ozoneData = [];
$carbonData = [];
$tempData = [];
$humData = [];

// Inicializar arreglos para sección de análisis
$met1Data = [];
$met2Data = [];
$met1Title = $_POST['met_1'];
$met2Title = $_POST['met_2'];

// Cuando esté en la parte de análisis
if(isset($_POST['met_1']) && isset($_POST['met_2'])){
    // Si $data no está vacío
    if (isset($data) && !empty($data)) {
        foreach ($data as $row) {
            $timeData[] = $row['Timestamp'];
            $met1Data[] = $row[$_POST['met_1']];
            $met2Data[] = $row[$_POST['met_2']];
        }
    }
}
else{
    // Cuando esté en la parte de histórico
    // Si $data no está vacío
    if (isset($data) && !empty($data)) {
        foreach ($data as $row) {
            $timeData[] = $row['Timestamp'];
            $pm25Data[] = $row['PM25'];
            $pm10Data[] = $row['PM10'];
            $ozoneData[] = $row['Ozone'];
            $carbonData[] = $row['Carbon_Mono'];
            $tempData[] = $row['Temperature'];
            $humData[] = $row['Humidity'];
        }
    }
}

?>