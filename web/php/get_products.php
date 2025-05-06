 <?php
header('Content-Type: application/json');
require_once 'config.php'; // conexión limpia

// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Recoger filtros desde GET
$brand = $_GET['brand'] ?? '';
$color = $_GET['color'] ?? '';
$size  = isset($_GET['size']) ? intval($_GET['size']) : 0;
$stockFilter = isset($_GET['stock']) && $_GET['stock'] == 1;

// Construcción dinámica de condiciones
$conditions = [];
$params = [];
$types = "";

if (!empty($brand)) {
    $conditions[] = "brand = ?";
    $params[] = $brand;
    $types .= "s";
}
if (!empty($color)) {
    $conditions[] = "color = ?";
    $params[] = $color;
    $types .= "s";
}
if ($size > 0) {
    $conditions[] = "size = ?";
    $params[] = $size;
    $types .= "i";
}
if ($stockFilter) {
    $conditions[] = "stock > 0";
}

$whereClause = "";
if (count($conditions) > 0) {
    $whereClause = "WHERE " . implode(" AND ", $conditions);
}

// Consulta SQL segura
$query = "SELECT * FROM products $whereClause ORDER BY name ASC";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
    exit;
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];
$brands = [];
$colors = [];
$sizes  = [];

while ($row = $result->fetch_assoc()) {
    $products[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'brand' => $row['brand'],
        'color' => $row['color'],
        'size' => $row['size'],
        'price' => $row['price'],
        'image' => $row['image'],
        'stock' => $row['stock']
    ];

    if (!in_array($row['brand'], $brands)) $brands[] = $row['brand'];
    if (!in_array($row['color'], $colors)) $colors[] = $row['color'];
    if (!in_array($row['size'], $sizes)) $sizes[] = $row['size'];
}

sort($brands);
sort($colors);
sort($sizes, SORT_NUMERIC); // ← ordena las tallas de menor a mayor

echo json_encode([
    'products' => $products,
    'filters' => [
        'brands' => $brands,
        'colors' => $colors,
        'sizes' => $sizes
    ]
]);


$stmt->close();
$conn->close();
