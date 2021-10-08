$(document).ready(function () {

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


    $('.post__author--link').click(function (e) {
        e.preventDefault()
    });

    /*const postWrapp = $('.post__description p ');
    const postImg = $('.post__description p > span > span > img');
    const postImgFirst = $('.post__description p:first-child > span > span > img');
    const postList = $('.post__description ol > li > span > img');
    let modific = '';

    if (postImg) {
        //postImg.parent().addClass('image-inner');
       // postImg.parent().parent().addClass('image-wrapper');
        postImg.parent().addClass('post__img--first image-decor');
        postImg.parent().parent().addClass('post__img-wrap--first');
        //postWrapp.addClass('d-flex justify-content-between');
    }
    if (postImgFirst && $('.post__description > p:first-child > span > span')) {
        modific = '--first';
        postImgFirst.parent().addClass('post__img' + modific);
        postImgFirst.parent().addClass('image-decor');
        postImgFirst.parent().parent().addClass('post__img-wrap' + modific);
        //postImgFirst.parent().removeClass('transform-border');
        postImgFirst.parent().removeClass('image-inner');
        postImgFirst.parent().parent().removeClass('image-wrapper');
        //postImgFirst.removeClass('d-flex justify-content-between');
    } else {
        postImgFirst.parent().addClass('post__img');
    }
    if (postList) {
        modific = '--list'
        postList.parent().addClass('post__img' + modific);
    }*/

});