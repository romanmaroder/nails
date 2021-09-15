$( document ).ready(function() {

   /* $('#calendar').fullCalendar({
        dayClick: function(date, jsEvent, view) {

            alert('Clicked on: ' + date.format());

            alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

            alert('Current view: ' + view.name);

            // change the day's background color just for fun
            $(this).css('background-color', 'red');

        }
    });*/


    /*$(document).on('click touchstart', '.fc-day', function () {
        let date = $(this).attr('data-date');

        $.get('/event/create',{'date':date},function (data) {
            $('#modal').modal('show')
                .find('#modalContent')
                .html(data)
        })


    });*/

    /*$('#modalButton').click(function () {
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'))
    })*/


$('.post__author--link').click(function(e){e.preventDefault()})

});