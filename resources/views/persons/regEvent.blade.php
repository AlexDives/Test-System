@extends('layer')

@section('header')
	<div class="app-header header d-flex">
		<div class="container-fluid">
			<div class="d-flex">
			    <a class="header-brand" href="/" style="width:100%;text-align:center">
					<img src="{{ asset('images/logo.png') }}" class="header-brand-img main-logo" alt="Hogo logo">
					<img src="{{ asset('images/logo.png') }}" class="header-brand-img icon-logo" alt="Hogo logo">
					<span class='logo-name'>Регистрация на {{ $event_name }}</span>
				</a>
				<div class="d-flex order-lg-2 ml-auto header-rightmenu"></div>
			</div>
		</div>
	</div> 
@endsection

@section('content')
    <div class="my-3 my-md-5 toggle-content">
        <div class="side-app">
            <div class="page-header"></div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form action='#' method='POST' name="regEvent" id="regEvent">
                        {{ csrf_field() }}
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <div class='row mb-3'>
                                            <div class='col-md-4 mb-2'>
												<label class="form-label">Фамилия</label>
												<input type="text" class="form-control" name="famil" id="famil" placeholder="" value="{{ $famil }}" readonly>
											</div> 
											<div class='col-md-4 mb-2'>
												<label class="form-label">Имя</label>
												<input type="text" class="form-control" name="name" id="name" placeholder="" value="{{ $name }}" readonly>
											</div> 
											<div class='col-md-4 mb-2'>
												<label class="form-label">Отчество</label>
												<input type="text" class="form-control" name="otch" id="otch" placeholder="" value="{{ $otch }}" readonly>
											</div>
                                            <div class='col-md-12 mt-2'>
                                                <label class="form-label">Место учебы</label>
                                                <input type="text" class="form-control" name="study_place" id="study_place" placeholder="" value="{{ $place_study }}">
                                            </div> 													
                                        </div>												 
                                    </div>	
                                    <!--<div class="col-md-12"> 
                                        <div class='row m-5'>
                                            <div class='col-md-12'>
                                                <div class="custom-controls-stacked custom-center">
                                                    <label class="custom-control custom-radio" onclick="checkedRadio();">
                                                        <input type="radio" class="custom-control-input" name="target_audience" id="target_audience" value="1" checked="">
                                                        <span class="custom-control-label">на базе 11 классов</span>
                                                    </label>
                                                    <label class="custom-control custom-radio" onclick="checkedRadio();">
                                                        <input type="radio" class="custom-control-input" name="target_audience" id="target_audience" value="2">
                                                        <span class="custom-control-label">на базе СПО</span>
                                                    </label> 
                                                </div>	
                                            </div>											
                                        </div>												 
                                    </div>-->									
                                </div>
                                <div class="row text-center">
                                    <div class='col-md-12'>
                                        <h4 style='width: 100%;color: #524c96'>Выберите предметы для тестрования и желаемое время</h4>
                                    </div>
                                    <div class='col-md-12 text-danger'>
                                        <h6>
                                            <p><b>Уважаемые абитуриенты!<br>
                                            При выборе времени для прохождения тестирования учитывайте, что на прохождение первого теста отводится один час.
                                            Поэтому рекомендуем Вам выбирать последовательно. (Например, время прохождения первого теста Вы выбрали на 9.00.<br>
                                            Соответственно время для второго теста рекомендуем выбрать на 10.00 )</b></p>
                                        </h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-6"> 
                                        <div class='row mb-3' id="testsBac">
                                            <div class='col-md-12 mb-2'>
                                                <label class="form-label">1-й тест</label>
                                                <select onchange="changeTest();" class='form-control tests' name="first_tests" id="first_tests">
                                                    <option value="-1">Выберите тест</option>
                                                    @foreach ($testsBac as $test)
                                                        <option value="{{$test->id}}" id="ft{{$test->id}}">{{$test->discipline}}</option>
                                                    @endforeach
                                                </select>
                                            </div> 
                                            <div class='col-md-12 mb-2'>
                                                <label class="form-label">2-й тест</label>
                                                <select  class='form-control tests' name="second_tests" id="second_tests" disabled>
                                                    <option value="-1">Выберите тест</option>
                                                    @foreach ($testsBac as $test)
                                                        <option value="{{$test->id}}" id="st{{$test->id}}">{{$test->discipline}}</option>
                                                    @endforeach
                                                </select>
                                            </div>  
                                        </div>												 
                                        <div class='row mb-3' id="testsMC">
                                            <div class='col-md-12 mb-2'>
                                                <label class="form-label">Тест</label>
                                                <select class='form-control tests' name="first_testsMC" id="first_testsMC">
                                                    <option value="-1">Выберите тест</option>
                                                    @foreach ($testsMC as $test)
                                                        <option value="{{$test->id}}" id="{{$test->id}}">{{$test->discipline}}</option>
                                                    @endforeach
                                                </select>
                                            </div>  
                                        </div>												 
                                    </div>
                                    <div class="col-md-6 col-6"> 
                                        <div class='row mb-3'>
                                            <div class='col-md-12 mb-2'>
                                                <label class="form-label">Время</label>
                                                <select onchange="changeTime();"class='form-control times' name="first_time" id="first_time">
                                                    <option value="-1">Время начала тестирования</option>
                                                    @for ($i = 0; $i < count($freeTime); $i++)
                                                        <option value="{{ $freeTime[$i]['full'] }}" id="ftt{{ ($i+1) }}">{{ $freeTime[$i]['short'] }}</option>
                                                    @endfor
                                                </select>
                                            </div> 
                                            <div class='col-md-12 mb-2' id="timeBac">
                                                <label class="form-label">Время</label>
                                                <select class='form-control times' name="second_time" id="second_time" disabled>
                                                    <option value="-1">Время начала тестирования</option>
                                                    @for ($i = 0; $i < count($freeTime); $i++)
                                                        <option value="{{ $freeTime[$i]['full'] }}" id="stt{{ ($i+1) }}">{{ $freeTime[$i]['short'] }}</option>
                                                    @endfor
                                                </select>
                                            </div>  
                                        </div>												 
                                    </div>
                                    <div class="col-md-12"> 
                                        <div class='row m-5'>
                                            <div class='col-md-4'></div>
                                            <div class='col-md-5'>
                                                <div class="custom-controls-stacked custom-center">
                                                    <label class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="is_hostel" id="is_hostel" value="T">
                                                        <span class="custom-control-label">Требуется общежитие</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>	
                        <div class='text-center mb-5'>
                            <button type='button' class='btn btn-success' onclick="reg()">Регистрация</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-lg-12 col-sm-12   text-center">
                        © {{ date('Y', time()) }} <a href="{{ url("/") }}">ЛНУ имени Тараса Шевченко</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection

@section('includeScripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/accordion.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert4.min.js') }}"></script>
    <script>
        function changeTest()
        {
            if ($('#first_tests').val() != -1) {
                var id = $('#first_tests').val();
                $('#second_tests').val('-1');
                $('.tests option').show();
                $('#st'+id).hide();
            }
            else 
            {
                $('#second_tests').val('-1');
                $('#second_time').val('-1');
                $('#second_time').prop('disabled', true);
                $('#second_tests').prop('disabled', true);
            }
        }
        function changeTime(num)
        {
            if ($('#first_time').val() != -1)
            {
                var id = $('#first_time option:selected').index();
                $('#second_time').prop('disabled', false);
                $('#second_tests').prop('disabled', false);
                $('#second_time').val('-1');
                $('.times option').show();
                $('#stt'+id).hide();
            }
            else
            {
                $('#second_tests').val('-1');
                $('#second_time').val('-1');
                $('#second_time').prop('disabled', true);
                $('#second_tests').prop('disabled', true);
            }
        }
        function checkedRadio()
        {
            if ($('input[name=target_audience]:checked').val() == 1) {
                $('#testsBac').show();
                $('#timeBac').show();
                $('#testsMC').hide();
            }
            else {
                $('#testsMC').show();
                $('#testsBac').hide();
                $('#timeBac').hide();
            }
        }

        function reg() {
            var place_study = $('#study_place').val();
            //var who = $('input[name=target_audience]:checked').val();
            var who = 1;
            var first_test = $('#first_tests').val();
            var second_test = $('#second_tests').val();
            var first_time = $('#first_time').val();
            var second_time = $('#second_time').val();
            var is_hostel = $('#is_hostel').val();
            var first_testMC = $('#first_testsMC').val();
            var eid = {{ $eid }};
            if ((first_test != -1 || first_testMC != -1) && first_time != -1) {
                
                $.ajax({
                    url: '/persons/savevent',
                    type: 'POST',
                    data: {
                        eid : eid,
                        place_study : place_study,
                        who : who,
                        first_test : first_test,
                        first_time : first_time,
                        second_test : second_test,
                        second_time : second_time,
                        is_hostel : is_hostel,
                        first_testMC : first_testMC
                    },
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data == -1) location.replace("/persons");
                        else popup(data);
                    },
                    error: function(msg) {
                        alert('Error, try again');
                    }
                });
            }
            else 
            {
                Swal.fire('Ошибка', 'Нужно выбрать минимум ОДИН тест и время к нему!', 'error')
            }
        }
        function createPdf(peid, status)
        {
            let form = document.createElement('form');
            form.action = '/persons/createPdf';
            form.method = 'POST';
            form.target = '_blank';
            form.innerHTML = '<input type="hidden" name="peid" value="' + peid + '"><input type="hidden" name="status" value="' + status + '">{{ csrf_field() }}';
            // перед отправкой формы, её нужно вставить в документ
            document.body.append(form);
            form.submit();           
        }
        function popup(data) {
            Swal.fire({
              title: '',				  
              showCloseButton: false,  
              html:
                '<div class="row">'+
                    '<div class="col-md-12 mb-2"><b>Вы успешно зарегистрировались на {{ $event_name }}:</b></div>'+
                    '<div class="col-md-12 mb-2" style="font-size:14px"><b>Для участия Вам потребуется:</b></div>'+
                '</div>'+
                '<div class="row">'+				    	
                    '<div class="col-md-12 mb-2" style="text-align: justify; line-height: 16px; font-size: 13px;"><ol>'+
                    '<li><b>Скачать и распечатать</b> "Экзаменационный лист", в котором указаны Ваши данные и <b>персональный PIN</b>.</li>'+
                    '<li class="mt-2">{{ $event_name }} будет проходить <b>{{ $event_date }}</b> в On-line режиме.</li>'+
                    '<li class="mt-2">Провести хорошо время</li></ol></div>'+ 
                '</div>'+

				/*'<div class="row">'+				    	
                    '<div class="col-md-12 mb-2" style="text-align: justify; line-height: 16px; font-size: 13px;"><ol>'+
                    '<li><b>Скачать и распечатать</b> "Экзаменационный лист", в котором указаны Ваши данные и <b>персональный PIN</b>. Без "Экзаменационного листа" Вас <b>не допустят к тестированию.</b></li>'+
                    '<li class="mt-2"><b>Прийти</b> для подтверждения регистрации на {{ $event_name }}, которое будет проходить <b>{{ $event_date }}</b>, по адресу г. Луганск,  ул. Оборонная 2: учебный корпус №2 , 2-й этаж, каб. 270</li>'+
                    '<li class="mt-2">Провести хорошо время</li></ol></div>'+ 
                '</div>'+*/

                '<div class="row">'+
                '<div class="col-md-4"><button class="btn btn-primary" onclick="createPdf(' + data + ', 0);">Открыть</button></div>'+
                '<div class="col-md-4"><button class="btn btn-primary" onclick="createPdf(' + data + ', 1);">Скачать</button></div>'+
                '<div class="col-md-4"><button class="btn btn-primary" onclick="Swal.close()">Отмена</button></div>'+
                '</div>'+
                '</div>',
              showCancelButton: false,
              showConfirmButton: false,
              focusConfirm: false,
              cancelButtonText: 'Отмена',
              confirmButtonText:'Отправить',			   
            });
        }
    </script>
@endsection

@section('includeStyles')
    <title>Регистрация на {{ $event_name }}</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">	
    <style>
        
        .custom-center{
            flex-direction: column;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #testsMC {
            display: none;
        }
        .m-5 {
            margin: 1rem !important;
        }
    </style>
@endsection