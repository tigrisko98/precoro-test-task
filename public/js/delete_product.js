function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch(`/product/${id}/delete`, {
            method: 'DELETE'
        }).then(() => {
            //location.reload();
        });
        deleteProductFromCart(id);
    }
}

function deleteProductFromCart(id) {
    fetch(`/cart/delete/${id}`, {}).then(() => {
        //location.reload();
    });
}

