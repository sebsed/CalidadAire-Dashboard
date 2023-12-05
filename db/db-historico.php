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
            
            // Consulta SQL para obtener los datos que se hayan solicitado dentro del rango de fechas
            $query = "SELECT Timestamp";
            // Dependiendo de las métricas seleccionadas se hace el query
            if(isset($_POST['cb_pm25'])){
                $query .= ", PM25 ";
            }
            if(isset($_POST['cb_pm10'])){
                $query .= ", PM10 ";
            }
            if(isset($_POST['cb_o3'])){
                $query .= ", Ozone ";
            }
            if(isset($_POST['cb_co'])){
                $query .= ", Carbon_Mono ";
            }
            if(isset($_POST['cb_temp'])){
                $query .= ", Temperature ";
            }
            if(isset($_POST['cb_hum'])){
                $query .= ", Humidity ";
            }
            // Completar query
            $query .= "FROM report WHERE Timestamp BETWEEN :fecha_inferior AND :fecha_superior";

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':fecha_inferior', $fecha_inferior, PDO::PARAM_STR);
                $stmt->bindParam(':fecha_superior', $fecha_superior, PDO::PARAM_STR);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
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

?>