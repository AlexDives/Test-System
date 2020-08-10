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
                        <li class="slide start_test" style="display: none;">
                            <a href="#" class="side-menu__item" onclick="startTest();"><i class="side-menu__icon fa fa-play"></i><span class="side-menu__label">Начать тест</span></a>
                        </li>
                        <li class="active show_start">
                            <a href="#" class="side-menu__item" onclick="showEvents();"><i class="side-menu__icon fa fa-calendar"></i><span class="side-menu__label">Мероприятия</span></a>
                        </li>
                        <li class="slide">
                            <a href="" class="side-menu__item"><i class="side-menu__icon fa fa-repeat"></i><span class="side-menu__label">Обновить</span></a>
                        </li>
                        <li class="slide exam_list" style="display: none;">
                            <a href="#" class="side-menu__item" onclick="popupPdf();"><i class="side-menu__icon  fa fa-file-text-o"></i><span class="side-menu__label">Экзаменационный лист</span></a>
                        </li>
                        <li class="slide show_report" style="display: none;">
                            <a href="#" class="side-menu__item" onclick="shortResult();"><i class="side-menu__icon  fa fa-file-text-o"></i><span class="side-menu__label">Краткий отчет</span></a>
                        </li>								 
                    </ul>
                </div>
                <div class='btn-back'>
                    <a href="#" class="side-menu__item" onclick="supportPopup()"><i class="side-menu__icon fa fa-angle"></i><span class="side-menu__label">Тех. поддержка</span></a>
                    <a href="/quit" class="side-menu__item"><i class="side-menu__icon  fa fa-sign-out"></i><span class="side-menu__label">Выйти</span></a>
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
                                            <div style='width:35mm;height:45mm;background:url("{{ $person->photo_url }}");border:1px solid #eee;float:left;margin-right:15px; background-size:cover'></div>
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
                                    <div class="row" style="justify-content: center;">
                                        <button type="button" class="btn btn-info" onclick="showEvents();">Мероприятия</button>
                                    </div>
                                </div>			 				 
                            </div>
                        </div>
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
                                                                <div class='col-md-4 col-5'>Мероприятие</div>
                                                                <div class='col-md-4 m-h'>Начало мероприятия</div>
                                                                <div class='col-md-3 m-h'>Конец мероприятия</div>
                                                            </div>
                                                        </h3>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div id="testListAjax">
                                                @include('persons.ajax.persEvents')
                                            </div>	
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hhidde" style="visibility: hidden">
            <div class="row">
                <div class="col-md-12 mb-2"><b>Вы успешно зарегистрировались на <label id="event_name"></label>:</b></div>
                <div class="col-md-12 mb-2" style="font-size:14px"><b>Для участия Вам потребуется:</b></div>
            </div>
            <div class="row">	
                <div class="row">
                    <div class="col-md-12 mb-2" style="text-align: justify; line-height: 16px; font-size: 13px;"><ol>
                    <li><b>Скачать и распечатать</b> "Экзаменационный лист", в котором указаны Ваши данные и <b>персональный PIN</b>.</li>
                    <li class="mt-2"><label id="event_name3"></label> будет проходить <b><label id="event_date"></b> в On-line режиме.</li>
                    <li class="mt-2">Провести хорошо время</li></ol></div>'
                </div>			    	
                <!--<div class="col-md-12 mb-2" style="text-align: justify; line-height: 16px; font-size: 13px;"><ol>                
                <li><b>Скачать и распечатать</b> "Экзаменационный лист", в котором указаны Ваши данные и <b>персональный PIN</b>. Без "Экзаменационного листа" Вас <b>не допустят к тестированию.</b></li>
                <li class="mt-2"><b>Прийти</b> для подтверждения регистрации на <label id="event_name3"></label>, которое будет проходить <b><label id="event_date"></label></b>, по адресу г. Луганск,  ул. Оборонная 2: учебный корпус №2 , 2-й этаж, каб. 270</li>
                <li class="mt-2">Провести хорошо время</li></ol></div>-->
            </div>
            <div class="row">
            <div class="col-md-4"><button class="btn btn-primary" onclick="createPdf(0);">Открыть</button></div>
            <div class="col-md-4"><button class="btn btn-primary" onclick="createPdf(1);">Скачать</button></div>
            <div class="col-md-4"><button class="btn btn-primary" onclick="Swal.close()">Отмена</button></div>
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
    <script src="{{ asset('/js/script.js') }}"></script>
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
        function selectedEvent(data)
        {
            let form = document.createElement('form');
            form.action = '/persons/regevent';
            form.method = 'POST';
            form.innerHTML = '<input name="eid" value="' + data + '">{{ csrf_field() }}';

            // перед отправкой формы, её нужно вставить в документ
            document.body.append(form);

            form.submit();
        }
        function showEvents() {
            $.ajax({
                url: '/persons/events',
                type: 'GET',
                success: function(html) {
                    popup(html);
                }
            });
        }
        function popup(data) {
            Swal.fire({
                title: 'Мероприятия',
                showCloseButton: true,
                html: data,
                showCancelButton: false,
                focusConfirm: false,
            }).then((result) => {
                
            })
        }
        var peid = 0;
        function createPdf(status)
        {
            let form = document.createElement('form');
            form.action = '/persons/createPdf';
            form.method = 'POST';
            form.target = '_blank';
            form.innerHTML = '<input name="peid" value="' + peid + '"><input name="status" value="' + status + '">{{ csrf_field() }}';
            // перед отправкой формы, её нужно вставить в документ
            if (BrowserDetect.version <= 53) $('#loadForm').html(form);
            else document.body.append(form);
            form.submit();
       }
        function popupPdf() {
            Swal.fire({
              title: '',				  
              showCloseButton: false,  
              html:$('.hhidde').html(),
              showCancelButton: false,
              showConfirmButton: false,
              focusConfirm: false,
              cancelButtonText: 'Отмена',
              confirmButtonText:'Отправить',			   
            });
        }
        function supportPopup() {
            Swal.fire({
                title: 'Техническая поддержка',
                showCloseButton: true,
                html: '<div class="row">' +
                    '<div class="col-md-12 mb-2"><b>Тема:</b></div>' +
                    '<div class="col-md-12 mb-2"><input type="text" class="form-control" name="theme" id="theme" maxlength="50"></div>' +
                    '</div>' +
                    '<div class="row">' +
                    '<div class="col-md-12 mb-2"><b>Сообщение:</b></div>' +
                    '<div class="col-md-12 mb-2"><textarea class="form-control" rows="3" name="texta" id="texta" maxlength="500"></textarea></div>' +
                    '<div class="col-md-12 mb-2"><font color="red">*</font> <font style="font-size: 13px;">Данная форма предназначена только для технических вопросов сайта!</font><br>' +
                    '<font color="red">**</font> <font style="font-size: 13px;">По всем другим вопросам обращаться в приемную комиссию!</font></div>' +
                    '</div>',
                showCancelButton: false,
                focusConfirm: true,
                cancelButtonText: 'Отмена',
                confirmButtonText: 'Отправить',
            }).then((result) => {
                if (result.value) {
                    sendReuqest();
                }
            });
        }
        function sendReuqest()
        {
            if ($('#theme').val() != '' && $('#texta').val() != ''){
                $.ajax({
                    url: '/persons/sendRequest',
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
                        else Swal.fire('Сообщение отправлено!', 'Ожидайте ответ на свой E-mail адрес.', 'success');
                    }
                });
            }
        }
        function popupEnterProfile(persId)
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
        testPersId = 0;
        gStatus = 2;
        function selected_test(ptid, obj, is_active, status)
        {
            $('table tr').removeClass('active');
            obj.addClass('active');
            if (is_active)
            {
                if (status != 2) $('.start_test').show();
                else $('.start_test').hide();
            }
            if(status == 2) $('.show_report').show();
            else $('.show_report').hide();
            testPersId = ptid;
            gStatus = status;
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