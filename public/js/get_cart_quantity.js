function getCardQuantity() {
    let cartEl = document.getElementById('cart-quantity');

    fetch(`/cart/get-quantity`, {
        method: 'GET'
    }).then((response) => response.json())
        .then((responseData) => {
            cartEl.textContent = ` (${responseData})`;
        });
}

