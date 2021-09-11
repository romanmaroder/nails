//Get the button
let mybutton = document.getElementById("btn-back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {
    if (
        document.body.scrollTop > (document.body.offsetHeight / 2) ||
        document.documentElement.scrollTop > (document.documentElement.offsetHeight / 2)
    ) {
        mybutton.style.display = "block";
        mybutton.animate([
            // keyframes
            //{ transform: 'translate3D(0, 0, 0)' },
           //{ transform: 'translate3D(0, -300px, 0)' },
            {width:'40px'},
            {height:'40px'},
            {backgroundColor:'#007bff'},
            {opacity:'1'},
        ], {
            // timing options
            duration: 3000,
            iterations: Infinity
        })
    } else {
        mybutton.animate([
            // keyframes
            //{ transform: 'translate3D(0, 0, 0)' },
            //{ transform: 'translate3D(0, -300px, 0)' },
            {borderColor:'transparent'},
            {backgroundColor:'transparent'},
            {opacity:'0'},
            //{width:'0px'},
            //{height:'0px'},
            //{display:'none'},
        ], {
            // timing options
            duration: 3000,
            //iterations: Infinity
        })
        //mybutton.style.display = "none";
    }
}
// When the user clicks on the button, scroll to the top of the document
mybutton.addEventListener("click", backToTop);

function backToTop() {
    $('html, body').animate({
        scrollTop: $('body').offset().top
    }, 800);
    //document.body.scrollTop = 0;
    //document.documentElement.scrollTop = 0;
}