function isProductsInCart() {
    let addToCartEls = document.getElementsByClassName('btn-outline-success');

    for (let el of addToCartEls) {
        let id = el.getAttribute('data-id');

        fetch(`/cart/is-product-in-cart/${id}`, {
            method: 'GET'
        }).then((response) => response.json())
            .then((responseData) => {
                el.classList.remove('btn-outline-success');
                el.removeAttribute('onclick');
                el.href = ('/cart')
                el.classList.add('btn-success');
                el.textContent = 'In cart';
            });
    }

}
