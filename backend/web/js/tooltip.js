$(function () {
    $("[data-toggle='tooltip']").tooltip();

});
$(function(){
    $(window).scroll(function() {
        let height =$(window).height();
        let heightDoc = $(document).height();
        console.log(height);
        console.log($(document).height());
        if(heightDoc == height*4){
            alert( '4 экрана' );

        }
    });
});