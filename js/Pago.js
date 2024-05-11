// Añadir productos al carrito y actualizar el total
function addToCart(productId) {
    let productElement = document.querySelector(`div.product[data-id='${productId}']`);
    let price = parseFloat(productElement.getAttribute('data-price'));
    let productName = productElement.querySelector('span').textContent;

    let cartItems = document.getElementById('cartItems');
    let newItem = document.createElement('div');
    newItem.textContent = productName + ' - ' + price.toFixed(2) + '€';
    cartItems.appendChild(newItem);

    updateTotal(price);
}

// Actualizar el total en la cesta
function updateTotal(price) {
    let totalDiv = document.getElementById('total');
    let currentTotal = parseFloat(totalDiv.textContent.replace('Total: ', '').replace('€', ''));
    currentTotal = (currentTotal + price).toFixed(2); // Asegura que el total siempre tiene dos decimales
    totalDiv.textContent = `Total: ${currentTotal}€`;
}

// Calcular el cambio a devolver al cliente y enviar los detalles de la compra
function simulatePayment() {
    let total = parseFloat(document.getElementById('total').textContent.replace('Total: ', '').replace('€', ''));
    let customerPayment = parseFloat(document.getElementById('customerPayment').value);
    if (isNaN(customerPayment) || customerPayment < total) {
        alert("Dinero insuficiente. Ingrese la cantidad correcta.");
        return;
    }
    let changeDue = customerPayment - total;

    // Preparar los datos del ticket para enviar al servidor
    let cartItems = document.querySelectorAll('#cartItems div');
    let products = Array.from(cartItems).map(item => {
        return {
            name: item.textContent.split(' - ')[0],
            price: parseFloat(item.textContent.split(' - ')[1].replace('€', ''))
        };
    });

    // Datos a enviar
    let data = {
        total: total.toFixed(2),
        paymentReceived: customerPayment.toFixed(2),
        changeGiven: changeDue.toFixed(2),
        products: products
    };

    // Enviar datos al servidor usando Fetch API
    fetch('process_payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Compra registrada con éxito.');
                resetTransaction(); // Reiniciar transacción después de registrar
            } else {
                alert('Error al registrar la compra.');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Resetear la transacción para el siguiente cliente
function resetTransaction() {
    document.getElementById('cartItems').innerHTML = ''; // Limpiar los artículos del carrito
    document.getElementById('total').textContent = 'Total: 0€';
    document.getElementById('customerPayment').value = ''; // Limpiar el campo de pago del cliente
    document.getElementById('changeDue').textContent = 'Cambio: 0€'; // Resetear el cambio mostrado
}
