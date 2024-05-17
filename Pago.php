<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TPV Virtual</title>
    <link rel="stylesheet" href="css/Pago.css">
</head>
<body>
<div class="container">
    <a href="Main.html" class="back-button">Atrás</a>
    <div class="products">
        <h2>Productos</h2>
        <!-- Productos obtenidos de la base de datos -->
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "admin";
        $dbName = "tpv";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->query("SELECT id, nombre, imagen, precio FROM productos");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='product' data-id='{$row['id']}' data-price='{$row['precio']}'>";
                if (filter_var($row['imagen'], FILTER_VALIDATE_URL)) {
                    echo "<img src='{$row['imagen']}' alt='{$row['nombre']}' style='width: 100px;'>";
                } else {
                    echo "<img src='img/{$row['imagen']}' alt='{$row['nombre']}' style='width: 100px;'>";
                }
                echo "<span>{$row['nombre']} - {$row['precio']}€</span>";
                echo "<button onclick='addToCart({$row['id']})'>Añadir</button>";
                echo "</div>";
            }
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
        ?>
    </div>
    <div class="cart">
        <h2>Cesta</h2>
        <div id="cartItems"></div>
        <div id="total">Total: 0€</div>
        <input type="number" id="customerPayment" placeholder="Dinero recibido" step="0.01"/>
        <button onclick="simulatePayment()">Calcular Cambio</button>
        <div id="changeDue">Cambio: 0€</div>
        <button onclick="resetTransaction()">Limpiar</button>
    </div>
</div>
<script src="js/Pago.js"></script>
</body>
</html>
