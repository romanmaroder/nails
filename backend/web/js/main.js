$( document ).ready(function() {
    if ($('body').hasClass('dark-mode')){
        $("#checkTheme").attr('checked','checked');

        $('.card').each(function(index,data) {
            $(data).removeClass('bg-light');
            $(data).addClass('bg-dark');
        });

        $('.navbar').removeClass('navbar-white');
        $('.navbar').addClass('navbar-dark');
    }else{

    }
});