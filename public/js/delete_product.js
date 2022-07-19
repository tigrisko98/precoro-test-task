function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch(`/cart/delete/${id}`, {})
            .then(response => response.json())
            .then(json => {
                fetch(`/product/${id}/delete`, {
                    method: 'DELETE'
                }).then(response => response.json())
                    .then(json => {
                    });
            });
    }
}
