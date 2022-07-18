function addProductToCart(id) {
    fetch(`/cart/add/${id}`, {
        method: 'POST'
    });
}
