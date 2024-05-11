<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$servername = "localhost";
$username = "root";
$password = "admin";
$dbName = "tpv";

try {
    // Establecer la conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Procesar el formulario de inicio de sesión
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Consultar la base de datos para verificar las credenciales
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Verificar si se encontró un usuario con las credenciales proporcionadas
        if ($stmt->rowCount() > 0) {
            // Inicio de sesión exitoso, redirigir al usuario a Main.html
            header("Location: Main.html");
            exit();
        } else {
            // Nombre de usuario o contraseña incorrectos
            echo "Nombre de usuario o contraseña incorrectos";
        }
    }
} catch (PDOException $e) {
    // Capturar cualquier error de conexión PDO
    echo "Error de conexión: " . $e->getMessage();
}
?>
