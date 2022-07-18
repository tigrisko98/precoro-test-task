const products = document.getElementById('products');

if (products) {
    products.addEventListener('click', (e) => {
        if (e.target.className === 'btn-outline-danger') {
            if (confirm('Are you sure you want to delete this product?')) {
                const id = e.target.getAttribute('data-id');

                fetch(`/product/${id}/delete`, {
                    method: 'DELETE'
                }).then(res => window.local.reload());
            }
        }
    });
}
