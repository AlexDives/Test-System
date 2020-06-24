@extends('layer')

@section('header')
	<div class="app-header header d-flex">
		<div class="container-fluid">
			<div class="d-flex">
				<a class="header-brand" href="{{ url("/editor") }}">
					<img src="{{ asset('images/logo.png') }}" class="header-brand-img main-logo" alt="Hogo logo">
					<img src="{{ asset('images/logo.png') }}" class="header-brand-img icon-logo" alt="Hogo logo">
					<span class='logo-name'>Админ панель</span>
				</a>
				<div class="d-flex order-lg-2 ml-auto header-rightmenu"></div>
			</div>
		</div>
	</div>
	<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
@endsection

@section('mainMenu')
	<aside class="app-sidebar toggle-sidebar">
		<div class="app-sidebar__user pb-0"></div>
		<div class="panel-body tabs-menu-body side-tab-body p-0 border-0 ">
			<div class="tab-content">
				<div class="tab-pane active" id="index1">
					<ul class="side-menu toggle-menu">
						<li class="slide">
							<a href="#" class="side-menu__item" id="printSelected" onclick="printSelected();"><i class="side-menu__icon fa fa-print"></i><span class="side-menu__label">Печать выбранного</span></a>
						</li>
						<li class="slide">
							<a href="#" class="side-menu__item" id="printAll" onclick="printAll();"><i class="side-menu__icon fa fa-print"></i><span class="side-menu__label">Печать всех</span></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</aside>
@endsection

@section('content')
<div class="app-content  my-3 my-md-5 toggle-content">
	<div class="side-app">
		<div class="page-header">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="#">Список зарегистрировашихся</a></li>
				<select name="groupList" onchange="change_group();" class="form-control custom-select" id="groupList">
					<option value="-1" selected>Все</option>
					@foreach ($groups as $g)
						<option value="{{ $g->study_place }}">{{ 'Группа '.$g->study_place }}</option>
					@endforeach
				</select>
			</ol>
		</div>
		<div class='row'>
			<div class='col-md-12'>
				<div class='row'>
					<div class='col-md-12'>
						<div id="testListAjax">
						</div>
					</div>
				</div>
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
	<script src="{{ asset('js/jquery.sweet-modal.min.js') }}"></script>
	<script src="{{ asset('js/sweetalert4.min.js') }}"></script>
	<script src="{{ asset('js/toastr.js') }}"></script>
	<script>
		ptid = 0;
		$(document).ready(function() {
			
			change_group();
		});
		function change_group()
		{
			var gid = $('#groupList').val();
			$.ajax({
				url: '/gia/adm/result/get',
				type: 'POST',
				data: {
					gid : gid
				},
				headers: {
					'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(data) {
					$('#testListAjax').html(data);
				}
			});
		}
		function printSelected()
        {
            let form = document.createElement('form');
            form.action = '/gia/print/result/pers';
            form.method = 'POST';
            form.target = '_blank';
            form.innerHTML = '<input name="ptid" value="' + ptid + '">{{ csrf_field() }}';
            document.body.append(form);
            form.submit();
		}
		
		function printAll()
        {
			var gid = $('#groupList').val();
            let form = document.createElement('form');
            form.action = '/gia/print/result/all';
            form.method = 'POST';
            form.target = '_blank';
            form.innerHTML = '<input name="gid" value="' + gid + '">{{ csrf_field() }}';
            document.body.append(form);
            form.submit();
        }
	</script>
@endsection

@section('includeStyles')
	<title>Админ панель</title>
	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/style.css') }}" rel="stylesheet" />
	<link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
@endsection