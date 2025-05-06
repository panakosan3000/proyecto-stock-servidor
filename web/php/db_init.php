 <?php
$servername = "localhost";
$username = "root";
$password = "";

// Crear conexión
$conn = new mysqli($servername, $username, $password);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Crear base de datos
$sql = "CREATE DATABASE IF NOT EXISTS zapatostyle";
if ($conn->query($sql) === TRUE) {
    echo "Base de datos creada correctamente<br>";
} else {
    echo "Error al crear la base de datos: " . $conn->error . "<br>";
}

// Seleccionar base de datos
$conn->select_db("zapatostyle");

// Crear tabla de productos
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    color VARCHAR(30) NOT NULL,
    size FLOAT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabla 'products' creada correctamente<br>";
} else {
    echo "Error al crear la tabla: " . $conn->error . "<br>";
}

// Eliminar productos anteriores
$conn->query("DELETE FROM products");

// Insertar productos
$sql = "INSERT INTO products (name, brand, color, size, price, image, description) VALUES
('Handball Special', 'Adidas', 'Azul', 42, 110.00, 'adidas1.jpg', 'Zapatillas deportivas para hombre.'),
('SL 72 Shoes', 'Adidas', 'Rojo', 41, 100.00, 'adidas2.jpg', 'Zapatillas deportivas para hombre.'),
('Garelle Indoor', 'Adidas', 'Gris', 43, 120.00, 'adidas3.jpg', 'Zapatillas deportivas para hombre.'),
('Breaknet Sleek', 'Adidas', 'Blanco', 38, 60.00, 'adidas4.jpg', 'Zapatillas femeninas cómodas.'),
('Forum Low', 'Adidas', 'Negro', 40, 66.00, 'adidas5.jpg', 'Zapatillas femeninas clásicas.'),
('Air Force 1\'07', 'Nike', 'Blanco', 42, 119.99, 'nike1.jpg', 'Zapatillas deportivas para hombre.'),
('Air Max Plus 06', 'Nike', 'Azul', 43, 189.99, 'nike2.jpg', 'Zapatillas deportivas para hombre.'),
('Air Max 95', 'Nike', 'Gris', 44, 189.99, 'nike3.jpg', 'Zapatillas deportivas para hombre.'),
('Zoom Vomero 5', 'Nike', 'Rosa', 39, 159.99, 'nike4.jpg', 'Zapatillas deportivas para mujer.'),
('Shox 72', 'Nike', 'Negro', 40, 169.99, 'nike5.jpg', 'Zapatillas deportivas para mujer.'),
('Novablast 5', 'Asics', 'Azul', 42, 150.00, 'asics1.jpg', 'Zapatillas técnicas para hombre.'),
('Metaspeed Edge Paris', 'Asics', 'Rojo', 41, 250.00, 'asics2.jpg', 'Zapatillas técnicas para hombre.'),
('Gel-Kayano 3', 'Asics', 'Negro', 43, 200.00, 'asics3.jpg', 'Zapatillas técnicas para hombre.'),
('S4+ Yogiri', 'Asics', 'Beige', 39, 210.00, 'asics4.jpg', 'Zapatillas de running para mujer.'),
('Gel-Nimbus 27', 'Asics', 'Blanco', 38, 200.00, 'asics5.jpg', 'Zapatillas de running para mujer.'),
('Speedcat 06', 'Puma', 'Rojo', 42, 140.00, 'puma1.jpg', 'Zapatillas deportivas para hombre.'),
('Doublecount', 'Puma', 'Negro', 41, 52.00, 'puma2.jpg', 'Zapatillas deportivas para hombre.'),
('Puma rt8', 'Puma', 'Azul', 40, 39.00, 'puma3.jpg', 'Zapatillas deportivas para hombre.'),
('Rs-x Reinvention', 'Puma', 'Blanco', 39, 110.00, 'puma4.jpg', 'Zapatillas deportivas para mujer.'),
('Road Rider', 'Puma', 'Gris', 38, 68.00, 'puma5.jpg', 'Zapatillas deportivas para mujer.')
";

if ($conn->query($sql) === TRUE) {
    echo "Nuevos productos insertados correctamente<br>";
} else {
    echo "Error al insertar productos: " . $conn->error . "<br>";
}

echo "<p>Inicialización completada. <a href='index.php'>Volver al inicio</a></p>";

$conn->close();
?>
