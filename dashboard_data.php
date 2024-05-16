<?php
header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=localhost;dbname=tpv", 'root', 'admin');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Total ventas de hoy
    $stmt = $conn->prepare("SELECT SUM(total) as total_ventas FROM tickets WHERE DATE(created_at) = CURDATE()");
    $stmt->execute();
    $ventas_hoy = $stmt->fetch(PDO::FETCH_ASSOC)['total_ventas'] ?? 0;

    // Total ventas del mes
    $stmt = $conn->prepare("SELECT SUM(total) as total_ventas FROM tickets WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
    $stmt->execute();
    $ventas_mes = $stmt->fetch(PDO::FETCH_ASSOC)['total_ventas'] ?? 0;

    // Productos más vendidos
    $stmt = $conn->prepare("
        SELECT p.nombre, SUM(ti.quantity) as cantidad 
        FROM ticket_items ti 
        JOIN productos p ON p.id = ti.product_id 
        GROUP BY ti.product_id 
        ORDER BY cantidad DESC 
        LIMIT 5");
    $stmt->execute();
    $productos_mas_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ingresos diarios de los últimos 7 días
    $stmt = $conn->prepare("
        SELECT DATE(created_at) as fecha, SUM(total) as total_ingresos 
        FROM tickets 
        WHERE created_at >= CURDATE() - INTERVAL 7 DAY 
        GROUP BY DATE(created_at)");
    $stmt->execute();
    $ingresos_diarios_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = [];
    $data = [];
    foreach ($ingresos_diarios_data as $row) {
        $labels[] = $row['fecha'];
        $data[] = $row['total_ingresos'];
    }

    // Datos para enviar
    $response = [
        'ventas_hoy' => $ventas_hoy,
        'ventas_mes' => $ventas_mes,
        'productos_mas_vendidos' => $productos_mas_vendidos,
        'ingresos_diarios' => [
            'labels' => $labels,
            'data' => $data
        ]
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
