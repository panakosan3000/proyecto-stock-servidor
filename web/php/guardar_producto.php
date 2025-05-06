 <?php
// *** Sólo en desarrollo; en producción desactiva display_errors en php.ini ***
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// indicar que la salida será JSON y evitar salidas previas
header('Content-Type: application/json; charset=utf-8');
ob_start();

session_start();
if (!isset($_SESSION['login'])) {
    ob_end_clean();
    echo json_encode(['success'=>false,'message'=>'No autorizado.']);
    exit;
}

// Recoger campos con isset() para evitar “Undefined index”
$name  = $_POST['name']        ?? '';
$brand = $_POST['brand']       ?? '';
$color = $_POST['color']       ?? '';
$size  = floatval($_POST['size']        ?? 0);
$price = floatval($_POST['price']       ?? 0);
$desc  = $_POST['description'] ?? '';

// Validaciones básicas
if ($name === '' || $brand === '' || $color === '' || $size <= 0 || $price <= 0) {
    ob_end_clean();
    echo json_encode(['success'=>false,'message'=>'Faltan campos obligatorios o valores no válidos.']);
    exit;
}

// Suprimir warnings de mysqli y conectar con root sin contraseña
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli("localhost", "root", "", "zapatostyle");
if ($conn->connect_error) {
    ob_end_clean();
    echo json_encode(['success'=>false,'message'=>'Error de conexión: '.$conn->connect_error]);
    exit;
}

// Preparar la consulta
$sql = "INSERT INTO products (name, brand, color, size, price, description) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    ob_end_clean();
    echo json_encode(['success'=>false,'message'=>'Error en prepare: '.$conn->error]);
    exit;
}

// Enlazar parámetros y ejecutar
$stmt->bind_param("sssdds", $name, $brand, $color, $size, $price, $desc);
if (!$stmt->execute()) {
    ob_end_clean();
    echo json_encode(['success'=>false,'message'=>'Error al ejecutar: '.$stmt->error]);
    exit;
}

$stmt->close();
$conn->close();

// Respuesta exitosa
ob_end_clean();
echo json_encode(['success'=>true,'message'=>'Producto guardado con éxito.']);
