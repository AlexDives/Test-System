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
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

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
            Command: toastr["success"]("Обновлено");
        }
    });
}
searchTest();
setInterval(searchTest, 60000);