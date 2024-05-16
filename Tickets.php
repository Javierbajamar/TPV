<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tickets de Compra</title>
    <link rel="stylesheet" href="css/Tickets.css">
</head>
<body>
<h1>Tickets de Compra</h1>
<div class="tickets">
    <a href="Main.html" class="back-button">Atrás</a>

    <?php
    try {
        $conn = new PDO("mysql:host=localhost;dbname=tpv", 'root', 'admin');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->query("SELECT * FROM tickets ORDER BY created_at DESC");

        while ($ticket = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='ticket'>";
            echo "<h2>Ticket #" . $ticket['ticket_id'] . " - " . $ticket['created_at'] . "</h2>";
            echo "<p>Total: " . $ticket['total'] . "€ - Pagado: " . $ticket['payment_received'] . "€ - Cambio: " . $ticket['change_given'] . "€</p>";
            echo "<a href='ticket_detalle.php?ticket_id=" . $ticket['ticket_id'] . "' class='view-button'>Ver Detalle e Imprimir</a>";
            echo "</div>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>
</div>
</body>
</html>
