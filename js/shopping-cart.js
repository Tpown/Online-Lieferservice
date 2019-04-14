if (document.readyState == 'loading') {
    document.addEventListener('DOMContentLoaded', ready)
} else {
    ready();
}

function ready() {
    var addItemBtn = document.getElementsByClassName("btn btn-primary")
    for(var i = 0; i < addItemBtn.length; ++i){
        var button = addItemBtn[i]
        button.addEventListener('click', addToCartClick)
    }
    console.log(addItemBtn)

    var quantityInputs = document.getElementsByClassName('cart-quantity-input')
    for(var i = 0; i < quantityInputs.length; ++i){
        var input = quantityInputs[i]
        input.addEventListener('change', quantityChanged)
    }
}

function quantityChanged(event){
    var input = event.target
    if(isNaN(input.value) || input.value <= 0){
        input.value = 1
    }
}

var title = document.getElementsByClassName('product-title')[1].innerText
console.log(title);

function addToCartClick(event) {
    var button = event.target
    var product = button.parentElement.parentElement
    var title = product.getElementsByClassName('product-title')[0].innerText
    console.log(title);

}

