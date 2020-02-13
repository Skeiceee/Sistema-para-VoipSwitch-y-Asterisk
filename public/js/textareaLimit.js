var textarea = document.querySelector("textarea");
var counter = document.querySelector("#counter")
textarea.addEventListener("input", function(){
    var maxlength = this.getAttribute("maxlength");
    var currentLength = this.value.length;
    if( currentLength >= maxlength ){
        counter.innerHTML = "Has alcanzado el número máximo de caracteres.";
    }else{
        counter.innerHTML = "Quedan " + (maxlength - currentLength) + " caracteres";
    }
});