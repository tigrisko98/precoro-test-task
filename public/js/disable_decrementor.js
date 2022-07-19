let decrementEls = document.getElementsByClassName('decrement');

function disableDecrementor() {
    for (el of decrementEls) {
        let quantity = el.getAttribute('data-quantity');

        if (quantity == 1) {
            el.disabled = true;
        }
    }
}
