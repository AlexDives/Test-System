<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="Список тестов">
		<meta name="author" content="ЛНУ имени Тараса Шевченко">
		<meta name="keywords" content="Список тестов"/>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link href="{{ asset('images/favicon.ico') }}" rel="icon" type="image/x-icon"/>
		<link href="{{ asset('images/favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
		@yield('includeStyles')
		@yield('includeScripts')
	</head>

	<body class="app sidebar-mini rtl">
		@if(Route::currentRouteName() != 'admin.statistic')
			<div id="global-loader">
				<img src="{{ asset('images/loader.svg') }}" alt="loader">
			</div>
		@endif
		<div class="page">
			<div class="page-main">
				@yield('header')
				@yield('mainMenu')
				@yield('content')
			</div>
		</div>
		<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
		@yield('includeBeforeScripts')
	</body>
</html>