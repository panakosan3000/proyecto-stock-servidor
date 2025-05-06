 <?php
session_start();

// Si ya está logueado, redirige directamente
if (isset($_SESSION['login'])) {
    header("Location: add_product.php");
    exit();
}

$error = "";

// Procesar login si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';

    // Sustituir por validación real desde base de datos si lo deseas
    if ($usuario === "admin" && $clave === "1234") {
        $_SESSION['login'] = $usuario;
        header("Location: add_product.php");
        exit();
    } else {
        $error = "Usuario o clave incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - JJStyle</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <header class="main-header">
        <div class="container">
            <div class="logo">
                <h1>JJStyle</h1>
            </div>
            <nav class="main-nav">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="nav-list">
                    <li><a href="index.html">Inicio</a></li>
                    <li><a href="add_product.php">Agregar Producto</a></li>
                    <li><a href="sobre_nosotros.html">Sobre Nosotros</a></li>
                    <li><a href="contacto.html">Contacto</a></li>
                    <li><a href="login.php" class="active">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="add-product-section">
        <div class="container form-container">
            <h2 class="section-title">Iniciar Sesión</h2>

            <?php if ($error): ?>
                <div class="form-result error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
				<form method="post" class="login-form">
					<div class="form-group">
						<label for="usuario">Usuario:</label>
						<input type="text" name="usuario" id="usuario" required>
					</div>
					<div class="form-group">
						<label for="clave">Contraseña:</label>
						<input type="password" name="clave" id="clave" required>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">Entrar</button>
					</div>
				</form>

        </div>
    </section>

    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h3>JJStyle</h3>
                    <p>Tu tienda de confianza para calzado de calidad</p>
                </div>
                <div class="footer-links">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="index.html">Inicio</a></li>
                        <li><a href="sobre_nosotros.html">Sobre Nosotros</a></li>
                        <li><a href="contacto.html">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-social">
                      <h4>Síguenos</h4>
                    <div class="social-icons">
                        <a href="https://es-es.facebook.com/" aria-label="Facebook">
						  <i class="fab fa-facebook-f" aria-hidden="true"></i>
						</a>
						<a href="https://www.instagram.com/" aria-label="Instagram">
						  <i class="fab fa-instagram" aria-hidden="true"></i>
						</a>
						<a href="https://x.com/" aria-label="Twitter">
						  <i class="fab fa-twitter" aria-hidden="true"></i>
						</a>
						<a href="https://app.slack.com/client/T08LQQ6AADV/C08M3ABM3HB" aria-label="Pinterest">
						  <i class="fab fa-pinterest" aria-hidden="true"></i>
						</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 JJStyle. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
