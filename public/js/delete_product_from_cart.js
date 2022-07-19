function deleteProductFromCart(id) {
    fetch(`/cart/delete/${id}`, {}).then(() => {
        location.reload();
    });
}
