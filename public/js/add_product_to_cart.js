function addProductToCart(id) {
    let cartQuantityEl = document.getElementById('cart-quantity');
    let cartEl = document.getElementById(`add-to-cart-${id}`);
    let formData = new FormData();
    let quantityEl = document.getElementById(`quantity-${id}`);
    let quantity = 1;

    if (quantityEl) {
        quantity = quantityEl.textContent;
    }
    formData.append('quantity', quantity);

    fetch(`/cart/add/${id}`, {
        method: 'POST',
        body: formData,
    }).then((response) => response.json())
        .then((responseData) => {
            cartQuantityEl.textContent = ` (${responseData})`;
            cartEl.classList.remove('btn-outline-success');
            cartEl.removeAttribute('onclick');
            cartEl.classList.add('btn-success');
            cartEl.href = ('/cart')
            cartEl.textContent = 'In cart';
        });
}
