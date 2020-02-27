@extends('layer')

@section('header')
    <div class="app-header header d-flex">
        <div class="container-fluid">
            <div class="d-flex">
                <a class="header-brand" href="">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img main-logo" alt="Hogo logo">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img icon-logo" alt="Hogo logo">
                    <span class='logo-name'>Тестирование</span>
                </a>
                <div class="d-flex order-lg-2 ml-auto header-rightmenu">
                    <div class="dropdown">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#" onClick="fullscreen(document.documentElement);return false;" id="fullscreen"></a>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
@endsection

@section('content')
    <div class="app-content  my-3 my-md-5 toggle-content" id='main-page' style='display: none'>
        <div class="side-app">
            <div class="page-header"></div>
            <div class="row" >
                <div class='col-md-12'>                             
                    <div class='card'>
                        <div class='card-body'>
                            <div class='row'>
                                <div class='col-md-4 text-left'>{{ $test_name }}</div>
                                <div class='col-md-4 text-center'>
                                    <h1>
                                        <div id="countdown" class="countdown">
                                            <div class="countdown-number">
                                                <span class="hours countdown-time"></span>
                                                :
                                                <span class="minutes countdown-time"></span>
                                                :
                                                <span class="seconds countdown-time"></span> 
                                            </div>
                                        </div>
                                    </h1>
                                </div>
                                <div class='col-md-4 text-right'>{{ $test->famil.' '.$test->name.' '.$test->otch }}</div>
                                <div class='col-md-12 text-center mt-5'>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row row-cards">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="pagination mg-b-0 page-0 li" style="overflow-x: auto;"></div> 
                                                            
                                                        <input type="hidden" name="tid" id="tid" value="{{$test->id}}">
                                                        <input type="hidden" name="idQuest" id="idQuest">
                                                        <input type="hidden" name="ptid" id="ptid" value="{{$pers_test_id}}">
                                            
                                                        <div class="card-body">
                                                            <div class="question" name="question" id="question"></div>
                                                            <div class='row' style='margin:15px -10px'>
                                                                <div class='col-md-6'>
                                                                    <div class="d-answer" onclick="checkedAnswer($(this));">
                                                                        <div class="q-ask" id="answer1" name="answer1"></div>
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-6 '>
                                                                    <div class="d-answer" onclick="checkedAnswer($(this));">
                                                                        <div class="q-ask" id="answer2" name="answer2"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class='row'>
                                                                <div class='col-md-6'>
                                                                    <div class="d-answer" onclick="checkedAnswer($(this));">
                                                                        <div class="q-ask" id="answer3" name="answer3"></div>
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-6'>
                                                                    <div class="d-answer" onclick="checkedAnswer($(this));">
                                                                        <div class="q-ask" id="answer4" name="answer4"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>
                                    <div class='text-center mt-5 btn-s'>
                                        <input type='hidden' name='result' id='res' readony>
                                        <button type='button' class='btn btn-success' onclick="confirmResponse();" id="confirmResposne" disabled>Подтвердить</button>
                                    </div>
                                    <div class='btn-exit mt-5 text-left'>
                                        <a href="#" onclick="fastStop();">Выход</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('includeScripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/accordion.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/quill2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert4.min.js') }}"></script>
@endsection

@section('includeBeforeScripts')
<script type="text/javascript">
    function fullscreen(element) {
        if (element.requestFullScreen) {
            element.requestFullScreen();
        } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullScreen) {
            element.webkitRequestFullScreen();
        }
    }
    function fastStop()
    {
        Swal.fire({
                title: '<center style="color:red;">В Н И М А Н И Е</center>',
                html:'<lable style="color:#326521;">Вы хотите прервать тестирование?<br>' +
                    'Повторное прохождение тестирования не допускается!<br>' +
                    'Результаты Вашей работы фиксируются на сервере.</lable>',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Да',			   
                cancelButtonText: 'Нет',			   
            }).then((result) => {
                if (result.value) { 
                    window.location.href="/test/result";
                }else{
                    fullscreen(document.documentElement);
                }
            }); 
    }
    var onfullscreenchange =  function(e){
        if (document.fullscreenElement == null)
        {
            fastStop();
        }
    }

    // Событие об изменениии режима
    document.documentElement.addEventListener("webkitfullscreenchange", onfullscreenchange);
    document.documentElement.addEventListener("mozfullscreenchange",    onfullscreenchange);
    document.documentElement.addEventListener("fullscreenchange",       onfullscreenchange);
    
    //document.oncontextmenu = function() { return false }
    document.onkeydown = function(event) {
        return false
    }

    var quillQuestion = new Quill('.question', { theme: 'snow',"modules": { "toolbar": false} });
    var quillAnswer1 = new Quill('#answer1', { theme: 'snow',"modules": { "toolbar": false} });
    var quillAnswer2 = new Quill('#answer2', { theme: 'snow',"modules": { "toolbar": false} });
    var quillAnswer3 = new Quill('#answer3', { theme: 'snow',"modules": { "toolbar": false} });
    var quillAnswer4 = new Quill('#answer4', { theme: 'snow',"modules": { "toolbar": false} });
    var selectedAnswerId = 0;
    var currentQuestion = 0;
    var ballMass = [];
    var lastMinutes = {{$timeLeft}};
    var maxMinutes = {{$timeLeft}};

    function ajaxQuestList(test_id, pers_test_id) {
        $.ajax({
            url: '/test/loadQuestList',
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: { tid: test_id, ptid:pers_test_id },
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
        data.forEach(element => {
            currentQuestion++;
            ballMass[element["quest_id"]] = element["ball"];
            $('.li').append('<div class="s-ask page-item "><a class="page-link item" href="#" onclick="selectedQuest(' + element["quest_id"] + ', this);">' + currentQuestion + '</a></div>');
        });
        if (currentQuestion != 0) {
            document.querySelector('.li').children[0].classList.add('active');
            $('.s-ask').filter('.active').children(".item").click();
        } 
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
                error: function(msg) {
                    alert('Ошибка');
                }
            });
        } else { clearQuest(); }
    }

    function checkedAnswer(obj){
        $("#confirmResposne").attr("disabled", false);
        $('.d-answer').children(".ql-container").removeClass('selected');
        obj.children(".ql-container").addClass('selected');
    }

    function endTest(type)
    {
        $('#main-page').hide();
        if (type == 0) title = 'Тест завершен!';
        else title = 'Время вышло!';
        Swal.fire({
            title: title,
            html:'Нажмите "ОК" для получения результатов!',
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonText: 'OK',
            closeOnClickOutside: false,	
            icon: 'infortmation',		   
        }).then((result) => {
            if (result.value) { 
                window.location.href="/test/result";
            }
        });
    }

    function deleteQuest() {
        var qid = $('#idQuest').val();
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

    function confirmResponse()
    {
        Swal.fire({
          title: 'Подтверждение ответа',				  
          showCloseButton: false,
          showCancelButton: true,
          focusConfirm: false,
          cancelButtonText: 'Нет',
          confirmButtonText:'Да',			   
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
                    data: { tid : tid, qid : qid, ansid : selectedAnswerId , ptid : ptid },
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
                        else 
                        {
                            deleteQuest();
                        }
                    },
                    error: function(msg) {
                        alert('Ошибка');
                    }
                });
            }
        });
    }
    function timeLeft()
    {
        var minuts_spent = {{$timeLeft}} - lastMinutes;
        $.ajax({
            url: '/test/timeLeft',
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: { ptid : {{$pers_test_id}} , leftMinutes : lastMinutes, minuts_spent : minuts_spent},
            success: function(data) {
                if (data == -1) 
                    Swal.fire(
                        'Ошибка!',
                        'Попытка изменения таймера!',
                        'error'
                    );
            },
             error: function(msg) {
                Swal.fire(
                    'Ошибка!',
                    '',
                    'error'
                );
            }
        });
    }
    Swal.fire({
        title: '<center style="color:red;">В Н И М А Н И Е</center>',
        html:'<lable style="color:#326521; ">Остановить тестирование после старта невозможно!<br>' +
             'Повторное прохождение тестирования не допускается!<br>' +
             'Результаты Вашей работы фиксируются на сервере.</lable>',
        showCloseButton: true,
        showCancelButton: false,
        focusConfirm: false,
        confirmButtonText: 'ОЗНАКОМЛЕН',			   
    }).then((result) => {
        if (result.value) { 
            fullscreen(document.documentElement);
            ajaxQuestList({{$test->id}},{{$pers_test_id}});
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

                    if ((t.minutes != 0) && (t.minutes != maxMinutes) && (t.minutes < lastMinutes))
                    {
                        lastMinutes = t.minutes;
                        timeLeft();
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
            var tmin = {{$timeLeft}};
            var deadline =  new Date(Date.parse(new Date()) + tday * thour * tmin * 60 * 1000); 

            initializeClock('countdown', deadline);
            // таймер
        }else{
             window.location.href="/pers/cabinet"
        }
    });

</script>
@endsection

@section('includeStyles')
    <title>Тестирование</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Scrollbar.css') }}" rel="stylesheet" />

    <link href="{{ asset('css/quill.snow.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/quill-better-table.css') }}" rel="stylesheet">
	<style>
		.question {
			padding: 10px;
			height: 200px;
			outline: none;
			overflow-y: auto;
			overflow-x: auto;
		}
		.q-ask{
            min-height: 200px;
            height: 200px;
            outline: none;
			overflow-y: auto;
			overflow-x: auto;
        }
        
        .ql-editor, .ql-editor>* {
            -webkit-user-modify: read-only;
            cursor: pointer !important;
        }
        .ql-container.selected {
            border: 2px solid green;
        }
        #main-page {
            margin-left: 5% !important;
            margin-right: 5% !important;
        }
        h1 {
            margin-bottom: 0px;
        }
        .mt-5 {
            margin-top: 0px !important;
            margin-top: 0px !important;
        }
        .card {
            margin-bottom: 0px !important;
        }
        .page-header {
            min-height: 15px;
        }

        @media (max-width: 768px) {
            .q-ask {
                min-height: 100px;
                height: 150px;
                outline: none;
                overflow-y: auto;
                overflow-x: auto;
            }
            .card-body {
                padding: 0.5rem 0.5rem;
            }
            h1 {
                font-size: 1.5rem;
            }
            .countdown span:first-child {
                font-size: 1.5rem;
            }
            .page-header {
                min-height: 5px;
            }
        }
	</style>
@endsection