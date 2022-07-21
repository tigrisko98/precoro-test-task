function recalculateTotalPrice() {
    let totalPrice = document.getElementById('total-price');

    fetch('/cart/get-total-price', {
        method: 'GET'
    }).then((response) => response.json())
        .then((responseData) => {
            totalPrice.innerText = `${responseData}`;
        });
}
