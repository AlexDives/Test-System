function fullscreen(element) {
    if (element.requestFullScreen) {
        element.requestFullScreen();
    } else if (element.mozRequestFullScreen) {
        element.mozRequestFullScreen();
    } else if (element.webkitRequestFullScreen) {
        element.webkitRequestFullScreen();
    }
}

function timerPause() {
    i = 10;

    let timerId = setInterval(() => {
        var text = 'Да';
        text = text + ' (' + i + ')';
        $('.swal2-confirm').html(text);
        i--;
    }, 1000);

    setTimeout(() => {
        clearInterval(timerId);
        $('.swal2-confirm').click();
    }, 12000);
}

function fastStop() {
    Swal.fire({
        title: '<center style="color:red;">В Н И М А Н И Е</center>',
        html: '<lable style="color:#326521;">Вы хотите прервать тестирование?<br>' +
            'Повторное прохождение тестирования не допускается!<br>' +
            'Результаты Вашей работы фиксируются на сервере.</lable>',
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
    }).then((result) => {
        if (result.value) {
            var csrf = $('#csrf').html();
            let form = document.createElement('form');
            form.action = '/test/result';
            form.method = 'POST';
            form.innerHTML = '<input type="hidden" name="stop" value="T"><input type="hidden" name="ptid" value="' + pers_test_id + '">' + csrf;
            document.body.append(form);
            form.submit();
        } else {
            fullscreen(document.documentElement);
        }
    });
    timerPause();
}
var onfullscreenchange = function(e) {
    if (document.fullscreenElement == null) {
        fastStop();
    }
}

// Событие об изменениии режима
document.documentElement.addEventListener("webkitfullscreenchange", onfullscreenchange);
document.documentElement.addEventListener("mozfullscreenchange", onfullscreenchange);
document.documentElement.addEventListener("fullscreenchange", onfullscreenchange);

//document.oncontextmenu = function() { return false }
document.onkeydown = function(event) {
    return false
}

var quillQuestion = new Quill('.question', { theme: 'snow', "modules": { "toolbar": false } });
var quillAnswer1 = new Quill('#answer1', { theme: 'snow', "modules": { "toolbar": false } });
var quillAnswer2 = new Quill('#answer2', { theme: 'snow', "modules": { "toolbar": false } });
var quillAnswer3 = new Quill('#answer3', { theme: 'snow', "modules": { "toolbar": false } });
var quillAnswer4 = new Quill('#answer4', { theme: 'snow', "modules": { "toolbar": false } });
var selectedAnswerId = 0;
var currentQuestion = 0;
var ballMass = [];
var lastMinutes = 0;
var maxMinutes = 0;
var max_test_time = 0;
var minuts_spent = 0;
var pers_test_id = 0;
var test_id = 0;

var cor_ball = 0;

function fillVariables(timeLeft, pers_test_id, test_id, minuts_spent, max_test_time) {
    this.lastMinutes = timeLeft;
    this.maxMinutes = timeLeft;
    this.minuts_spent = minuts_spent;
    this.pers_test_id = pers_test_id;
    this.test_id = test_id;
    this.max_test_time = max_test_time;
}

function ajaxQuestList(test_id, pers_test_id) {
    $.ajax({
        url: '/test/loadQuestList',
        type: 'POST',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        data: { tid: test_id, ptid: pers_test_id },
        success: function(data) {
            if (data == -1) alert('Произошла ошибка при загрузке списка вопросов, повторите попытку!');
            else {
                loadQuestList(data);
            }
        },
        error: function(jqxhr, status, errorMsg) {
            Swal.fire({
                title: status,
                html: 'Ошибка соединения. Вы будете перемещены в личный кабинет. Повторите попытку через пару минут.',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: 'OK',
                closeOnClickOutside: false,
                icon: 'error',
            }).then((result) => {
                if (result.value) {
                    let form = document.createElement('form');
                    form.action = 'https://abit.ltsu.org/profile';
                    form.method = 'GET';
                    document.body.append(form);
                    form.submit();
                }
            });
        }
    });
}

function loadQuestList(data) {
    data.forEach(element => {
        currentQuestion++;
        ballMass[element["quest_id"]] = element["quest_ball"];
        $('.li').append('<div class="s-ask page-item "><a class="page-link item" href="#" onclick="selectedQuest(' + element["quest_id"] + ', this);">' + currentQuestion + '</a></div>');
    });
    if (currentQuestion != 0) {
        document.querySelector('.li').children[0].classList.add('active');
        $('.s-ask').filter('.active').children(".item").click();
    } else endTest(0);
}

function findRow(node) {
    var i = 1;
    while (node = node.previousSibling) {
        if (node.nodeType === 1) {++i }
    }
    return i;
}

function selectedQuest(qid, obj) {

    const wrapObj = document.querySelector('.li');
    var lastIndex = 0;
    for (let i = 0; i < wrapObj.children.length; i++) {
        wrapObj.children[i].classList.remove('active');
        lastIndex = i;
    }
    obj.parentNode.classList.add('active');

    if (qid > 0) {
        $.ajax({
            url: '/test/selectedQuest',
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
                            case 'ans1':
                                document.querySelector("#answer1 .ql-editor").innerHTML = element["text"];
                                $(".d-answer").children('#answer1').attr('onclick', "selectedAnswerId =" + element['id'] + ";");
                                break;
                            case 'ans2':
                                document.querySelector("#answer2 .ql-editor").innerHTML = element["text"];
                                $(".d-answer").children('#answer2').attr('onclick', "selectedAnswerId =" + element['id'] + ";");
                                break;
                            case 'ans3':
                                document.querySelector("#answer3 .ql-editor").innerHTML = element["text"];
                                $(".d-answer").children('#answer3').attr('onclick', "selectedAnswerId =" + element['id'] + ";");
                                break;
                            case 'ans4':
                                document.querySelector("#answer4 .ql-editor").innerHTML = element["text"];
                                $(".d-answer").children('#answer4').attr('onclick', "selectedAnswerId =" + element['id'] + ";");
                                break;
                        }
                    });
                    $('#idQuest').val(qid);

                }
            },
            error: function(jqxhr, status, errorMsg) {
                Swal.fire({
                    title: status,
                    html: 'Ошибка соединения. Вы будете перемещены в личный кабинет. Повторите попытку через пару минут.',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonText: 'OK',
                    closeOnClickOutside: false,
                    icon: 'error',
                }).then((result) => {
                    if (result.value) {
                        let form = document.createElement('form');
                        form.action = 'https://abit.ltsu.org/profile';
                        form.method = 'GET';
                        document.body.append(form);
                        form.submit();
                    }
                });
            }
        });
    } else { clearQuest(); }
}

function checkedAnswer(obj) {
    $("#confirmResposne").attr("disabled", false);
    $('.d-answer').children(".ql-container").removeClass('selected');
    obj.children(".ql-container").addClass('selected');
}

function endTest(type) {
    $('#main-page').hide();
    if (type == 0) title = 'Тест завершен!';
    else title = 'Время вышло!';
    Swal.fire({
        title: title,
        html: 'Нажмите "ОК" для получения результатов!',
        showCloseButton: false,
        showCancelButton: false,
        focusConfirm: false,
        confirmButtonText: 'OK',
        closeOnClickOutside: false,
        icon: 'info',
    }).then((result) => {
        if (result.value) {
            let form = document.createElement('form');
            var csrf = $('#csrf').html();
            form.action = '/test/result';
            form.method = 'POST';
            form.innerHTML = '<input name="ptid" value="' + pers_test_id + '">' + csrf;
            document.body.append(form);
            form.submit();
        }
    });
}

function deleteQuest() {
    var qid = $('#idQuest').val();
    /*var c = 0 + cor_ball + ballMass[qid];
    console.log(cor_ball + ' + ' + ballMass[qid] + ' = ' + c);
    cor_ball += ballMass[qid];*/
    ballMass[qid] = null;
    var indexRem = $('.s-ask').filter('.active').index();
    if (currentQuestion - 1 == indexRem) indexRem = 0;
    $('.s-ask').filter('.active').remove();
    currentQuestion--;
    if (currentQuestion <= 0) {
        endTest(0);
    } else {
        document.querySelector('.li').children[indexRem].classList.add('active');
        $('.s-ask').filter('.active').children(".item").click();
        $("#confirmResposne").attr("disabled", true);
        $('.d-answer').children(".ql-container").removeClass('selected');
    }
}

function confirmResponse() {
    Swal.fire({
        title: 'Подтверждение ответа',
        showCloseButton: false,
        showCancelButton: true,
        focusConfirm: false,
        cancelButtonText: 'Нет',
        confirmButtonText: 'Да',
    }).then((result) => {
        if (result.value) {
            var tid = $('#tid').val();
            var qid = $('#idQuest').val();
            var ptid = $('#ptid').val();
            $.ajax({
                url: '/test/confirmResponse',
                type: 'POST',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: { tid: tid, qid: qid, ansid: selectedAnswerId, ptid: ptid },
                success: function(data) {
                    if (data == -1)
                        Swal.fire(
                            'Ошибка!',
                            'Не найден ptid - ' + ptid + ', обратитесь к администратору!',
                            'error'
                        );
                    else if (data == -2)
                        Swal.fire(
                            'Ошибка!',
                            'Не найден tid - ' + tid + ', обратитесь к администратору!',
                            'error'
                        );
                    else if (data == -3)
                        Swal.fire(
                            'Ошибка!',
                            'Не найден qid - ' + qid + ', обратитесь к администратору!',
                            'error'
                        );
                    else if (data == -4)
                        Swal.fire(
                            'Ошибка!',
                            'Не найден ansDetail - ' + selectedAnswerId + ', обратитесь к администратору!',
                            'error'
                        );
                    else {
                        deleteQuest();
                    }
                },
                error: function(jqxhr, status, errorMsg) {
                    Swal.fire({
                        title: status,
                        html: 'Ошибка соединения. Вы будете перемещены в личный кабинет. Повторите попытку через пару минут.',
                        showCloseButton: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText: 'OK',
                        closeOnClickOutside: false,
                        icon: 'error',
                    }).then((result) => {
                        if (result.value) {
                            let form = document.createElement('form');
                            form.action = 'https://abit.ltsu.org/profile';
                            form.method = 'GET';
                            document.body.append(form);
                            form.submit();
                        }
                    });
                }
            });
        }
    });
}

function time_left() {
    $.ajax({
        url: '/test/timeLeft',
        type: 'POST',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            ptid: pers_test_id,
            leftMinutes: lastMinutes,
            minuts_spent: minuts_spent
        },
        success: function(data) {
            if (data == -1)
                Swal.fire(
                    'Ошибка!',
                    'Попытка изменения таймера!',
                    'error'
                );
        },
        error: function(jqxhr, status, errorMsg) {
            Swal.fire({
                title: status,
                html: 'Ошибка соединения. Вы будете перемещены в личный кабинет. Повторите попытку через пару минут.',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: 'OK',
                closeOnClickOutside: false,
                icon: 'error',
            }).then((result) => {
                if (result.value) {
                    let form = document.createElement('form');
                    form.action = 'https://abit.ltsu.org/profile';
                    form.method = 'GET';
                    document.body.append(form);
                    form.submit();
                }
            });
        }
    });
}
Swal.fire({
    title: '<center style="color:red;">В Н И М А Н И Е</center>',
    html: '<lable style="color:#326521; ">Остановить тестирование после старта невозможно!<br>' +
        'Повторное прохождение тестирования не допускается!<br>' +
        'Результаты Вашей работы фиксируются на сервере.</lable>',
    showCloseButton: true,
    showCancelButton: false,
    focusConfirm: false,
    confirmButtonText: 'ОЗНАКОМЛЕН',
}).then((result) => {
    if (result.value) {
        fullscreen(document.documentElement);
        ajaxQuestList(test_id, pers_test_id);
        $('#main-page').show();
        // таймер
        function getTimeRemaining(endtime) {
            var t = Date.parse(endtime) - Date.parse(new Date());
            var seconds = Math.floor((t / 1000) % 60);
            var minutes = Math.floor((t / 1000 / 60) % 60);
            var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
            var days = Math.floor(t / (1000 * 60 * 60 * 24));
            return {
                'total': t,
                'days': days,
                'hours': hours,
                'minutes': minutes,
                'seconds': seconds
            };
        }

        function initializeClock(id, endtime) {
            var clock = document.getElementById(id);
            var hoursSpan = clock.querySelector('.hours');
            var minutesSpan = clock.querySelector('.minutes');
            var secondsSpan = clock.querySelector('.seconds');

            function updateClock() {
                var t = getTimeRemaining(endtime);

                hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
                minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
                secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

                if ((t.minutes != 0) && (t.minutes != maxMinutes) && (t.minutes < lastMinutes)) {
                    lastMinutes = t.minutes;
                    minuts_spent = max_test_time - lastMinutes;
                    time_left();
                }
                if (t.total <= 0) {
                    clearInterval(timeinterval);
                    endTest(1);
                }
            }
            updateClock();
            var timeinterval = setInterval(updateClock, 1000);
        }

        var tday = 1;
        var thour = 1;
        var tmin = lastMinutes;
        var deadline = new Date(Date.parse(new Date()) + tday * thour * tmin * 60 * 1000);

        initializeClock('countdown', deadline);
        // таймер
    } else {
        window.location.href = "/pers/cabinet"
    }
});