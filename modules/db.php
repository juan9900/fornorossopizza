
<?php
$host = "127.0.0.1";
$username = "root";
$password = '';
$database = "club";
$db = mysqli_connect($host, $username, $password, $database);

if (!$db) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
?>
