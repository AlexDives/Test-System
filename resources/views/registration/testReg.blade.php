@extends('layer')

@section('header')
	<div class="app-header header d-flex">
		<div class="container-fluid">
			<div class="d-flex">
			    <a class="header-brand" href="/" style="width:100%;text-align:center">
					<img src="{{ asset('images/logo.png') }}" class="header-brand-img main-logo" alt="Hogo logo">
					<img src="{{ asset('images/logo.png') }}" class="header-brand-img icon-logo" alt="Hogo logo">
					<span class='logo-name'>Регистрация</span>
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
										<input type="password" class="form-control" name="pass" id="pass" placeholder="">
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
											<!--<div class='col-md-12 mb-2' style="display:none;">
												<label class="form-label">Серия паспорта</label>
												<input type="text" class="form-control" name="serp" id="serp" placeholder="" >
											</div>-->
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
											<!--<div class='col-md-12 mb-2'  style="display:none;">
												<label class="form-label">Номер паспорта</label>
												<input type="text" class="form-control" name="nump" id="nump" placeholder="" >
											</div>-->
										</div>												 
									</div>										
								</div>
							</div>
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
	<script src="{{ asset('js/toastr.js') }}"></script>
	<script src="{{ asset('js/CustomJS/registration.js') }}"></script>
	<script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('includeStyles')
    <title>Регистрация</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
	<link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
@endsection