$(document).ready(function() {
    var max_fields = 5;
    var wrapper = $(".container1");
    var add_button = $(".add_form_field");

    var x = 1;
    $(add_button).click(function(e) {
        e.preventDefault();

        if (x <= 0) { x = 1; }

        if (x < max_fields) {
            x++;
            $(wrapper).append('<div class="input-group mt-3">' +
                '<span class="delete"><button class="btn btn-default delete btn-del" type="button">-</button></span>' +
                '<select name="testList[]" id="testList[]"  class="form-control custom-select">' +
                '<option value=""></option> ' +
                '@foreach ($testList as $tl) ' +
                '<option value="{{ $tl->id }}">{{ $tl->discipline }} ({{ $tl->typeTestName }} | {{ $tl->targetAudienceName }})</option>' +
                '@endforeach ' +
                '</select>' +
                '</div>');
        } else {
            alert('Добавлять больше нельзя!')
        }
    });

    $(wrapper).on("click", ".delete", function(e) {
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    })

});

function saveTestInfo(tid) {
    if ($.trim($('#discipline').val()) != '' &&
        $.trim($('#typeTest').val()) != '' &&
        $.trim($('#targetAudience').val()) != '' &&
        $.trim($('#validator').val()) != '' &&
        $.trim($('#max_ball').val()) != '' &&
        $.trim($('#min_ball').val()) != '' &&
        $.trim($('#count_question').val()) != '' &&
        $.trim($('#test_time').val()) != '') {
        $.ajax({
            url: '/info/save',
            type: 'POST',
            data: $('#testInfoForm').serialize(),
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
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
                if (data == -1) Command: toastr["error"]('Произошла ошибка при сохранении! Обратитесь к администратору.');
                else {
                    Command: toastr["success"]("Сохранено");
                    location.replace("/info?id=" + data);

                }
            },
            error: function(msg) {
                alert('Ошибка');
            }
        });
    } else alert('Заполните все поля!');
}

function loadUsers() {
    $.ajax({
        url: '/info/editors',
        type: 'POST',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        data: { tid: $("#tid").val() },
        success: function(html) {
            popup(html);
        }
    });
}

function popup(data) {
    Swal.fire({
        title: 'Редакторы теста',
        showCloseButton: false,
        html: data,
        showCancelButton: false,
        focusConfirm: true,
    }).then((result) => {
        var testJson = [];
        var i = 0;
        $('input[name="is_editor"]').each(function() {
            testJson[i] = { 'id': $(this).attr('id'), 'status': $(this).prop('checked'), 'tid': $('#tid').val() };
            i++;
        });
        saveTestEditors(testJson);
    })
}

function saveTestEditors(txt) {
    $.ajax({
        url: '/info/saveTestEditors',
        type: 'post',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "json",
        data: { testEditors: txt },
        success: function(data) {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
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
            if (data == -1) Command: toastr["error"]('Произошла ошибка при сохранении! Обратитесь к администратору.');
            else Command: toastr["success"]("Сохранено");
        },
    });
}

function tableSearch() {
    var phrase = document.getElementById('search-text');
    var table = document.getElementById('info-table');
    var regPhrase = new RegExp(phrase.value, 'i');
    var flag = false;
    for (var i = 1; i < table.rows.length; i++) {
        flag = false;
        for (var j = table.rows[i].cells.length - 1; j >= 0; j--) {
            flag = regPhrase.test(table.rows[i].cells[j].innerHTML);
            if (flag) break;
        }
        if (flag) {
            table.rows[i].style.display = "";
        } else {
            table.rows[i].style.display = "none";
        }

    }
}

function test_duplicate(id) {
    $.ajax({
        url: '/info/duplicate',
        type: 'post',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        data: { tid: id },
        success: function(data) {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
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
            if (data == -1) Command: toastr["error"]('Произошла ошибка при создании дубликата теста! Обратитесь к администратору.');
            else Command: toastr["success"]("Дубликат теста успешно создан!");
        },
    });
}