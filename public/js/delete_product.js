function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch(`/product/${id}/delete`, {
            method: 'DELETE'
        }).then(response => window.local.reload());
    }
}
