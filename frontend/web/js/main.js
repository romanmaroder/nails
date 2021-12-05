$(document).ready(function () {
    if ($('#wrap-background').hasClass('site-login') || $('#wrap-background').hasClass('site-signup')) {
        $('.wrap').addClass('wrap-background');
    }

    $('.post__author--link').click(function (e) {
        e.preventDefault()
    });




    if ($('body').hasClass('dark-mode')){
        $("#checkTheme").attr('checked','checked');

        $('.card').each(function(index,data) {
            $(data).removeClass('bg-light');
            $(data).addClass('bg-dark');
        });

        $('#modal, #event-form,.select2-container--krajee-bs4 .select2-selection').each(function(index,data) {
            $(data).css('background-color','#343a40');

        });
    }else{

    }

});