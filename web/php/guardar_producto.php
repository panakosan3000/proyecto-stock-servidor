 <?php
// Mostrar errores solo en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Salida JSON
header('Content-Type: application/json; charset=utf-8');
ob_start();

session_start();
if (!isset($_SESSION['login'])) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'No autorizado.']);
    exit;
}

// Recoger campos
$name  = $_POST['name'] ?? '';
$brand = $_POST['brand'] ?? '';
$color = $_POST['color'] ?? '';
$size  = floatval($_POST['size'] ?? 0);
$price = floatval($_POST['price'] ?? 0);
$desc  = $_POST['description'] ?? '';
$stock = intval($_POST['stock'] ?? -1);  // ← Añadido stock

// Validación básica
if ($name === '' || $brand === '' || $color === '' || $size <= 0 || $price <= 0 || $stock < 0) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios o valores no válidos.']);
    exit;
}

// Procesar imagen si existe
$imageName = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../images/';
    $originalName = basename($_FILES['image']['name']);
    $imageName = preg_replace('/\s+/', '_', $originalName);
    $uploadPath = $uploadDir . $imageName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'No se pudo guardar la imagen.']);
        exit;
    }
}

// Conectar a la base de datos
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli("localhost", "root", "", "zapatostyle");
if ($conn->connect_error) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]);
    exit;
}

// Insertar producto incluyendo imagen y stock
$sql = "INSERT INTO products (name, brand, color, size, price, image, description, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Error en prepare: ' . $conn->error]);
    exit;
}

$stmt->bind_param("sssddssi", $name, $brand, $color, $size, $price, $imageName, $desc, $stock);
if (!$stmt->execute()) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Error al ejecutar: ' . $stmt->error]);
    exit;
}

// Éxito
$stmt->close();
$conn->close();
ob_end_clean();
echo json_encode(['success' => true, 'message' => 'Producto guardado con éxito.']);
