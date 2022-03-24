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
// Block button when submitting a form
    $(document).on('pjax:send', function() {
        $(".spinner-border").removeClass('d-none');
        $('button[type=submit]').attr('disabled', true).addClass('disabled');
    });

    $(document).on('pjax:complete', function() {
        $(".spinner-border").addClass('d-none');
        $('button[type=submit]').attr('disabled', false).removeClass('disabled');
    });


});