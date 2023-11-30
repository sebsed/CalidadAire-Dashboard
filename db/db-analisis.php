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
            // Hacer query de las métricas seleccionadas
            if(isset($_POST['met_1']) && isset($_POST['met_2'])){
                $query = "SELECT Timestamp, ".$_POST['met_1'].", ".$_POST['met_2']." FROM newreport WHERE Timestamp BETWEEN :fecha_inferior AND :fecha_superior";
            }

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

// Inicializar arreglos para métricas
$timeData = [];
$met1Data = [];
$met2Data = [];
$met1Title = $_POST['met_1'];
$met2Title = $_POST['met_2'];

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

// Normalización de datos para métrica 1
$min1 = min($met1Data);
$max1 = max($met1Data);
$normalizedData1 = array_map(function ($value) use ($min1, $max1) {
    return ($value - $min1) / ($max1 - $min1);
}, $met1Data);

// Normalización de datos para métrica 2
$min2 = min($met2Data);
$max2 = max($met2Data);
$normalizedData2 = array_map(function ($value) use ($min2, $max2) {
    return ($value - $min2) / ($max2 - $min2);
}, $met2Data);

// Pasar nombres de etiquetas
echo '<script>';
echo 'var met1Title = ' . json_encode($met1Title) . ';';
echo 'var met2Title = ' . json_encode($met2Title) . ';';
echo '</script>';

?>