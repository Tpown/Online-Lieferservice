if (document.readyState == 'loading') {
    document.addEventListener('DOMContentLoaded', ready)
} else {
    ready();
}

function ready() {
    var addItemBtn = document.getElementsByClassName("btn btn-primary")
    for (var i = 0; i < addItemBtn.length; ++i) {
        var button = addItemBtn[i]
        button.addEventListener('click', addToCartClick)
    }
    //console.log(addItemBtn)



    var quantityInputs = document.getElementsByClassName('cart-quantity-input')
    for (var i = 0; i < quantityInputs.length; ++i) {
        var input = quantityInputs[i]
        input.addEventListener('change', quantityChanged)
    }
}

function removeItem(event){
    var button = event.target
    button.parentElement.parentElement.remove()
    updateCartTotal()
}

function quantityChanged(event) {
    var input = event.target
    if (isNaN(input.value) || input.value <= 0) {
        input.value = 1
    }
    updateCartTotal()
}

function addToCartClick(event) {
    var button = event.target
    var product = button.parentElement.parentElement
    var title = product.getElementsByClassName('product-title')[0].innerText
    /*let node = document.getElementById('preis')
    let price = node.dataset.price;
    console.log(price) */
    var price = product.getElementsByClassName('cart-price')[0].innerText
    var quantity = product.getElementsByClassName('cart-quantity-input')[0].value
    addItemToCart(title, price, quantity);

    console.log(price);
    console.log(title);
    console.log(quantity);

    updateCartTotal()

}

function addItemToCart(title, price, quantity) {
    var cartrow = document.createElement('div')
    cartrow.classList.add('cart-row')
    var cartList = document.getElementsByClassName('cart-list')[0]
    var cartItemNames = cartList.getElementsByClassName('product-title')

    for(var i = 0; i < cartItemNames.length; ++i){
        if(cartItemNames[i].innerText == title){
            alert('Produkt existiert schon')
            return
        }
    }
    
    var cartRowContents = `<div class="cart-item cart-column">
    <span class="product-title">${title}</span>
</div>
<span class="cart-price cart-column" data-price="1.50">${price}</span>
<div class="cart-quantity cart-column">
    <input class="cart-quantity-input" type="number" value=${quantity}>
    <button class="btn btn-danger" type="button"> X </button>
</div>`
    cartrow.innerHTML = cartRowContents
    cartList.append(cartrow)
    cartrow.getElementsByClassName('btn-danger')[0].addEventListener('click', removeItem)

    var quantityInputs = document.getElementsByClassName('cart-quantity-input')
    for (var i = 0; i < quantityInputs.length; ++i) {
        var input = quantityInputs[i]
        input.addEventListener('change', quantityChanged)
    }
   
}

function updateCartTotal() {
    var cartItemContainer = document.getElementsByClassName('cart-list')[0]
    var cartRows = cartItemContainer.getElementsByClassName('cart-row')
    var total = 0
    for (var i = 0; i < cartRows.length; i++) {
        var cartRow = cartRows[i]
        var priceElement = cartRow.getElementsByClassName('cart-price')[0]
        var quantityElement = cartRow.getElementsByClassName('cart-quantity-input')[0]
        var price = parseFloat(priceElement.innerText.replace('€', ''))
        var quantity = quantityElement.value
        total = total + (price * quantity)
    }
    total = Math.round(total * 100) / 100
    document.getElementsByClassName('cart-total-price')[0].innerText = total + '€'
    document.getElementsByClassName('cart-total-price-input')[0].setAttribute("value", total)
}

