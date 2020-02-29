@extends('layer')

@section('header')
    <div class="app-header header d-flex">
        <div class="container-fluid">
            <div class="d-flex">
                <a class="header-brand" href="index.html">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img main-logo" alt="Hogo logo">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img icon-logo" alt="Hogo logo">
                    <span class='logo-name'>Тестирование</span>
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
                <div class="tab-pane active">
                    <ul class="side-menu toggle-menu">
                        <li class="active">
                            <a href="#" class="side-menu__item" onclick="popupEnterProfile()"><i class="side-menu__icon  fa fa-home"></i><span class="side-menu__label">Личный кабинет</span></a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item" onclick="window.location.reload();"><i class="side-menu__icon fa fa-repeat"></i><span class="side-menu__label">Обновить</span></a>
                        </li>	
                        @if ($role_id == 1 || $role_id == 2)
                            <li class="slide">
                                <a href="{{ url("/editor") }}" class="side-menu__item"><i class="side-menu__icon  fa fa-pencil-square-o"></i><span class="side-menu__label">Перейти в "Редактор"</span></a>
                            </li>
                        @endif							 
                    </ul>
                </div>
                <div class='btn-back'>
                    <a href="/quit" class="side-menu__item"><i class="side-menu__icon fa fa-angle-left"></i><span class="side-menu__label">Выход</span></a>
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
                    <li class="breadcrumb-item"><a href="#">Главная</a></li>
                </ol>							
            </div>
            <div class="row" >
                <div class="col-md-12">
                    <div class="input-group mb-1">
                        <input type="text" class="form-control bg-white" placeholder="Найти..." onfocus="this.removeAttribute('readonly')" readonly=()>
                        <div class="input-group-append ">
                            <button type="button" class="btn btn-primary ">
                                <i class="fa fa-search " aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>	
                    <div class="persTable">					
                        @include('testing.ajax.persTable')
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
        setInterval(() => {
            window.location.reload();
        }, 300000); // каждые 5 мин обновить страницу

        var persId = 0;
        function checkedRow(obj){
            $('table tr').removeClass('active');
            obj.addClass('active');
        }
        function popupEnterProfile()
        {
            Swal.fire({
            showCancelButton: true, 
            title: 'ВВЕДИТЕ ПИН\nдля входа',
            input: 'password',
            inputPlaceholder: '******',
            inputAttributes: {
                maxlength: 10,
                autocapitalize: 'off',
                autocorrect: 'off'
            },
            cancelButtonText: 'Закрыть',
            confirmButtonText:'Вход',
            reverseButtons: true

            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/pers/check',
                        type: 'POST',
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { 
                            pid: persId, pin:result.value
                        },
                        success: function (data) {
                            if (data == 'true') window.location.replace('/pers/cabinet');
                            else Swal.fire("Внимание!", "Неверный PIN!", "error");
                        }
                    });
                }
            });
        }
    </script>
@endsection

@section('includeStyles')
    <title>Тестирование</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
    <style>
        .t-title { padding: 15px 20px; cursor: default; font-weight: 600; }
        .t-text { padding: 15px 35px; cursor: pointer;}
        table tr.active {
            background: lightgrey;
        }
        table tr.active {
            cursor: pointer;
        }
        
    </style>	
@endsection