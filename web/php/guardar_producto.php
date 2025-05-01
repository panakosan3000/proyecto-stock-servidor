 <?php
// guardar_producto.php

header('Content-Type: application/json');

// Conexión a la base de datos
require_once 'db_init.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $color = trim($_POST['color']);
    $size  = intval($_POST['size']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']); // NUEVO

    // Validación básica
    if (empty($name) || empty($brand) || empty($color) || $size <= 0 || $price <= 0 || $stock < 0) {
        $response['message'] = 'Todos los campos son obligatorios y deben ser válidos.';
        echo json_encode($response);
        exit;
    }

    // Procesar imagen si se subió
    $imageName = 'default_shoe.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $uploadPath = '../images/' . $imageName;

        if (!move_uploaded_file($imageTmpPath, $uploadPath)) {
            $response['message'] = 'Error al guardar la imagen.';
            echo json_encode($response);
            exit;
        }
    }

    // Insertar en base de datos
    $stmt = $conn->prepare("INSERT INTO products (name, brand, color, size, price, image, stock) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdsi", $name, $brand, $color, $size, $price, $imageName, $stock);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Producto guardado correctamente.';
    } else {
        $response['message'] = 'Error al guardar en la base de datos: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
