@extends('layer')

@section('header')
	<div class="app-header header d-flex">
		<div class="container-fluid">
			<div class="d-flex">
			    <a class="header-brand" href="/" style="width:100%;text-align:center">
					<img src="images/logo.png" class="header-brand-img main-logo" alt="Hogo logo">
					<img src="images/logo.png" class="header-brand-img icon-logo" alt="Hogo logo">
					<span class='logo-name'>Регистрация на пробное тестирование</span>
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
					<div class="col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class='col-md-4 mb-2'>
										<label class="form-label">Логин</label>
										<input type="text" class="form-control" name="login" id="login" placeholder="" onkeyup="check_login()">
									</div> 
									<div class='col-md-4 mb-2'>
										<label class="form-label">Пароль</label>
										<input type="password" class="form-control" name="pass" id="pass" placeholder="" onkeyup="checkPass()">
									</div> 
									<div class='col-md-4 mb-2'>
										<label class="form-label">Повторить пароль</label>
										<input type="password" class="form-control" name="pass2" id="pass2" placeholder="" onkeyup="checkPass()">
									</div> 
								</div>
								<hr>
								<div class="row">
									<div class="col-md-6"> 
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
												<label class="form-label">Серия паспорта</label>
												<input type="text" class="form-control" name="serp" id="serp" placeholder="" >
											</div> 
										</div>												
									</div>										
									<div class="col-md-6"> 
										<div class='row mb-3'>
											<div class='col-md-12 mb-2'>
												<label class="form-label">Дата рождения</label>
												<input type="date" class="form-control" name="birthday" id="birthday" placeholder="" >
											</div> 
											<div class='col-md-12 mb-2'>
												<label class="form-label">Пол</label>
												<select class='form-control' name="gender" id="gender" >
													<option></option>
													<option value="Муж">Мужской</option>
													<option value="Жен">Женский</option>
												</select>
											</div> 
											<div class='col-md-12 mb-2'>
												<label class="form-label">Email</label>
												<input type="email" class="form-control" name="email" id="email" placeholder="" onkeyup="check_email()">
											</div>
											<div class='col-md-12 mb-2'>
												<label class="form-label">Номер паспорта</label>
												<input type="text" class="form-control" name="nump" id="nump" placeholder="" >
											</div> 
										</div>												 
									</div>										
								</div>
							</div>
							<div id="errorLogin" class="col-sm-12" style="color: #ff0000; margin-top: 5px; margin-bottom: 5px;"></div>
							<div id="errorEmail" class="col-sm-12" style="color: #ff0000; margin-top: 5px; margin-bottom: 5px;"></div>
							<div id="errorFillInput" class="col-sm-12" style="color: #ff0000; margin-top: 5px; margin-bottom: 5px;"></div>
							<div id="error" class="col-sm-12" style="color: #ff0000; margin-top: 5px; margin-bottom: 5px;"></div>
						</div>	
						<input type="hidden" name="captcha" id="captcha" value="">
						<div class="g-recaptcha" data-sitekey="6LfwCtUUAAAAAJ7rw_7LyfpDHrAF5dgaUJpuJTQd"></div>
						<div class='text-center mb-5'>
							<button type='button' class='btn btn-success' id='reg'>Регистрация</button>
						</div>
						</div>
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
	<script src='https://www.google.com/recaptcha/api.js'></script>
		<!-- новая строка - сборный текст -->
		<script>
			var blockedLogin = false;
			var blockedEmail = false;
			function check_login() { 
				var log = $("#login").val().trim(); 

				$.ajax({ 
					url: '/Check_login', 
					type: 'POST', 
					data: { 
						log: log 
					}, 
					headers: { 
						'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') 
					}, 
					success: function(data) { 
						if (data == -1) { 
							$('#errorLogin').html("*Такой логин уже существует"); 
							blockedLogin = true;
						} else { 
							$('#errorLogin').html(''); 
							blockedLogin = false;
						} 
					}, 
					error: function(msg) { 
						alert('Error, try again'); 
					} 
				}); 
			}
			function check_email() { 
				var email = $("#email").val().trim(); 

				$.ajax({ 
					url: '/Check_email', 
					type: 'POST', 
					data: { 
						email: email 
					}, 
					headers: { 
						'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') 
					}, 
					success: function(data) { 
						if (data == -1) { 
							$('#errorEmail').html("*Такой E-mail уже существует"); 
							blockedEmail = true;
						} else { 
							$('#errorEmail').html(''); 
							blockedEmail = false;
						} 
					}, 
					error: function(msg) { 
						alert('Error, try again'); 
					} 
				}); 

			}
			$(document).on('click','#reg', function(e){
				var empty = true;
						$('input').each(function() { 
							if ($(this).hasClass('form-control')) {
								if ($(this).val().trim().length == 0) { 
									empty = false;
									return false;
								} 
							}
						});
						if (!empty) $('#errorFillInput').html('Все поля должны быть заполнены!');
						else $('#errorFillInput').html('');
				if (blockedLogin == false && blockedEmail == false && empty) {

						var pas = $("#pass").val(); 
						var check_pas = $("#pass2").val();
						if (pas != check_pas) $('#error').html('Пароли не совпадают!');
						else {
							$('#error').html('');
							$('#captcha').val(grecaptcha.getResponse());
							$.ajax({
								url: '/registration/post',
								type: 'POST',
								headers: {
									'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
								},
								data: $('#regForm').serialize(),
								success: function (data) {
									if (data == 0) popupShow();
									else if (data == 1) {
										$('#error').html("Проверка на робота не пройдена, повторите попытку!");
										grecaptcha.reset();
									}
									
								}
							});
						}
					
				}
			});
			function popupShow()
			{
				Swal.fire({
					showCancelButton: true, 
					// title: 'Регистрация',
					text: 'На указанный Вами email отправлено сообщение\n для завершения регистрации!',
					// input: 'password',
					inputPlaceholder: '******',
					inputAttributes: {
						maxlength: 10,
						autocapitalize: 'off',
						autocorrect: 'off'
					},
					// cancelButtonText: 'Закрыть',
					confirmButtonText:'Продолжить',
					showCancelButton: false,
					reverseButtons: false

				}).then((result) => {
					if (result.value) {
						window.location.href="/"
					}
				})
			}
			function checkPass()
			{
				var pas = $("#pass").val(); 
				var check_pas = $("#pass2").val();
				if (pas != check_pas) $('#error').html('Пароли не совпадают!');
				else $('#error').html('');
			}
		</script>
@endsection

@section('includeStyles')
    <title>Регистрация</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
@endsection