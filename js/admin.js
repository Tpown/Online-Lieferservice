var accordions = document.getElementsByClassName("accordion")
console.log(accordions)
for (var i = 0; i < accordions.length; ++i) {
    accordions[i].addEventListener("click", function () {
       
        var content = this.nextElementSibling;
        console.log(content);
        if (content.style.maxHeight) {
            content.style.maxHeight = null;
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
        }
    });

}