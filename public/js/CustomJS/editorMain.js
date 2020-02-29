var firstLoad = true;

function searchTest() {
    $.ajax({
        url: '/editor/search',
        type: 'POST',

        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        data: $('#search').serialize(),
        success: function(html) {
            $('#testListAjax').html(html);
            if (firstLoad) firstLoad = false;
            else {
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-bottom-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "2000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                Command: toastr["success"]("Обновлено");
            }
        }
    });

}
searchTest();
setInterval(searchTest, 60000);

function supportPopup() {
    Swal.fire({
        title: 'Связь с отделом программирования',
        showCloseButton: false,
        html: '<div class="row">' +
            '<div class="col-md-12 mb-2"><b>Тема:</b></div>' +
            '<div class="col-md-12 mb-2"><input type="text" class="form-control" name="theme" id="theme"></div>' +
            '</div>' +
            '<div class="row">' +
            '<div class="col-md-12 mb-2"><b>Сообщение:</b></div>' +
            '<div class="col-md-12 mb-2"><textarea class="form-control" rows="3" name="texta" id="texta"></textarea></div>' +
            '</div>',
        showCancelButton: true,
        focusConfirm: true,
        cancelButtonText: 'Отмена',
        confirmButtonText: 'Отправить',
    }).then((result) => {
        sendReuqest();
    })
}

function sendReuqest() {
    var them = $("#theme").val();
    var txt = $("#texta").val();

    $.ajax({
        url: '/speedrequest',
        type: 'POST',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            theme: them,
            text: txt
        },
        success: function(data) {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            if (data == 0) Command: toastr["success"]('Запрос отправлен!');
            else Command: toastr["error"]('Ошибка при отправке запроса!');
        }
    });
}