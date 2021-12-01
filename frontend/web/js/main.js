$(document).ready(function () {
    if ($('#wrap-background').hasClass('site-login') || $('#wrap-background').hasClass('site-signup')) {
        $('.wrap').addClass('wrap-background');
        $('.wrap').removeClass('bg-light');
    } else {

        $('.wrap').addClass('bg-light');
    }

    $('.post__author--link').click(function (e) {
        e.preventDefault()
    });

});