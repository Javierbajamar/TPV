<?php
$servername = "localhost";
$username = "root";
$password = "admin";
$dbName = "tpv";

// Conectar a la base de datos
$conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Obtener los datos JSON enviados desde el cliente
$data = json_decode(file_get_contents("php://input"), true);

// Preparar la inserción en la tabla de tickets
$stmt = $conn->prepare("INSERT INTO tickets (total, payment_received, change_given) VALUES (?, ?, ?)");
$stmt->execute([$data['total'], $data['paymentReceived'], $data['changeGiven']]);
$ticketId = $conn->lastInsertId();

// Preparar la inserción en la tabla de ticket_items
foreach ($data['products'] as $product) {
    $stmt = $conn->prepare("INSERT INTO ticket_items (ticket_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
    // Aquí se asume que tienes el product_id, ajusta según tu diseño de base de datos
    $stmt->execute([$ticketId, $product['id'], 1, $product['price']]);
}

echo json_encode(['success' => true]);
