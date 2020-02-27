$(document).ready(function () {
    var max_fields = 5;
    var wrapper = $(".container1");
    var add_button = $(".add_form_field");

    var x = 1;
    $(add_button).click(function (e) {
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
        }
        else {
            alert('Добавлять больше нельзя!')
        }
    });

    $(wrapper).on("click", ".delete", function (e) {
        e.preventDefault(); $(this).parent('div').remove(); x--;
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
            success: function (data) {
                if (data == -1) alert('Произошла ошибка при сохранении! Обратитесь к администратору.');
                else document.location.href = "/info?id=" + data;
            },
            error: function (msg) {
                alert('Ошибка');
            }
        });
    } else alert('Заполните все поля!');
}