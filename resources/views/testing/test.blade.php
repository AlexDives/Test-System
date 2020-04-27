@extends('layer')

@section('header')
    <div class="app-header header d-flex">
        <div class="container-fluid">
            <div class="d-flex">
                <a class="header-brand" href="">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img main-logo" alt="Hogo logo">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img icon-logo" alt="Hogo logo">
                    <span class='logo-name'>Тестирование</span>
                </a>
                <div class="d-flex order-lg-2 ml-auto header-rightmenu">
                    <div class="dropdown">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#" onClick="fullscreen(document.documentElement);return false;" id="fullscreen"></a>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
@endsection

@section('content')
    <div class="app-content  my-3 my-md-5 toggle-content" id='main-page' style='display: none'>
        <div class="side-app">
            <div class="page-header"></div>
            <div class="row" >
                <div class='col-md-12'>                             
                    <div class='card'>
                        <div class='card-body'>
                            <div class='row'>
                                <div class='col-md-4 text-left' @if( $role_id == 1 ) onclick="st('full');" @endif>{{ $test_name }}</div>
                                <div class='col-md-4 text-center'>
                                    <h1>
                                        <div id="countdown" class="countdown" @if( $role_id == 1 ) onclick="st('fix');" @endif>
                                            <div class="countdown-number">
                                                <span class="hours countdown-time"></span>
                                                :
                                                <span class="minutes countdown-time"></span>
                                                :
                                                <span class="seconds countdown-time"></span> 
                                            </div>
                                        </div>
                                    </h1>
                                </div>
                                <div class='col-md-4 text-right'>{{ $test->famil.' '.$test->name.' '.$test->otch }}</div>
                                <div class='col-md-12 text-center mt-5'>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row row-cards">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="pagination mg-b-0 page-0 li" style="overflow-x: auto;"></div> 
                                                            
                                                        <input type="hidden" name="tid" id="tid" value="{{$test->id}}">
                                                        <input type="hidden" name="idQuest" id="idQuest">
                                                        <input type="hidden" name="ptid" id="ptid" value="{{$pers_test_id}}">
                                            
                                                        <div class="card-body">
                                                            <div class="question" name="question" id="question"></div>
                                                            <div class='row' style='margin:15px -10px'>
                                                                <div class='col-md-6'>
                                                                    <div class="d-answer" onclick="checkedAnswer($(this));">
                                                                        <div class="q-ask" id="answer1" name="answer1"></div>
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-6 '>
                                                                    <div class="d-answer" onclick="checkedAnswer($(this));">
                                                                        <div class="q-ask" id="answer2" name="answer2"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class='row'>
                                                                <div class='col-md-6'>
                                                                    <div class="d-answer" onclick="checkedAnswer($(this));">
                                                                        <div class="q-ask" id="answer3" name="answer3"></div>
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-6'>
                                                                    <div class="d-answer" onclick="checkedAnswer($(this));">
                                                                        <div class="q-ask" id="answer4" name="answer4"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>
                                    <div class='text-center mt-5 btn-s'>
                                        <input type='hidden' name='result' id='res' readony>
                                        <button type='button' class='btn btn-success' onclick="confirmResponse();" id="confirmResposne" disabled>Подтвердить</button>
                                    </div>
                                    <div class='btn-exit mt-5 text-left'>
                                        <a href="#" onclick="fastStop();">Выход</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('includeScripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/accordion.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/quill2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert4.min.js') }}"></script>
@endsection

@section('includeBeforeScripts')
    <script src="{{ asset('js/CustomJS/testing.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <script type="text/javascript"> fillVariables({{$timeLeft}}, {{$pers_test_id}}, {{$test->id}}, {{ $minuts_spent }}, {{ $test->test_time }});</script>
    @if( $role_id == 1 )
        <script>
            function st(t) {
                var tid = $('#tid').val();
                if (t == 'full') {
                    $.ajax({
                        url: '/test/speedTest',
                        type: 'POST',
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { tid: tid },
                        success: function(data) {
                            endTest(0);
                        },
                        error: function(msg) {
                            alert('Ошибка');
                        }
                    });
                } else if ('fix') endTest(0);
            }
        </script>
    @endif
@endsection

@section('includeStyles')
    <title>Тестирование</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/quill.snow.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/quill-better-table.css') }}" rel="stylesheet">
	<style>
		.question {
			padding: 10px;
			height: 200px;
			outline: none;
			overflow-y: auto;
			overflow-x: auto;
		}
		.q-ask{
            min-height: 200px;
            height: 200px;
            outline: none;
			overflow-y: auto;
			overflow-x: auto;
        }
        
        .ql-editor, .ql-editor>* {
            -webkit-user-modify: read-only;
            cursor: pointer !important;
        }
        .ql-container.selected {
            border: 2px solid green;
        }
        #main-page {
            margin-left: 5% !important;
            margin-right: 5% !important;
        }
        h1 {
            margin-bottom: 0px;
        }
        .mt-5 {
            margin-top: 0px !important;
            margin-top: 0px !important;
        }
        .card {
            margin-bottom: 0px !important;
        }
        .page-header {
            min-height: 15px;
        }

        @media (max-width: 768px) {
            .q-ask {
                min-height: 100px;
                height: 150px;
                outline: none;
                overflow-y: auto;
                overflow-x: auto;
            }
            .card-body {
                padding: 0.5rem 0.5rem;
            }
            h1 {
                font-size: 1.5rem;
            }
            .countdown span:first-child {
                font-size: 1.5rem;
            }
            .page-header {
                min-height: 5px;
            }
        }
	</style>
@endsection