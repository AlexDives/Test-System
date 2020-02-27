/*var table = Quill.import('modules/table');
Quill.register(table, true);*/
/*import QuillBetterTable from 'quill-better-table'

Quill.register({
    'modules/better-table': QuillBetterTable
}, true)*/

var toolbarOptions = [
    ['bold', 'italic', 'underline'], // toggled buttons

    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'align': [] }],
    [{ 'script': 'sub' }, { 'script': 'super' }], // superscript/subscript

    [{ 'size': ['small', false, 'large', 'huge'] }], // custom dropdown
    /*[{ 'header': [1, 2, 3, 4, 5, 6, false] }],*/

    /*[{ 'color': [] }, { 'background': [] }],    */ // dropdown with defaults from theme
    /*[{ 'font': [] }],*/
    ['table'],
    ['image'],
    ['clean'] // remove formatting  button
];

var quillQuestion = new Quill('.question', { modules: { toolbar: toolbarOptions }, theme: 'snow' });
var quillAnswerTrue = new Quill('#answerTrue', { modules: { toolbar: toolbarOptions }, theme: 'snow' });
var quillanswerFalse1 = new Quill('#answerFalse1', { modules: { toolbar: toolbarOptions }, theme: 'snow' });
var quillanswerFalse2 = new Quill('#answerFalse2', { modules: { toolbar: toolbarOptions }, theme: 'snow' });
var quillanswerFalse3 = new Quill('#answerFalse3', { modules: { toolbar: toolbarOptions }, theme: 'snow' });

const maxQuestion = 60;
var currentQuestion = 0;
var ballMass = [];

function ajaxQuestList(test_id) {
    $.ajax({
        url: '/questions/loadList',
        type: 'POST',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        data: { tid: test_id },
        success: function(data) {
            if (data == -1) alert('Произошла ошибка при загрузке списка вопросов, повторите попытку!');
            else {
                loadQuestList(data);
            }
        },
        error: function(msg) {
            alert('Ошибка');
        }
    });
}

function loadQuestList(data) {
    if (currentQuestion < maxQuestion) {
        data.forEach(element => {
            currentQuestion++;
            ballMass[element["id"]] = element["ball"];
            $('.li').append('<div class="s-ask page-item "><a class="page-link item" href="#" onclick="selectedQuest(' + element["id"] + ', this);">' + currentQuestion + '</a></div>');
        });
        if (currentQuestion != 0) {
            document.querySelector('.li').children[0].classList.add('active');
            $('.s-ask').filter('.active').children(".item").click();

            /*$('.li').animate({
                scrollLeft: '+=100'
            }, 300, 'swing');*/
        } else if (currentQuestion == 0) newQuest();
    } else {
        alert('Максимальное количество ворпосов в тесте 60!');
    }
}


function clearQuest() {
    $('#idQuest').val('0');
    $('#ballQuest').val('');
    $('#textQuest').val('');
    $('#asnwerTrueQuest').val('');
    $('#asnwerFalse1Quest').val('');
    $('#asnwerFalse2Quest').val('');
    $('#asnwerFalse3Quest').val('');
    $('.ql-editor').text('');
}

function newQuest() {
    if (currentQuestion < maxQuestion) {
        currentQuestion++;

        $('.li').append('<div class="s-ask page-item "><a class="page-link item" href="#" onclick="selectedQuest(0, this);">' + currentQuestion + '</a></div>');

        const wrapObj = document.querySelector('.li');
        var lastIndex = 0;
        for (let i = 0; i < wrapObj.children.length; i++) {
            wrapObj.children[i].classList.remove('active');
            lastIndex = i;
        }
        wrapObj.children[lastIndex].classList.add('active');

        $('.li').animate({
            scrollLeft: '+=100'
        }, 300, 'swing');

        clearQuest();
    } else {
        alert('Максимальное количество ворпос в тесте 60!');
    }

}

function findRow(node) {
    var i = 1;
    while (node = node.previousSibling) {
        if (node.nodeType === 1) {++i }
    }
    return i;
}

// var count = findRow(document.getElementById('activeQuest'));

var firstClick = true;

function selectedQuest(qid, obj) {
    if (!firstClick) {

    } else firstClick = false;
    const wrapObj = document.querySelector('.li');
    var lastIndex = 0;
    for (let i = 0; i < wrapObj.children.length; i++) {
        wrapObj.children[i].classList.remove('active');
        lastIndex = i;
    }
    obj.parentNode.classList.add('active');

    if (qid > 0) {
        $.ajax({
            url: '/questions/selectedQuest',
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: qid },
            success: function(data) {
                if (data == -1) alert('Произошла ошибка при загрузке списка вопросов, повторите попытку!');
                else {
                    data.forEach(element => {
                        switch (element['type']) {
                            case 'que':
                                document.querySelector("#question .ql-editor").innerHTML = element["text"];
                                break;
                            case 'cor':
                                document.querySelector("#answerTrue .ql-editor").innerHTML = element["text"];
                                break;
                            case 'dis1':
                                document.querySelector("#answerFalse1 .ql-editor").innerHTML = element["text"];
                                break;
                            case 'dis2':
                                document.querySelector("#answerFalse2 .ql-editor").innerHTML = element["text"];
                                break;
                            case 'dis3':
                                document.querySelector("#answerFalse3 .ql-editor").innerHTML = element["text"];
                                break;
                        }
                    });
                    $('#idQuest').val(qid);
                    $('#ballQuest').val(ballMass[qid]);
                }
            },
            error: function(msg) {
                alert('Ошибка');
            }
        });
    } else { clearQuest(); }
}

function saveQuest() {

    var a1 = document.querySelector("#question .ql-editor").innerHTML;
    var a2 = document.querySelector("#answerTrue .ql-editor").innerHTML;
    var a3 = document.querySelector("#answerFalse1 .ql-editor").innerHTML;
    var a4 = document.querySelector("#answerFalse2 .ql-editor").innerHTML;
    var a5 = document.querySelector("#answerFalse3 .ql-editor").innerHTML;
    if (a1 != '<p><br></p>' && a2 != '<p><br></p>' && a3 != '<p><br></p>' && a4 != '<p><br></p>') {
        $('#textQuest').val(a1);
        $('#answerTrueQuest').val(a2);
        $('#answerFalse1Quest').val(a3);
        $('#answerFalse2Quest').val(a4);
        $('#answerFalse3Quest').val(a5);

        $.ajax({
            url: '/questions/save',
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: $('#questionForm').serialize(),
            success: function(data) {
                if (data == -1) alert('Произошла ошибка при сохранении данного вопроса, повторите попытку!');
                else {
                    $('#idQuest').val(data);
                    ballMass[data] = $('#ballQuest').val();
                    $('.s-ask').filter('.active').children(".item").attr('onclick', "selectedQuest(" + data + ", this);");
                    $("#okImg").fadeIn(500);
                    $("#okImg").delay(500).fadeOut(500);
                }
            },
            error: function(msg) {
                alert('Ошибка');
            }
        });
    }
}

function deleteQuest() {
    var qid = $('#idQuest').val();
    $.ajax({
        url: '/questions/deleteQuest',
        type: 'POST',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id: qid },
        success: function(data) {
            if (data == -1) alert('Произошла ошибка при удалении вопроса, повторите попытку!');
            else {

                ballMass[qid] = null;
                var indexRem = $('.s-ask').filter('.active').index();
                if (currentQuestion - 1 == indexRem) indexRem = 0;
                $('.s-ask').filter('.active').remove();
                currentQuestion--;
                if (currentQuestion <= 0) {
                    currentQuestion = 0;
                    newQuest();
                } else {
                    document.querySelector('.li').children[indexRem].classList.add('active');
                    $('.s-ask').filter('.active').children(".item").click();


                    const wrapObj = document.querySelector('.li');
                    for (var i = indexRem; i < wrapObj.children.length; i++) {
                        wrapObj.children[i].children[0].innerHTML = i + 1;
                    }
                }
                //$('.s-ask').children(".item").val(0);
            }
        },
        error: function(msg) {
            alert('Ошибка');
        }
    });
}