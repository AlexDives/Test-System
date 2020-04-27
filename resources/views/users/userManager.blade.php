@extends('layer')

@section('header')
<div class="app-header header d-flex">
    <div class="container-fluid">
        <div class="d-flex">
            <a class="header-brand" href="index.html">
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
                        <a href="#" class="side-menu__item" onclick="newUser();"><i class="side-menu__icon fa fa-play"></i><span class="side-menu__label">Новый пользователь</span></a>
                    </li>
                    <li class="slide">
                        <a href="#" class="side-menu__item" onclick="saveUser();"><i
                                class="side-menu__icon  fa fa-repeat"></i><span
                                class="side-menu__label">Сохранить</span></a>
                    </li>
                    <li class="slide">
                        <a href="#" class="side-menu__item" onclick="deleteUser();"><i
                                class="side-menu__icon  fa fa-file-text-o"></i><span
                                class="side-menu__label">Удалить</span></a>
                    </li>
                </ul>
            </div>
            <div class='btn-back'>
                <a href="../editor" class="side-menu__item"><i class="side-menu__icon fa fa-angle-left"></i><span
                        class="side-menu__label">Назад</span></a>
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
                <li class="breadcrumb-item"><a href="#">Менеджер пользователей</a></li>
            </ol>
        </div>
        <div class="row">
            <div class='col-md-12'>
                <div class='row'>
                    <div class='col-md-12'  id="userTable" name="userTable"></div>
                </div>
                <div class='row'>
                    <div class="col-md-12">
                        <div class="card">
                            <a class='card-body' name="mainInfoUser">
                                <form action="" method="POST" id="userform">
                                    {{ csrf_field() }}
                                    <div class='row'>
                                        <div class='col-md-6'>
                                            <div class="form-group">
                                                <label class="form-label">Логин</label>
                                                <input type="text" class="form-control" name="login" placeholder=""
                                                    id="login">
                                                <input type="hidden" id="uid" name="uid" value="0">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Пароль</label>
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="" id="password">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Повторить Пароль</label>
                                                <input type="password" class="form-control" name="password_two"
                                                    placeholder="" id="password_two">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Секретный вопрос</label>
                                                <input type="text" class="form-control" name="secret_question"
                                                    placeholder="" id="secret_question">
                                            </div>
                                        </div>
                                        <div class='col-md-6'>
                                            <div class="form-group">
                                                <label class="form-label">Фамилия</label>
                                                <input type="text" class="form-control" name="famil" placeholder=""
                                                    id="famil">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Имя</label>
                                                <input type="text" class="form-control" name="name" placeholder=""
                                                    id="name">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Отчество</label>
                                                <input type="text" class="form-control" name="otch" placeholder=""
                                                    id="otch">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Ответ на секретный вопрос</label>
                                                <input type="text" class="form-control" name="secret_answer"
                                                    placeholder="" id="secret_answer">
                                            </div>
                                        </div>
                                    </div>
                                    <HR>
                                    <div class='row'>
                                        <div class='col-md-12'>
                                            <div class="form-group">
                                                <label class="form-label">Место работы</label>
                                                <input type="text" class="form-control" name="workplace" placeholder=""
                                                    id="workplace">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Должность</label>
                                                <input type="text" class="form-control" name="doljn" placeholder=""
                                                    id="doljn">
                                            </div>
                                        </div>
                                        <div class='col-md-6'>
                                            <div class="form-group">
                                                <label class="form-label">№ Кабинета</label>
                                                <input type="text" class="form-control" name="aud_num" placeholder=""
                                                    id="aud_num">
                                            </div>
                                        </div>
                                        <div class='col-md-6'>
                                            <div class="form-group">
                                                <label class="form-label">№ Телефона</label>
                                                <input type="text" class="form-control" name="tel_num" placeholder=""
                                                    id="tel_num">
                                            </div>
                                        </div>
                                        <div class='col-md-6'>
                                            <div class="form-group">
                                                <label class="form-label">Роль</label>
                                                <select name="roles" class="form-control custom-select" id="roles">
                                                    @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class='col-md-6'>
                                            <div class='form-group' style='margin: 35px 0px 0px 0px;'>
                                                <label class="custom-switch">
                                                    <input type="checkbox" name="is_block" id="is_block"
                                                        class="custom-switch-input" checked>
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="custom-switch-description">Активировать /
                                                        Деактивировать</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </a>
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
                    © {{ date('Y', time()) }} <a href="{{ url("/") }}">ЛНУ имени тараса Шевченко</a>
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
<script src="{{ asset('js/CustomJS/userManager.js') }}"></script>
@endsection

@section('includeStyles')
<title>Менеджер пользователей</title>
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/style.css') }}" rel="stylesheet" />
<link href="{{ asset('css/CustomCSS/userManager.css') }}" rel="stylesheet" />
<link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
@endsection