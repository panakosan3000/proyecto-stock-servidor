 // validation.js

// Configurar validación en tiempo real
function setupRealTimeValidation() {
    const nameInput = document.getElementById('name');
    const brandInput = document.getElementById('brand');
    const colorInput = document.getElementById('color');
    const sizeInput = document.getElementById('size');
    const priceInput = document.getElementById('price');
    const stockInput = document.getElementById('stock');

    nameInput.addEventListener('input', validateName);
    brandInput.addEventListener('input', validateBrand);
    colorInput.addEventListener('input', validateColor);
    sizeInput.addEventListener('input', validateSize);
    priceInput.addEventListener('input', validatePrice);
    stockInput.addEventListener('input', validateStock);
}

function validateName() {
    const nameInput = document.getElementById('name');
    const nameError = document.getElementById('nameError');
    if (nameInput.value.trim() === '') {
        nameError.textContent = 'El nombre del producto es obligatorio';
        return false;
    } else {
        nameError.textContent = '';
        return true;
    }
}

function validateBrand() {
    const brandInput = document.getElementById('brand');
    const brandError = document.getElementById('brandError');
    if (brandInput.value.trim() === '') {
        brandError.textContent = 'La marca es obligatoria';
        return false;
    } else {
        brandError.textContent = '';
        return true;
    }
}

function validateColor() {
    const colorInput = document.getElementById('color');
    const colorError = document.getElementById('colorError');
    if (colorInput.value.trim() === '') {
        colorError.textContent = 'El color es obligatorio';
        return false;
    } else {
        colorError.textContent = '';
        return true;
    }
}

function validateSize() {
    const sizeInput = document.getElementById('size');
    const sizeError = document.getElementById('sizeError');
    if (sizeInput.value.trim() === '' || isNaN(sizeInput.value) || sizeInput.value <= 0) {
        sizeError.textContent = 'Debe introducir una talla válida';
        return false;
    } else {
        sizeError.textContent = '';
        return true;
    }
}

function validatePrice() {
    const priceInput = document.getElementById('price');
    const priceError = document.getElementById('priceError');
    if (priceInput.value.trim() === '' || isNaN(priceInput.value) || priceInput.value <= 0) {
        priceError.textContent = 'Debe introducir un precio válido';
        return false;
    } else {
        priceError.textContent = '';
        return true;
    }
}

function validateStock() {
    const stockInput = document.getElementById('stock');
    const stockError = document.getElementById('stockError');
    if (stockInput.value.trim() === '' || isNaN(stockInput.value) || stockInput.value < 0) {
        stockError.textContent = 'Debe introducir un stock válido (0 o más)';
        return false;
    } else {
        stockError.textContent = '';
        return true;
    }
}

// Validar el formulario completo
function validateForm() {
    const isNameValid = validateName();
    const isBrandValid = validateBrand();
    const isColorValid = validateColor();
    const isSizeValid = validateSize();
    const isPriceValid = validatePrice();
    const isStockValid = validateStock();

    return isNameValid && isBrandValid && isColorValid && isSizeValid && isPriceValid && isStockValid;
}

// Enviar el formulario
function submitForm() {
    const form = document.getElementById('addProductForm');
    const formData = new FormData(form);

    fetch('php/add_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const formResult = document.getElementById('formResult');
        formResult.style.display = 'block';

        if (data.success) {
            formResult.className = 'form-result success';
            formResult.textContent = data.message;
            form.reset();
        } else {
            formResult.className = 'form-result error';
            formResult.textContent = data.message;
        }

        setTimeout(() => {
            formResult.style.display = 'none';
        }, 5000);
    })
    .catch(error => {
        console.error('Error:', error);
        const formResult = document.getElementById('formResult');
        formResult.style.display = 'block';
        formResult.className = 'form-result error';
        formResult.textContent = 'Hubo un error al enviar el formulario.';
    });
}
