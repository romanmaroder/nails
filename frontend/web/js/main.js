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


$('.post__author--link').click(function(e){e.preventDefault()});


    const postImg = $('.post__description p > img:first-child');
    const postList = $('.post__description ol > li > img');

    if (postImg) {
        postImg.parent().addClass('post__img-inner');
    }
    if (postList) {
        var block = $('<span></span>');
        $('.post__description ol>li').prepend(block);
        $('.post__description ol>li >span').append(postList);
        postList.parent().addClass('post__img-list');
    }

});