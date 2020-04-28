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
		<title>Восстановление пароля</title>
    	<script src="{{ asset('js/jquery.js') }}"></script>
		<script>
            var blockedEmail = false;
            var blockedLogin = false;
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
                            blockedLogin = true;
                        } else {
                            blockedLogin = false;
                        }
                        if (blockedEmail && blockedLogin) $('#password').show();
                        else $('#password').hide();
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
                            blockedEmail = true;
                        } else {
                            blockedEmail = false;
                        }
                        if (blockedEmail && blockedLogin) $('#password').show();
                        else $('#password').hide();
                    },
                    error: function(msg) {
                        alert('Error, try again');
                    }
                });
            }

            function check_pass()
            {
                if ($('#password').val().trim() != '') $('#ButtonFormAuth').prop('disabled', false);
                else $('#ButtonFormAuth').prop('disabled', true);
            }

            function reset_pwd()
            {
                $.ajax({
                    url: '/reset_pwd/reset',
                    type: 'POST',
                    data: $('#resetForm').serialize(),
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data == -1) {
                            Alert('Произошла ошибка при смене пароля, повторите попытку!');
                        } else {
                            window.location.href = "/";
                        }
                    },
                    error: function(msg) {
                        alert('Error, try again');
                    }
                });
            }
		</script>
	</head>
	<body>
		<div class="wrap">
			<div class="auth-block" id="auth-block">
				<figure class="auth-block-front">
					<div class="auth-block-header">
						<a class="logo" href='#'><img src="{{ asset('images/logo.png') }}" /></a>
					</div>
					<div class="auth-form-block">
						<form method="POST" action="" name="resetForm" id="resetForm">
							{{ csrf_field() }}
							<input type="text" name="login" id="login" placeholder="Логин" value="" onkeyup="check_login()" required/>
							<input type="email" name="email" id="email" placeholder="Email" value="" onkeyup="check_email()" required/>
							<input type="password" name="password" id="password" placeholder="Новый пароль" value="" style="display: none;" onkeyup="check_pass()" required/>
							<input type="button" name="ButtonFormAuth" value="Восстановить пароль" class="ButtonFormAuth" id="ButtonFormAuth" onClick="reset_pwd()" disabled />
						</form>
					</div>
				</figure>
			</div>
		</div>
	</body>
</html>