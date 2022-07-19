function increment(id) {
    let quantity = document.getElementById(`quantity-${id}`).innerText;
    quantity++;
    if (quantity == 2) {
        let decrementEl = document.getElementById(`decrement-${id}`);
        decrementEl.disabled = false;
    }
    document.getElementById(`quantity-${id}`).innerText = quantity;
}

function decrement(id) {
    let quantity = document.getElementById(`quantity-${id}`).innerText;
    quantity--;
    if (quantity == 1) {
        let decrementEl = document.getElementById(`decrement-${id}`);
        decrementEl.disabled = true;
    }
    document.getElementById(`quantity-${id}`).innerText = quantity;
}
