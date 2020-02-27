/* загрузка выбранного пользователя */
function user_change(uid, login, famil, name, otch, workplace, doljn, aud_num, tel_num, rid, is_block, secret_question, secret_answer, userStatus) {
    var self = $('.uid-' + uid);
    var table = self.closest('table');
    var activeRow = table.data('active-row');
    if (activeRow) {
        activeRow.removeClass('active');
    }
    activeRow = self.closest('tr');
    table.data('active-row', activeRow);
    activeRow.addClass('active');

    $('#login').val(login);
    $('#uid').val(uid);
    $('#famil').val(famil);
    $('#name').val(name);
    $('#otch').val(otch);
    $('#secret_question').val(secret_question);
    $('#secret_answer').val(secret_answer);
    $('#workplace').val(workplace);
    $('#doljn').val(doljn);
    $('#aud_num').val(aud_num);
    $('#tel_num').val(tel_num);
    $('#roles').val(rid);
    if (is_block == 'F')
        $('#is_block').prop('checked', true);
    else
        $('#is_block').prop('checked', false);
}
/************************************/

/* новый пользователь */
function newUser() {
    var self = $('.user-row');
    var table = self.closest('table');
    var activeRow = table.data('active-row');
    if (activeRow) {
        activeRow.removeClass('active');
    }
    $('#uid').val(-1);
    $('#login').val("");
    $('#famil').val("");
    $('#name').val("");
    $('#otch').val("");
    $('#secret_question').val("");
    $('#secret_answer').val("");
    $('#workplace').val("");
    $('#doljn').val("");
    $('#aud_num').val("");
    $('#tel_num').val("");
    $('#roles').val("");
    $('#is_block').prop('checked', false);
}
/**********************/

/* сохранить пользователя */
function saveUser() {
    $.ajax({
        url: '/manager/save',
        type: 'POST',
        data: $('#userform').serialize(),
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            if (data == -1) alert('Произошла ошибка при сохранении! Обратитесь к администратору.');
            else loadUserTable();
        },
        error: function(msg) {
            alert('Ошибка');
        }
    });
}
/* сохранить пользователя */

/* удалить пользователя */
function deleteUser() {
    $.ajax({
        url: '/manager/delete',
        type: 'POST',
        data: $('#userform').serialize(),
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            loadUserTable();
        },
        error: function(msg) {
            alert('Ошибка');
        }
    });
}
/************************/

function loadUserTable() {
    $.ajax({
        url: '/manager/loadUserTable',
        type: 'GET',
        success: function(html) {
            $('#userTable').html(html);
        }
    });
}
loadUserTable();
setInterval(loadUserTable, 60000);