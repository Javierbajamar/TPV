<?php
// Configuración de la conexión a la base de datos
$conn = new PDO("mysql:host=localhost;dbname=tpv", 'root', 'admin');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Manejo del formulario de guardado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $imagen = '';

    if (!empty($_FILES['imagen_file']['name'])) {
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["imagen_file"]["name"]);
        if (move_uploaded_file($_FILES["imagen_file"]["tmp_name"], $target_file)) {
            $imagen = basename($_FILES["imagen_file"]["name"]);
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        $imagen = $_POST['imagen_url'];
    }

    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        $stmt = $conn->prepare("UPDATE productos SET nombre = ?, imagen = ?, precio = ? WHERE id = ?");
        $stmt->execute([$nombre, $imagen, $precio, $_POST['product_id']]);
    } else {
        $stmt = $conn->prepare("INSERT INTO productos (nombre, imagen, precio) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $imagen, $precio]);
    }

    header('Location: manage_products.php');
    exit();
}

// Manejo de eliminación de productos
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: manage_products.php');
    exit();
}

// Consulta para obtener todos los productos
$stmt = $conn->query("SELECT * FROM productos");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Productos</title>
    <link rel="stylesheet" href="css/ManageProducts.css">
</head>
<body>
<h1>Gestionar Productos</h1>
<div class="container">
    <a href="Main.html" class="back-button">Atrás</a>

    <form action="manage_products.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : '' ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?= isset($_GET['edit']) ? htmlspecialchars($productos[array_search($_GET['edit'], array_column($productos, 'id'))]['nombre']) : '' ?>" required>

        <label for="imagen">Imagen (URL o archivo):</label>
        <input type="text" id="imagen_url" name="imagen_url" placeholder="URL de la imagen">
        <input type="file" id="imagen_file" name="imagen_file">

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" id="precio" name="precio" value="<?= isset($_GET['edit']) ? htmlspecialchars($productos[array_search($_GET['edit'], array_column($productos, 'id'))]['precio']) : '' ?>" required>

        <button type="submit" name="save">Guardar</button>
    </form>

    <h2>Lista de Productos</h2>
    <table>
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Imagen</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                <td>
                    <?php if (filter_var($producto['imagen'], FILTER_VALIDATE_URL)): ?>
                        <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" width="100">
                    <?php else: ?>
                        <img src="img/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" width="100">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($producto['precio']) ?>€</td>
                <td>
                    <a href="manage_products.php?edit=<?= $producto['id'] ?>">Editar</a>
                    <a href="manage_products.php?delete=<?= $producto['id'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
