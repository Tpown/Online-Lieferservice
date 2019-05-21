function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "top-navbar") {
      x.className += " responsive";
    } else {
      x.className = "top-navbar";
    }
  }
  
  
  function getOrder(orderID) {
    "use strict";
    console.log("Hello Cheese");
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://127.0.0.1/Webseite/CustomerStatus.php');

    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            // console.log(response);
            var filtered_response = response.filter(function (id) {
                //   console.log(orderID);
                return id.ID == orderID;
            })
            console.log(filtered_response);

            remove_allChilds();
            status_title(orderID);
            getCustomerInfo(orderID);

            for (var index = 0; index < filtered_response.length; ++index) {
                insert_order_items(filtered_response[index].Quantity, filtered_response[index].Title, index, filtered_response[index].Status, filtered_response[index].ProductID);
            }
        }

    }
    xhr.send();
}

function getCustomerInfo(orderID){
    "use strict";
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://127.0.0.1/Webseite/CustomerInfos.php');

    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            //console.log(response);
            var filtered_response = response.filter(function (id) {
                //   console.log(orderID);
                return id.ID == orderID;
            })
            console.log(filtered_response);

            var address = document.createElement("h6");
            var addresscontent = document.createTextNode(filtered_response[0].address +", " + filtered_response[0].zip + " " +filtered_response[0].city);
            address.appendChild(addresscontent);

            var name = document.createElement("h6");
            var namecontent = document.createTextNode(filtered_response[0].surname + " " + filtered_response[0].lastname);
            name.appendChild(namecontent);
            
            var statusTitle = document.getElementById("status-title");
            statusTitle.appendChild(name);
            statusTitle.appendChild(address);
        }

    }
    xhr.send();
}


function status_title(orderID) {
    "use strict";
    var statusTitle = document.getElementById("status-title");

    // console.log(statusTitle);
    var product = document.getElementById("order-item-status");

    var parent = statusTitle.parentElement;
    statusTitle.remove();
    var newH4 = document.createElement("h4");
    newH4.setAttribute("id", "status-title");
    var newContent = document.createTextNode(`Status: #${orderID}  Bestellung`);
    newH4.appendChild(newContent);

    parent.insertBefore(newH4, product);
    
    var newForm = document.createElement("form");
    newForm.setAttribute("action", "Delivery.php");
    newForm.setAttribute("id", "formid");
    newForm.setAttribute("method", "POST");
    product.appendChild(newForm);

    var inputSession = document.createElement("input");
    inputSession.setAttribute("type", "hidden");
    inputSession.setAttribute("name", "Bestellung");
    inputSession.setAttribute("value", orderID);
    newForm.appendChild(inputSession);
}

function insert_order_items(quantity, product, index, status, productID) {
    "use strict";
    var newH6 = document.createElement("h6");
    newH6.setAttribute("id", "product");
    var description = document.createTextNode(`Anzahl: ${quantity}     |     Produkt: ${product}`);
    newH6.appendChild(description);
    var order_status = document.getElementById("formid");
    order_status.appendChild(newH6);

    var newUl = document.createElement("ul");
    newUl.setAttribute("class", "checkbox-items");
    newUl.setAttribute("id", "checkbox-items-id");
    order_status.appendChild(newUl);


    var tmp = index * 4;
    for (var i = 3; i < 6; ++i) {
        if (i == 3) {
            tmp += 1;
            if(status == i){
                insert_li("Bereit zur Lieferung", tmp, index, 1, i, productID);
            }else{
                insert_li("Bereit zur Lieferung", tmp, index, 0, i, productID); 
            }   
        }
        if (i == 4) {
            tmp += 1;
            if(status == i){
                insert_li("Wird zugestellt", tmp, index, 1, i, productID);
            }else{
                insert_li("Wird zugestellt", tmp, index, 0 , i, productID); 
            }     
        }
        if (i == 5) {
            tmp += 1;
            if(status == i){
                insert_li("Ist zugestellt", tmp, index, 1, i, productID);
            }else{
                insert_li("Ist zugestellt", tmp, index, 0, i, productID); 
            }   
        }
    }


}


function insert_li(text, tmp, index, csk, inputVal, productID) {
    "use strict";
    var count = tmp;
    var newLi = document.createElement("li");
    var p_ul = document.getElementsByClassName("checkbox-items")[index];
    p_ul.appendChild(newLi);

    var newInput = document.createElement("input");
    newInput.type = "radio";
    newInput.name = `item${productID}`;
    newInput.id = "checkbox" + count;
    newInput.value = inputVal;
    if(csk == 1){
        newInput.checked = "checked";
    }
    newInput.setAttribute("onclick", "document.forms[\"formid\"].submit()");

    var newLabel = document.createElement("label");
    newLabel.className = "status";
    newLabel.setAttribute("for", "checkbox" + count);

    var status_order_txt = document.createTextNode(text);

    newLabel.appendChild(status_order_txt);
    newLi.appendChild(newInput);
    newLi.appendChild(newLabel);
}

function remove_allChilds() {
    "use strict";
    var node = document.getElementById("order-item-status");
    while (node.hasChildNodes()) {
        node.removeChild(node.firstChild);
    }
}


