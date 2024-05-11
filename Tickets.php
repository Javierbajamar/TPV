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
    <?php
    $conn = new PDO("mysql:host=localhost;dbname=tpv", 'root', 'admin');
    $stmt = $conn->query("SELECT * FROM tickets ORDER BY created_at DESC");

    while ($ticket = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='ticket'>";
        echo "<h2>Ticket #" . $ticket['ticket_id'] . " - " . $ticket['created_at'] . "</h2>";
        echo "<p>Total: " . $ticket['total'] . "€ - Pagado: " . $ticket['payment_received'] . "€ - Cambio: " . $ticket['change_given'] . "€</p>";
        echo "<div class='items'>";
        $itemStmt = $conn->prepare("SELECT p.nombre, ti.quantity, ti.unit_price FROM ticket_items ti JOIN productos p ON p.id = ti.product_id WHERE ti.ticket_id = ?");
        $itemStmt->execute([$ticket['ticket_id']]);
        while ($item = $itemStmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<p>" . $item['nombre'] . " x " . $item['quantity'] . " @ " . $item['unit_price'] . "€</p>";
        }
        echo "</div>";
        echo "</div>";
    }
    ?>
</div>
</body>
</html>
