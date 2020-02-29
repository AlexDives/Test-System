<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<meta http-equiv="Cache-Control" content="max-age=300, must-revalidate">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link href="{{ asset('images/favicon.ico') }}" rel="icon" type="image/x-icon"/>
		<link href="{{ asset('images/favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
		<link href="https://fonts.googleapis.com/css?family=Cabin&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="css/styleAuth.css" />
		<title>Система тестирования</title>
    	<script src="{{ asset('js/jquery.js') }}"></script>
		<script src="{{ asset('js/rotateScript.js') }}"></script>
		<script>
			function auth_check() {
				if ($("#login").val() == "" && $("#pwd").val() == "") $(".error-message").text("Заполните все поля!");
				else { 
					$.ajax({
						url: '{{ url("/auth") }}',
						type: 'POST',
						data: $('#AuthForm').serialize(),
						headers: {
							'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
						},
						success: function (data) {
							if (data == -2) $(".error-message").text("Неверный логин или пароль!");
							else if (data == -1) $(".error-message").text("Данный пользователь ЗАБЛОКИРОВАН!");
							else if (data == -3) $(".error-message").text("Данный пользователь не найден!");
							else {
								check_role(data['role_id']);
							}
						},
						error: function (msg) {
							alert('Ошибка');
						}
					});
				}
			}
			/* проверка роли и доступ к определенным элементам */
			function check_role (role_id)
			{
				if (role_id > 0) {
					if(role_id == 1 || role_id == 2)
					{
						location.replace("/editor");
					}
					else if (role_id == 3) 
					{
						location.replace("/testing");
					}
					else if (role_id == 4) 
					{
						location.replace("/editor");
					}
					else if (role_id == 5) alert('Ты тип авторизирован!');
				}
			}
			function onkeyup_check(e) { if (e.which == 13) auth_check(); }
		</script>
	</head>
	<body>
		<div class="wrap">
			<div class="auth-block" id="auth-block">
				<figure class="auth-block-front">
					<div class="auth-block-header">
						<a class="logo" href='#'><img src="{{ asset('images/logo.png') }}" /></a>
						<h3>Авторизация</h3>
					</div>
					<div class="auth-form-block">
						<form method="POST" action="" name="AuthForm" id="AuthForm">
							{{ csrf_field() }}
							<input type="hidden" name="rid" id="rid" value="{{ $idRole }} "> 
							<input type="text" name="login" id="login" placeholder="Логин" value="" readonly onfocus="this.removeAttribute('readonly')" required/>
							<input type="password" name="pwd" id="pwd" placeholder="Пароль" value="" onfocus="this.removeAttribute('readonly')" onkeyup="onkeyup_check(event)" required/>
							<input type="button" name="ButtonFormAuth" value="Войти" class="ButtonFormAuth" id="ButtonFormAuth" onClick="auth_check()"/>
						</form>
						<div class="error-message" name="error-message" id="error-message"></div>
						<hr>
						<a href="/registration" style="color: black;">Регистрация</a>
					</div>
				</figure>
				<figure class="auth-block-back">
					<div class="change-system">
						<a href="" class="list-system" id="editor">
							<h3>Редактор тестов</h3>
							<p>(создание тестов и редактирование существующих)</p>
						</a>
						<a href="" class="list-system" id="testing">
							<h3>Тестирование</h3>
						</a>
					</div>
				</figure>
			</div>
		</div>
		<script>
			if ($("#rid").val() > 0) check_role($("#rid").val());
		</script>
	</body>
</html>