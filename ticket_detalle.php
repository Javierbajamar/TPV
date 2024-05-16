<!-- ticket_detalle.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Ticket</title>
    <link rel="stylesheet" href="css/Tickets.css">
</head>
<body>
<h1>Detalle del Ticket</h1>
<div class="ticket-detail">
    <a href="Main.html" class="back-button">Atrás</a>

    <?php
    if (isset($_GET['ticket_id'])) {
        $ticket_id = $_GET['ticket_id'];
        try {
            $conn = new PDO("mysql:host=localhost;dbname=tpv", 'root', 'admin');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM tickets WHERE ticket_id = ?");
            $stmt->execute([$ticket_id]);
            $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($ticket) {
                echo "<div class='ticket'>";
                echo "<h2>Ticket #" . $ticket['ticket_id'] . " - " . $ticket['created_at'] . "</h2>";
                echo "<p>Total: " . $ticket['total'] . "€ - Pagado: " . $ticket['payment_received'] . "€ - Cambio: " . $ticket['change_given'] . "€</p>";
                echo "<div class='items'>";
                $itemStmt = $conn->prepare("SELECT p.nombre, ti.quantity, ti.unit_price FROM ticket_items ti JOIN productos p ON p.id = ti.product_id WHERE ti.ticket_id = ?");
                $itemStmt->execute([$ticket_id]);
                while ($item = $itemStmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<p>" . $item['nombre'] . " x " . $item['quantity'] . " @ " . $item['unit_price'] . "€</p>";
                }
                echo "</div>";
                echo "</div>";
            } else {
                echo "<p>Ticket no encontrado.</p>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "<p>No se ha proporcionado un ID de ticket.</p>";
    }
    ?>

    <button onclick="window.print()">Imprimir</button>
</div>

</body>
</html>
