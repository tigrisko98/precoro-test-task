function deleteProductFromCart(id) {
    fetch(`/cart/delete/${id}`, {}).then(response => window.local.reload());
}
