 <?php
session_start();

// Verificación de sesión: solo usuarios autenticados
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto - JJStyle</title>
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
                    <li><a href="add_product.php" class="active">Agregar Producto</a></li>
                    <li><a href="sobre_nosotros.html">Sobre Nosotros</a></li>
                    <li><a href="contacto.html">Contacto</a></li>
					<?php if (isset($_SESSION['login'])): ?>
        <li><a href="logout.php">Cerrar Sesión</a></li>
    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <section class="add-product-section">
        <div class="container">
            <h2 class="section-title">Agregar Nuevo Producto</h2>
            <div class="form-container">
                <form id="addProductForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Nombre del Producto: <span class="required">*</span></label>
                        <input type="text" id="name" name="name" required>
                        <small class="error-message" id="nameError"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="brand">Marca: <span class="required">*</span></label>
                        <input type="text" id="brand" name="brand" required>
                        <small class="error-message" id="brandError"></small>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group half">
                            <label for="color">Color: <span class="required">*</span></label>
                            <input type="text" id="color" name="color" required>
                            <small class="error-message" id="colorError"></small>
                        </div>
                        
                        <div class="form-group half">
                            <label for="size">Talla: <span class="required">*</span></label>
                            <input type="number" id="size" name="size" step="0.5" min="20" max="50" required>
                            <small class="error-message" id="sizeError"></small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Precio (€): <span class="required">*</span></label>
                        <input type="number" id="price" name="price" step="0.01" min="0.01" required>
                        <small class="error-message" id="priceError"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Imagen del Producto:</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <small class="help-text">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 5MB</small>
                        <small class="error-message" id="imageError"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Descripción:</label>
                        <textarea id="description" name="description" rows="4"></textarea>
                        <small class="error-message" id="descriptionError"></small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Guardar Producto</button>
                        <button type="reset" class="btn btn-outline">Cancelar</button>
                    </div>
                </form>

                <div id="formResult" class="form-result"></div>
            </div>
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
                    <h4>Enlaces </h4>
                    <ul>
                        <li><a href="../index.html">Inicio</a></li>
                        <li><a href="../sobre_nosotros.html">Sobre Nosotros</a></li>
                        <li><a href="../contacto.html">Contacto</a></li>
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

<script src="js/validation.js"></script>
<script>
  // Menú móvil
  document.getElementById('menuToggle').addEventListener('click', function() {
    document.querySelector('.nav-list').classList.toggle('active');
  });

  // Envío del formulario
  document.addEventListener('DOMContentLoaded', function() {
    const addProductForm = document.getElementById('addProductForm');
    if (!addProductForm) return;

    addProductForm.addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(addProductForm);

      fetch('php/guardar_producto.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())              // <-- leer como texto crudo
      .then(text => {
        console.log("RESPUESTA CRUDA:", text);        // <-- ver en consola qué llega
        try {
          const data = JSON.parse(text);               // <-- intentar parsear JSON
          const resultDiv = document.getElementById('formResult');
          resultDiv.textContent = data.message;
          resultDiv.className = 'form-result ' + (data.success ? 'success' : 'error');
          if (data.success) addProductForm.reset();
        } catch (e) {
          console.error("NO ES JSON VÁLIDO:", e);
          // aquí podrías informar al usuario de un error inesperado
        }
      })
      .catch(error => {
        console.error("Error de fetch:", error);
      });
    });
  });
</script>
</body>
</html>
