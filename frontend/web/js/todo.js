// Updating the contents of the container after adding an entry
$("#new_todo").on("pjax:end", function() {
    $.pjax.reload({container:"#todo-list"});
    $.pjax.reload({container:"#pager"});
});

// Refresh the contents of the container after pressing the pagination buttons
$("#pager").on("pjax:end", function() {
    $.pjax.reload({container:"#todo-list"});
});

// Reinitializing the TodoList plugin after updating the container
$(document).on("ready pjax:end", function(event) {
    $(event.target).TodoList();
})

// Loading spinner (AdminLte3)
$('#overlay').hide();

// Get the form
let getForm = function(id) {
    return $('.card').find( 'form[id='+ id +'] ');
}


// Request to change the status when selecting a checkbox

let toggleCheckbox = function(){
    $('.card').on('click',' input:checkbox', function(e){

        let id = $(this).attr('id')
        let name = $(this).attr('name')
        let val = $(this).val();
        let form = getForm(id);

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: {[name]: val},
            beforeSend: function() { $('#overlay').show(); },
            complete: function() { $('#overlay').hide(); },
            success: function(data){
            }
        });
    });
}
toggleCheckbox();

// Request to delete a record by clicking on an icon

let deleteItemTodo = function (){
    $('.card').on('click','.fa-trash',function(){

        let id = $(this).attr('data-id');

        let form = getForm(id);

        $.ajax({
            url: basePath + '/todo/todo/' + 'delete?id=' + id,
            method: form.attr('method'),
            beforeSend: function() { $('#overlay').show(); },
            complete: function() { $('#overlay').hide(); },
            success: function(data){
                $.pjax.reload({container:'#todo-list'});
            }
        });
    });
}

deleteItemTodo();

// Request to edit an entry by clicking on the icon

let updateItemTodo = function () {
    $('.card').on('click','.fa-edit',function(e){

        let id = $(this).attr('data-id');

        let input = $('.card').find('input[type=text][id='+ id +'] ');

        if( input.hasClass('no-input-style') ){
            input.removeClass('no-input-style');
            input.focus();
        }else {
            input.addClass('no-input-style');
        }

        let form = getForm(id);

        input.blur( function() {

            $.ajax({
                url: form.attr('action') ,
                method: form.attr('method'),
                data:form.serialize(),
                beforeSend: function() { $('#overlay').show(); },
                complete: function() { $('#overlay').hide(); },
                success: function(data){
                    $.pjax.reload({container:'#todo-list'});
                }
            });


        });
    });
}

updateItemTodo();

$(window).keydown(function(event){
    if(event.keyCode === 13) {
        event.preventDefault();
        return false;
    }
});