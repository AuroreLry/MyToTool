$(document).ready(function() {
    $('#create_list').click(function () {
        var nom = $('#list_name').val();
        if (nom !== '') {
            $.ajax({
                url: '/list',
                type: "POST",
                data: {nom: nom},
                success: function (data) {

                }
            })
        }
    })
})

$(document).ready(function() {
    $('#create_task').click(function (e) {
        var nom = $('#task_name').val();
        var priority = $('#task_priority').find(":selected").val();
        var liste = $('#task_list').find(":selected").val();
        if (nom !== '') {
            $.ajax({
                url: '/task',
                type: "POST",
                data: {nom: nom, priority: priority, liste: liste},
                success: function (data)  {

                }
            })
        }
    })
})

$(document).ready(function() {
    $('.supprimerliste').click(function (e) {
        let listId = $(this).attr('data-listId')
        if (listId !== '') {
            $.ajax({
                url: '/list',
                type: "DELETE",
                data: {listId: listId},
                success: function (data) {
                    $(location).attr('href', '/home');


                }
            })
        }
    })
})

$(document).ready(function() {
    $('.supprimerTache').click(function (e) {
        let taskId = $(this).attr('data-taskId')
        if (taskId !== '') {
            $.ajax({
                url: '/task',
                type: "DELETE",
                data: {taskId: taskId},
                success: function (data) {
                    $(`#li-${taskId}`).remove();

                }
            })
        }
        e.preventDefault()
    })
})

$(document).ready(function() {
    $('.checkbox_task').each(function () {
        this.addEventListener('change', function () {
            $.ajax({
                url: '/task',
                type: "PATCH",
                data: {done: this.checked, taskId: this.value},
                success: function (data)  {

                }
            });
        });
    })
})