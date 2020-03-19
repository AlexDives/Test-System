@extends('layer')

@section('header')
    <div class="app-header header d-flex">
        <div class="container-fluid">
            <div class="d-flex">
                <a class="header-brand" href="{{ url("/editor") }}">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img main-logo" alt="Hogo logo">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img icon-logo" alt="Hogo logo">
                    <span class='logo-name'>Редактор тестов</span>
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
                        <li class="active">
                            <a href="{{ url("/info/new") }}" class="side-menu__item" id="newTest"><i class="side-menu__icon fa fa-file-o"></i><span class="side-menu__label">Новый тест</span></a>
                        </li>
                        <li class="slide" style="display: none;" id="slideEdit">
                            <a href="#" class="side-menu__item" id="changedTest"><i class="side-menu__icon  fa fa-file-code-o"></i><span class="side-menu__label">Редактировать</span></a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item" id="refreshList" onclick="searchTest();"><i class="side-menu__icon  fa fa-refresh"></i><span class="side-menu__label">Обновить</span></a>
                        </li>
                        @if ($role_id == 1)
                            <li class="slide">
                                <a href="{{ url("/manager") }}" class="side-menu__item"><i class="side-menu__icon  fa fa-file-text-o"></i><span class="side-menu__label">Менеджер пользователей</span></a>
                            </li>
                        @endif	
                        @if ($role_id == 1 || $role_id == 2)
                            <li class="slide">
                                <a href="{{ url("/pers/list") }}" class="side-menu__item"><i class="side-menu__icon  fa fa-pencil-square-o"></i><span class="side-menu__label">Перейти в "Тестирование"</span></a>
                            </li>
                        @endif							 
                    </ul>
                </div>
                <div class='btn-back'>
                    <a href="#" class="side-menu__item" onclick="supportPopup()"><i class="side-menu__icon fa fa-angle"></i><span class="side-menu__label">Тех. поддержка</span></a>
                    <a href="{{ url("/quit") }}" class="side-menu__item"><i class="side-menu__icon fa fa-angle-left"></i><span class="side-menu__label">Выйти</span></a>
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
                <li class="breadcrumb-item"><a href="#">Список тестов</a></li>
            </ol>							
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="" method="post" id="search" name="search" class="input-group mb-1">
                    {{ csrf_field() }}
                    <input type="text" class="form-control bg-white" name="searchTestText" id="searchTestText" placeholder="Найти..." onkeyup="searchTest();">
                    <div class="input-group-append ">
                        <button type="button" class="btn btn-primary" onclick="searchTest();">
                            <i class="fa fa-search " aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
                <div class="card">									
                    <div class="table-responsive">
                        <ul class="open-list accordionjs m-0" data-active-index="false">
                            <li>
                                <div class='acc_head head_test' style='background: #fff !important'>
                                    <h3>
                                        <div class='row'>
                                            <div class='col-md-1 col-2'>№</div>
                                            <div class='col-md-4 col-5'>Дисциплина</div>
                                            <div class='col-md-2 col-5'>Целевая аудитория</div>
                                            <div class='col-md-2 col-5'>Тип теста</div>
                                            <div class='col-md-3 m-h'>Дата создания</div>
                                        </div>
                                    </h3>
                                </div>
                            </li>
                        </ul>
                        <div id="testListAjax">
                            @include('editor.ajax.testList')
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
    <script src="{{ asset('js/CustomJS/editorMain.js') }}"></script>
    <script src="{{ asset('js/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert4.min.js') }}"></script>
	<script src="{{ asset('js/toastr.js') }}"></script>
@endsection

@section('includeStyles')
    <title>Список тестов</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
@endsection