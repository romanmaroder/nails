//Get the button
let mybutton = document.getElementById("btn-back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {
    if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
    ) {
        mybutton.style.display = "block";
        mybutton.classList.remove('no-animate');
        mybutton.classList.add('animate');
    } else if(document.body.scrollTop === 0 ||
        document.documentElement.scrollTop === 0) {
        mybutton.classList.remove('animate');
        mybutton.classList.add('no-animate');
    }
}
// When the user clicks on the button, scroll to the top of the document
mybutton.addEventListener("click", backToTop);

function backToTop() {
    $('html, body').animate({
        scrollTop: $('body').offset().top
    }, 800);
}