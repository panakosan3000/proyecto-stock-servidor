  document.addEventListener('DOMContentLoaded', function () {
    // Inicializar el menú móvil
    const menuToggle = document.getElementById('menuToggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function () {
            document.querySelector('.nav-list').classList.toggle('active');
        });
    }

    // Cargar productos con filtros
    loadProducts();

    // Inicializar botones de filtro
    const applyFiltersBtn = document.getElementById('applyFilters');
    const resetFiltersBtn = document.getElementById('resetFilters');

    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function () {
            loadProducts();
        });
    }

    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function () {
            document.getElementById('brandFilter').value = '';
            document.getElementById('colorFilter').value = '';
            document.getElementById('sizeFilter').value = '';
            const stockCheckbox = document.getElementById('stockFilter');
            if (stockCheckbox) stockCheckbox.checked = false;
            loadProducts();
        });
    }

    // Inicializar formulario de contacto
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formResult = document.createElement('div');
            formResult.className = 'form-result success';
            formResult.textContent = 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.';

            const existingResult = document.querySelector('.form-result');
            if (existingResult) existingResult.remove();

            contactForm.parentNode.appendChild(formResult);
            contactForm.reset();

            setTimeout(() => {
                formResult.style.display = 'none';
            }, 5000);
        });
    }
});

// Cargar productos desde el servidor

function loadProducts() {
    console.log("🟢 loadProducts() iniciada");

    const testElement = document.getElementById('productsList');
    if (!testElement) {
        console.error("❌ No se encontró el elemento #productsList en el DOM");
        return;
    } else {
        console.log("✅ Elemento #productsList encontrado correctamente");
    }

    const productsList = document.getElementById('productsList');
    if (!productsList) return;

    const brandFilter = document.getElementById('brandFilter').value;
    const colorFilter = document.getElementById('colorFilter').value;
    const sizeFilter = document.getElementById('sizeFilter').value;
    const stockFilter = document.getElementById('stockFilter')?.checked;

    productsList.innerHTML = '<div class="loading">Cargando productos...</div>';

    let url = 'php/get_products.php';
    const params = [];

    if (brandFilter) params.push(`brand=${encodeURIComponent(brandFilter)}`);
    if (colorFilter) params.push(`color=${encodeURIComponent(colorFilter)}`);
    if (sizeFilter) params.push(`size=${encodeURIComponent(sizeFilter)}`);
    if (stockFilter) params.push(`stock=1`);

    if (params.length > 0) {
        url += '?' + params.join('&');
    }

    
    console.log("📡 Realizando petición fetch a:", url);
    fetch(url)

        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar los productos');
            }
            return response.json();
        })
        
.then(data => {
    console.log("📦 Datos recibidos del servidor:", data);
    if (!data || typeof data !== "object") {
        console.error("❌ Respuesta no válida del servidor");
        return;
    }
    if (!Array.isArray(data.products)) {
        console.warn("⚠️ 'products' no es un array:", data.products);
    } else if (data.products.length === 0) {
        console.warn("⚠️ 'products' está vacío");
    } else {
        console.log(`✅ ${data.products.length} productos recibidos`);
    }
			console.log("✅ Ejecutando displayProducts con productos:", data.products);

            updateFilterOptions(data.filters);
            displayProducts(data.products);
        })
        
.catch(error => {
    console.error("❌ Error en fetch o en el procesamiento de datos:", error);

            productsList.innerHTML = `<div class="error">Error al cargar los productos: ${error.message}</div>`;
        });
}

// Actualizar filtros dinámicos
function updateFilterOptions(filters) {
    const brandFilter = document.getElementById('brandFilter');
    const colorFilter = document.getElementById('colorFilter');
    const sizeFilter = document.getElementById('sizeFilter');

    if (brandFilter && filters.brands) {
        const selected = brandFilter.value;
        while (brandFilter.options.length > 1) brandFilter.remove(1);
        filters.brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand;
            option.textContent = brand;
            if (brand === selected) option.selected = true;
            brandFilter.appendChild(option);
        });
    }

    if (colorFilter && filters.colors) {
        const selected = colorFilter.value;
        while (colorFilter.options.length > 1) colorFilter.remove(1);
        filters.colors.forEach(color => {
            const option = document.createElement('option');
            option.value = color;
            option.textContent = color;
            if (color === selected) option.selected = true;
            colorFilter.appendChild(option);
        });
    }

    if (sizeFilter && filters.sizes) {
        const selected = sizeFilter.value;
        while (sizeFilter.options.length > 1) sizeFilter.remove(1);
        filters.sizes.forEach(size => {
            const option = document.createElement('option');
            option.value = size;
            option.textContent = size;
            if (size == selected) option.selected = true;
            sizeFilter.appendChild(option);
        });
    }
}

// Mostrar los productos en la página
function displayProducts(products) {
		console.log("👟 displayProducts() recibió:", products);
    const productsList = document.getElementById('productsList');
    
    const productTemplate = document.getElementById('productTemplate');
    if (!productTemplate) {
        console.error("❌ No se encontró el template con id 'productTemplate'");
        return;
    } else {
        console.log("✅ Template 'productTemplate' encontrado correctamente");
    }


    if (!productsList || !productTemplate) return;
    productsList.innerHTML = '';

    if (products.length === 0) {
        productsList.innerHTML = '<div class="no-results">No se encontraron productos con los filtros seleccionados.</div>';
        return;
    }

    
    console.log("🔄 Procesando productos...");
    products.forEach((product, i) => {
        console.log(`📦 Producto [${i}]:`, product);

        const productCard = document.importNode(productTemplate.content, true);

        const imgElement = productCard.querySelector('.product-image img');
        imgElement.src = product.image && product.image !== 'default_shoe.jpg'
            ? 'images/' + product.image
            : 'https://via.placeholder.com/300x200?text=Zapato';
        imgElement.alt = product.name;

        productCard.querySelector('.product-name').textContent = product.name;
        productCard.querySelector('.product-brand').textContent = product.brand;
        productCard.querySelector('.product-color').textContent = product.color;
        productCard.querySelector('.product-size').textContent = `Talla ${product.size}`;
        productCard.querySelector('.product-price').textContent = `${Number(product.price).toFixed(2)} €`;
		console.log(product);
        const stockElement = productCard.querySelector('.product-stock');
       if (stockElement) {
    if (parseInt(product.stock) === 0) {
        stockElement.textContent = 'Sin stock';
        stockElement.style.color = '#b50000';
        stockElement.style.fontWeight = 'bold';
    } else {
        stockElement.textContent = `Stock: ${product.stock}`;
        stockElement.style.color = 'green';
    }
}


        const button = productCard.querySelector('.product-btn');
        button.dataset.productId = product.id;
        button.addEventListener('click', function () {
            alert(`Ver detalles del producto: ${product.name}`);
        });

        productsList.appendChild(productCard);
    });
}
