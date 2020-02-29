@extends('layer')

@section('header')
    <div class="app-header header d-flex">
        <div class="container-fluid">
            <div class="d-flex">
                <a class="header-brand" href="index.html">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img main-logo" alt="Hogo logo">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img icon-logo" alt="Hogo logo">
                    <span class='logo-name'>Личный кабинет</span>
                </a>
                <div class="d-flex order-lg-2 ml-auto header-rightmenu">
                    <div class="dropdown">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#"></a>
                    </div>
                </div>
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
                        <li class="active show_start" style="display: none;">
                            <a href="#" class="side-menu__item" onclick="startTest();"><i class="side-menu__icon fa fa-play"></i><span class="side-menu__label">Начать тест</span></a>
                        </li>
                        <li class="slide">
                            <a href="" class="side-menu__item"><i class="side-menu__icon  fa fa-repeat"></i><span class="side-menu__label">Обновить</span></a>
                        </li>
                        <li class="slide show_report" style="display: none;">
                            <a href="#" class="side-menu__item" ><i class="side-menu__icon  fa fa-file-text-o"></i><span class="side-menu__label">Краткий отчет</span></a>
                        </li>
                        @if ($role == 1 || $role == 2 || $role == 3)
                            <li class="slide show_report" style="display: none;">
                                <a href="#" class="side-menu__item"><i class="side-menu__icon  fa fa-file-text-o"></i><span class="side-menu__label">Полный отчет</span></a>
                            </li>
                        @endif
                        								 
                    </ul>
                </div>
                <div class='btn-back'>
                    <a href="/pers/list" class="side-menu__item"><i class="side-menu__icon  fa fa-sign-out"></i><span class="side-menu__label">Выйти</span></a>
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
                    <li class="breadcrumb-item"><a href="#">Личный кабинет</a></li>
                </ol>							
            </div>
            <div class="row" >
                <div class='col-md-12'>
                    <div class='row'>
                        <div class="col-md-12">
                            <div class="card">									
                                <div class='card-body'>
                                    <div class='row'>
                                        <div class='col-md-7'>
                                            <div style='width:35mm;height:45mm;background:url("data:image/png;base64,{{ base64_encode($person->photo) }}");border:1px solid #eee;float:left;margin-right:15px; background-size:cover'></div>
                                            <div class="form-group" style='float:left'>
                                                <div>
                                                    <input type="text" class="form-control mb-2" placeholder="Фамилия" value="{{ $person->famil }}" readonly>
                                                </div>
                                                <div>
                                                    <input type="text" class="form-control mb-2" placeholder="Имя" value="{{ $person->name }}" readonly>
                                                </div>
                                                <div>
                                                    <input type="text" class="form-control" placeholder="Отчество" value="{{ $person->otch }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-5'>
                                            <div style='text-align: center;width:100%;height:100%;padding-top:50px'>
                                                <h1>PIN {{ $person->PIN }}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>			 				 
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            <div class='card'>
                                @include('testing.ajax.persTests')
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
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert4.min.js') }}"></script>
    <script>
        var testPersId = 0;
        var gStatus = 0
        function checkedRow(obj, status){
            $('table tr').removeClass('active');
            obj.addClass('active');
            if (status == 2) 
            {
                $('.show_report').show();
                $('.show_start').hide();
            }
            else 
            {
                $('.show_report').hide();
                $('.show_start').show();
                gStatus = status;
            }
        }
        
        function startTest()
        {
            if (testPersId != 0 && gStatus != 2)
            {
                window.location.replace('/test/start?ptid='+testPersId);
            }
        }
    </script>
@endsection

@section('includeStyles')
    <title>Личный кабинет</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
    <style>
        table tr.active {
            background: lightgrey;
        }
        table tr {
            cursor: pointer;
        }
        input, h1 {
            cursor: default;
        }
    </style>	
@endsection