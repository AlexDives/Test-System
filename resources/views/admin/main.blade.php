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
                        <li class="slide">
                            <a href="#" class="side-menu__item" id="sendAllMail" onclick="sendAllMail();"><i class="side-menu__icon  fa fa-refresh"></i><span class="side-menu__label">Рассылка на E-mail</span></a>
                        </li>	
                        <li class="slide">
                            <a href="#" class="side-menu__item" ></a>
                        </li>	
                        <li class="slide">
                            <a href="#" class="side-menu__item" id="sendAllMail" onclick="sendAllMailWithAttach();"><i class="side-menu__icon  fa fa-refresh"></i><span class="side-menu__label">E-mail с файлом</span></a>
                        </li>	
                        <li class="slide">
                            <a href="#" class="side-menu__item" ></a>
                        </li>	
                        <li class="slide">
                            <a href="/admin/statistic" class="side-menu__item" id="statistic"><i class="side-menu__icon  fa fa-refresh"></i><span class="side-menu__label">Статистика</span></a>
                        </li>	
                        <li class="slide">
                            <a href="#" class="side-menu__item" ></a>
                        </li>	
                        <li class="slide">
                            <a href="{{ url("/editor") }}" class="side-menu__item" id="newTest"><i class="side-menu__icon fa fa-file-o"></i><span class="side-menu__label">Список тестов</span></a>
                        </li>
                        @if ($role == 1)
                            <li class="slide">
                                <a href="{{ url("/manager") }}" class="side-menu__item"><i class="side-menu__icon  fa fa-file-text-o"></i><span class="side-menu__label">Менеджер пользователей</span></a>
                            </li>
                        @endif	
                        @if ($role == 1 || $role == 2)
                            <li class="slide">
                                <a href="{{ url("/pers/list") }}" class="side-menu__item"><i class="side-menu__icon  fa fa-pencil-square-o"></i><span class="side-menu__label">Перейти в "Тестирование"</span></a>
                            </li>
                        @endif		
                    </ul>
                </div>
                <div class='btn-back'>
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
                <li class="breadcrumb-item"><a href="#">Список зарегистрировашихся</a></li>
            </ol>							
        </div>
        <div class='row'>
            <div class='col-md-12'>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class="card">									
                            <div class="table-responsive">
                                <ul class="open-list accordionjs m-0" data-active-index="false">
                                    <li>
                                        <div class='acc_head head_test' style='background: #fff !important'>
                                            <h3>
                                                <div class='row'>
                                                    <div class='col-md-1 col-2'>№</div>
                                                    <div class='col-md-3 col-5'>ФИО</div>
                                                    <div class='col-md-1 col-2'>PIN</div>
                                                    <div class='col-md-3 m-h'>E-mail</div>
                                                    <div class='col-md-2 m-h'>Дата регистрации</div>
                                                    <div class='col-md-1 m-h'>Статус</div>
                                                </div>
                                            </h3>
                                        </div>
                                    </li>
                                </ul>
                                <div id="testListAjax">
                                    @include('admin.ajax.listPers')
                                </div>	
                            </div>
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
        function sendMail()
        {
            if ($('#theme').val() != '' && $('#texta').val() != ''){
                $.ajax({
                    url: '/admin/sendAllMail',
                    type: 'POST',
                    data: {
                        theme : $('#theme').val(),
                        texta : $('#texta').val()
                    },
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data == -1) Swal.fire('Сообщение НЕ отправлено!', 'По техническим причинам, сообщение не было отправлено. Напишите письмо на E-mail: asu@ltsu.org', 'error');
                        else Swal.fire('Сообщение отправлено!', 'Рассылка прошла успешно!', 'success');
                    }
                });
            }
        }
        function sendAllMailWithAttach()
        {
            $.ajax({
                url: '/admin/sendAllMailWithAttach',
                type: 'POST',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data == -1) Swal.fire('Сообщение НЕ отправлено!', 'По техническим причинам, сообщение не было отправлено. Напишите письмо на E-mail: asu@ltsu.org', 'error');
                    else Swal.fire('Сообщение отправлено!', 'Рассылка прошла успешно!', 'success');
                }
            });
        }
        function sendAllMail()
        {
            Swal.fire({
                title: 'E-mail рассылка',
                showCloseButton: true,
                html: '<div class="row">' +
                    '<div class="col-md-12 mb-2"><b>Тема:</b></div>' +
                    '<div class="col-md-12 mb-2"><input type="text" class="form-control" name="theme" id="theme" maxlength="50"></div>' +
                    '</div>' +
                    '<div class="row">' +
                    '<div class="col-md-12 mb-2"><b>Сообщение:</b></div>' +
                    '<div class="col-md-12 mb-2"><textarea class="form-control" rows="3" name="texta" id="texta"></textarea></div>' +
                    '</div>',
                showCancelButton: false,
                focusConfirm: true,
                cancelButtonText: 'Отмена',
                confirmButtonText: 'Отправить',
            }).then((result) => {
                if (result.value) {
                    sendMail();
                }
            });
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