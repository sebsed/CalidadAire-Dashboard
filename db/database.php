<?php

$servername = "";
$username = "";
$password = "";
$dbname = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//obtener ultimo registro del query
$sql = "SELECT * FROM medicion Order By idMedicion Desc";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

?>