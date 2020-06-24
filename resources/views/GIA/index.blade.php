@extends('layer')

@section('header')
	<div class="app-header header d-flex">
		<div class="container-fluid">
			<div class="d-flex">
			    <a class="header-brand" href="/" style="width:100%;text-align:center">
					<img src="{{ asset('images/logo.png') }}" class="header-brand-img main-logo" alt="Hogo logo">
					<img src="{{ asset('images/logo.png') }}" class="header-brand-img icon-logo" alt="Hogo logo">
					<span class='logo-name'>Регистрация {{ isset($events) ? "на ".$events->name : " не активна" }}</span>
				</a>
				<div class="d-flex order-lg-2 ml-auto header-rightmenu"></div>
			</div>
		</div>
	</div> 
@endsection

@section('content')
	<div class="my-3 my-md-5 toggle-content">
		<div class="side-app">
			<div class="page-header"></div><div class="page-header"></div>
			<form action='' method='POST' name="regForm" id="regForm">
				{{ csrf_field() }}
 				<div class="row">
                     <div class="col-md-3"></div>
                     @if (isset($events))
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12"> 
                                            <div class='row mb-3'>
                                                <div class='col-md-12 mb-2'>
                                                    <label class="form-label">Фамилия</label>
                                                    <input type="text" class="form-control" name="famil" id="famil" placeholder="" >
                                                </div> 
                                                <div class='col-md-12 mb-2'>
                                                    <label class="form-label">Имя</label>
                                                    <input type="text" class="form-control" name="name" id="name" placeholder="" >
                                                </div> 
                                                <div class='col-md-12 mb-2'>
                                                    <label class="form-label">Отчество</label>
                                                    <input type="text" class="form-control" name="otch" id="otch" placeholder="" >
                                                </div> 	
                                                <div class='col-md-12 mb-2'>
                                                    <label class="form-label">Группа</label>
                                                    <input type="text" class="form-control" name="group" id="group" placeholder="" >
                                                </div>
                                            </div>												
                                        </div>																			
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-5" style="justify-content: center;">
                                <input type="hidden" name="captcha" id="captcha" value="">
                                <div class="g-recaptcha" data-sitekey="6LfwCtUUAAAAAJ7rw_7LyfpDHrAF5dgaUJpuJTQd"></div>
                            </div>
                            
                            <div class='text-center mb-5'>
                                <button type='button' class='btn btn-success' id='reg'>Начать тест</button>
                            </div>
                        </div>
                    @else
                        <div class="col-md-6">
                            <div class='text-center mb-5'>
                               Экзамен в данное время не доступен
                            </div>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection

@section('includeScripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
	<script src="{{ asset('js/accordion.min.js') }}"></script>
    <script src="{{ asset('js/jquery.sweet-modal.min.js') }}"></script>
	<script src="{{ asset('js/sweetalert4.min.js') }}"></script>
	<script src="{{ asset('js/toastr.js') }}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        function errorMsg(msg) {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-center",
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
            Command: toastr["error"](msg);
        }
        $(document).on('click', '#reg', function(e) {
            var empty = true;
            $('input').each(function() {
                if ($(this).hasClass('form-control')) {
                    if ($(this).val().trim().length == 0) {
                        empty = false;
                        return false;
                    }
                }
            });

            if (!empty) errorMsg('Все поля должны быть заполнены!');
            else
            {
                $('#captcha').val(grecaptcha.getResponse());
                $.ajax({
                    url: '/gia/reg',
                    type: 'POST',
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $('#regForm').serialize(),
                    success: function(data) {
                        startTest(data);
                        if (data == 1) {
                            errorMsg("Проверка на робота не пройдена, повторите попытку!");
                            grecaptcha.reset();
                        }
                        else if (data == -1) {
                            errorMsg("Ошибка при регистрации!");
                            grecaptcha.reset();
                        }
                    }
                });
            }
        });
        function startTest(testPersId)
        {
            if (testPersId != 0)
            {
                let form = document.createElement('form');
                form.action = '/test/start';
                form.method = 'POST';
                form.innerHTML = '<input name="ptid" value="' + testPersId + '">{{ csrf_field() }}';
                document.body.append(form);
                form.submit();
            }
        }
    </script>
@endsection

@section('includeStyles')
    <title>Регистрация</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
	<link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
@endsection