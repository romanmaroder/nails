$( document ).ready(function() {
    if ($('body').hasClass('dark-mode')){
        $("#checkTheme").attr('checked','checked');

        // Switching the color scheme of customer cards
        $('.card').each(function(index,data) {

            $(data).removeClass('bg-light');
            $(data).addClass('bg-dark');
        });

        // Switching the color scheme of the navigation bar
        $('.navbar').removeClass('navbar-white');
        $('.navbar').addClass('navbar-dark');
    }else{

    }
});