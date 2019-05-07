var accordions = document.getElementsByClassName("accordion")
console.log(accordions)
for (var i = 0; i < accordions.length; ++i) {
    accordions[i].addEventListener("click", function () {
       
        var content = this.nextElementSibling;
        console.log(content);
        if (content.style.height) {
            content.style.height = null;
        } else {
            content.style.height = "100%";
        }
    });

}