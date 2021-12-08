$(document).ready(function () {
    if ($('#wrap-background').hasClass('site-login') || $('#wrap-background').hasClass('site-signup')) {
        $('.wrap').addClass('wrap-background');
    }

    $('.post__author--link').click(function (e) {
        e.preventDefault()
    });


    if ($('body').hasClass('dark-mode')) {


        $("#checkTheme").attr('checked', 'checked');

        // Switching the color scheme of customer cards
        $('.card').each(function (index, data) {
            $(data).removeClass('bg-light');
            $(data).addClass('bg-dark');
        });

        // Switching the color scheme of the button up
        $('#btn-back-to-top').removeClass('btn-dark');
        $('#btn-back-to-top').addClass('btn-light');

    }

});


