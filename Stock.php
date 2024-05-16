<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock TPV</title>
    <link rel='stylesheet' type='text/css' media='screen' href='css/Stockl.css'>
    <script>
        function searchProduct() {
            let input = document.getElementById('searchBox').value.toLowerCase();
            let cards = document.getElementsByClassName('card');

            for (let i = 0; i < cards.length; i++) {
                let productName = cards[i].querySelector('h3').textContent.toLowerCase();

                if (productName.includes(input)) {
                    cards[i].style.display = "flex";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>


</head>
<body>
<a href="#" onclick="window.history.back();" class="back-button">Atrás</a>

<h2>Stock </h2>
<div class="search-container">
    <input type="text" id="searchBox" placeholder="Buscar producto..." oninput="searchProduct()">
</div>
<div class="card-container">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "admin";
    $dbName = "tpv";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $searchTerm = $_GET['search'] ?? '';
        $stmt = $conn->prepare("SELECT nombre, imagen, precio FROM productos WHERE nombre LIKE ?");
        $stmt->execute(["%{$searchTerm}%"]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='card'>";
            echo "<img src='/img/" . $row['imagen'] . "' alt='Imagen del producto'>";
            echo "<h3>" . $row['nombre'] . "</h3>";
            echo "<p>" . $row['precio'] . " €</p>";
            echo "</div>";
        }
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
    ?>
</div>
</body>
</html>
