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
                            <a href="#" class="side-menu__item" onclick="shortResult();"><i class="side-menu__icon  fa fa-file-text-o"></i><span class="side-menu__label">Краткий отчет</span></a>
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
                                        <div class='row col-md-7'>
                                            <div class="col-md-3">
                                                <img src="{{ $person->photo_url }}" style='width:35mm;height:45mm;' alt="" class="d-block ui-w-100" id="photo_main">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <div>
                                                    <input style="border: transparent; background-color: transparent; color:black;" type="text" class="form-control mb-2" placeholder="Фамилия" value="{{ $person->famil }}" readonly>
                                                </div>
                                                <div>
                                                    <input style="border: transparent; background-color: transparent; color:black;" type="text" class="form-control mb-2" placeholder="Имя" value="{{ $person->name }}" readonly>
                                                </div>
                                                <div>
                                                    <input style="border: transparent; background-color: transparent; color:black;" type="text" class="form-control" placeholder="Отчество" value="{{ $person->otch }}" readonly>
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
        <div id="loadForm" style="display: none;"></div>
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-lg-12 col-sm-12   text-center">
                        © {{ date('Y', time()) }} <a href="{{ url("/") }}">ЛНУ имени Тараса Шевченко</a> <span style="color: gray;">{{ isset($ip) ? $ip : '' }}</span>
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
        var BrowserDetect = {
            init: function() {
                this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
                this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || "an unknown version";
            },
            searchString: function(data) {
                for (var i = 0; i < data.length; i++) {
                    var dataString = data[i].string;
                    var dataProp = data[i].prop;
                    this.versionSearchString = data[i].versionSearch || data[i].identity;
                    if (dataString) {
                        if (dataString.indexOf(data[i].subString) != -1)
                            return data[i].identity;
                    } else if (dataProp)
                        return data[i].identity;
                }
            },
            searchVersion: function(dataString) {
                var index = dataString.indexOf(this.versionSearchString);
                if (index == -1) return;
                return parseFloat(dataString.substring(index + this.versionSearchString.length + 1));
            },
            dataBrowser: [{
                    string: navigator.userAgent,
                    subString: "Chrome",
                    identity: "Chrome"
                },
                {
                    string: navigator.userAgent,
                    subString: "OmniWeb",
                    versionSearch: "OmniWeb/",
                    identity: "OmniWeb"
                },
                {
                    string: navigator.vendor,
                    subString: "Apple",
                    identity: "Safari",
                    versionSearch: "Version"
                },
                {
                    prop: window.opera,
                    identity: "Opera",
                    versionSearch: "Version"
                },
                {
                    string: navigator.vendor,
                    subString: "iCab",
                    identity: "iCab"
                },
                {
                    string: navigator.vendor,
                    subString: "KDE",
                    identity: "Konqueror"
                },
                {
                    string: navigator.userAgent,
                    subString: "Firefox",
                    identity: "Firefox"
                },
                {
                    string: navigator.vendor,
                    subString: "Camino",
                    identity: "Camino"
                },
                {
                    /* For Newer Netscapes (6+) */
                    string: navigator.userAgent,
                    subString: "Netscape",
                    identity: "Netscape"
                },
                {
                    string: navigator.userAgent,
                    subString: "MSIE",
                    identity: "Internet Explorer",
                    versionSearch: "MSIE"
                },
                {
                    string: navigator.userAgent,
                    subString: "Gecko",
                    identity: "Mozilla",
                    versionSearch: "rv"
                },
                {
                    /* For Older Netscapes (4-) */
                    string: navigator.userAgent,
                    subString: "Mozilla",
                    identity: "Netscape",
                    versionSearch: "Mozilla"
                }
            ]
        };
        BrowserDetect.init();
        console.log(BrowserDetect.browser);
        console.log(BrowserDetect.version);
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
                let form = document.createElement('form');
                form.action = '/test/start';
                form.method = 'POST';
                form.innerHTML = '<input name="ptid" value="' + testPersId + '">{{ csrf_field() }}';
                if (BrowserDetect.version <= 53) $('#loadForm').html(form);
            else document.body.append(form);
                form.submit();
            }
        }

        function shortResult()
        {
            let form = document.createElement('form');
            form.action = '/test/result/short';
            form.method = 'POST';
            form.target = '_blank';
            form.innerHTML = '<input name="ptid" value="' + testPersId + '">{{ csrf_field() }}';
            if (BrowserDetect.version <= 53) $('#loadForm').html(form);
            else document.body.append(form);
            form.submit();
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