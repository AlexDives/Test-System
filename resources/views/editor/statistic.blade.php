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
@endsection

@section('mainMenu')
    <aside class="app-sidebar toggle-sidebar">
        <div class="app-sidebar__user pb-0"></div>
        <div class="panel-body tabs-menu-body side-tab-body p-0 border-0 ">
            <div class="tab-content">
                <div class="tab-pane active" id="index1">
                    <ul class="side-menu toggle-menu">
                        <li class="active">
                        <a href="{{ url("/info") }}?id={{ $tid }}" class="side-menu__item"><i class="side-menu__icon fa fa-info-circle"></i><span class="side-menu__label">Информация</span></a>
                        </li>
                        <li class="slide">
                            <a href="{{ url("/questions") }}?id={{ $tid }}" class="side-menu__item"><i class="side-menu__icon  fa fa-question-circle"></i><span class="side-menu__label">Вопросы</span></a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item active"><i class="side-menu__icon  fa fa-pie-chart"></i><span class="side-menu__label">Статистика</span></a>
                        </li>							 
                    </ul>
                </div>
                <div class='btn-back'>
                    <a href="{{ url("/editor") }}" class="side-menu__item"><i class="side-menu__icon fa fa-angle-left"></i><span class="side-menu__label">Назад</span></a>
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
                <li class="breadcrumb-item"><a href="#">Статистика теста - {{ $testName }}</a></li>
            </ol>							
        </div>
        <div class='row'>
            <div class='col-md-6'>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='card'>
                            <form method="post" active="" class='card-body' id="statsForm" name="statsForm">
                                {{ csrf_field() }}
                                <input type="hidden" name="tid" id="tid" value={{ $tid }}>
                                <div>
                                    <input type='radio' class='r-ask' id='typeStat1' name='typeStat' value="1" onclick="checkStats()" checked>
                                    <label for='typeStat1' class='mb-0'>Статистика за весь период</label> 
                                </div>
                                <div>
                                    <input type='radio' class='r-ask' id='typeStat2' name='typeStat' value="2" onclick="checkStats()">
                                    <label for='typeStat2' class='mb-0'>Статистика за месяц</label> 
                                </div>
                                <div>
                                    <input type='radio' class='r-ask' id='typeStat3' name='typeStat' value="3" onclick="checkStats()">
                                    <label for='typeStat3' class=''>Статистика за промежуток:</label> 
                                    <div class="form-inline">
                                        <label for="dateStart" class="col-1 col-form-label">с</label>
                                        <div class="col-5">
                                          <input class="form-control customDate" type="date" id="dateStart">
                                        </div>
                                        <label for="dateEnd" class="col-1 col-form-label">по</label>
                                        <div class="col-5">
                                          <input class="form-control customDate" type="date" id="dateEnd">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 				
            </div>
        </div>
        <div class='row'>
            <div class='col-md-6'>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='card'>
                            <div class='card-body'>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <div class="block_h n1">Общее количество прохождения теста:</div> 
                                        <div class="block_t" id="countPassage">1000</div>
                                    </div>	
                                    <div class='col-md-12'>
                                        <div class="block_h n1">Количество успешного прохождения:</div>   
                                        <div class="block_t" id="countTruePassage">5000</div>
                                    </div>	
                                    <div class='col-md-12'>												
                                        <div class="block_h n1">Количество провального прохождения:</div> 
                                        <div class="block_t" id="countFalsePassage">5000</div>
                                    </div>						
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='card'>
                            <div class='card-body'>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <div class="block_h n2">Максимальный набранный бал:</div>  
                                        <div class="block_t" id="maxBall">100</div>
                                    </div>	
                                    <div class='col-md-12'>
                                        <div class="block_h n2">Минимальный набранный бал: </div>   
                                        <div class="block_t" id="minBall">24</div>
                                    </div>	
                                    <div class='col-md-12'>
                                        <div class="block_h n2">Средний набранный бал:     </div>     
                                        <div class="block_t" id="avgBall">55</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='card'>
                            <div class='card-body'>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <div class="block_h n3">Количество людей набравших 90-100 баллов:</div>  
                                        <div class="block_t" id="ball90100">100</div>
                                    </div>	
                                    <div class='col-md-12'>
                                        <div class="block_h n3">Количество людей набравших 70-89 баллов:</div>   
                                        <div class="block_t" id="ball7089">314</div>
                                    </div>	
                                    <div class='col-md-12'>
                                        <div class="block_h n3">Количество людей набравших 50-69 баллов:</div>     
                                        <div class="block_t" id="ball5069">545</div>
                                    </div>
                                    <div class='col-md-12'>
                                        <div class="block_h n3">Количество людей набравших 24-49 баллов:</div>     
                                        <div class="block_t" id="ball2449">6545</div>
                                    </div>
                                    <div class='col-md-12'>
                                        <div class="block_h n3">Количество людей набравших 0-23 балла:</div>     
                                        <div class="block_t" id="ball0023">10</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>				 
            </div>
            <div class='col-md-6'>    
                <div class="text-center">
                    <div id="statistic" class="chartsh"></div>
                    <p>График набранных баллов</p>
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

    <script src="{{ asset('js/stats.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/export-data.js') }}"></script>
    <script src="{{ asset('js/highcharts/histogram-bellcurve.js') }}"></script>
    <script src="{{ asset('js/highcharts/solid-gauge.js') }}"></script>   

    <script>
        $(document).ready(function() {
            checkStats();
        });
        function checkStats()
        {
            $.ajax({
                url: '/statistic/refresh',
                type: 'POST',
                data: $('#statsForm').serialize(),
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#countPassage').html(data['countPassage']);
                    $('#countTruePassage').html(data['countTruePassage']);
                    $('#countFalsePassage').html(data['countFalsePassage']);
                    $('#maxBall').html(data['maxBall']);
                    $('#minBall').html(data['minBall']);
                    $('#avgBall').html(data['avgBall']);
                    $('#ball90100').html(data['ball90100']);
                    $('#ball7089').html(data['ball7089']);
                    $('#ball5069').html(data['ball5069']);
                    $('#ball2449').html(data['ball2449']);
                    $('#ball0023').html(data['ball0023']);
                },
                error: function (msg) {
                    alert('Ошибка');
                }
            });
        }
        checkStats();
    </script>
@endsection

@section('includeStyles')
    <title>Статистика теста</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/richtext.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/Scrollbar.css') }}" rel="stylesheet" />

    <style>
        .stat-info p{
            width:300px;
        }
        .block_h {
            width: 260px;
            font-size: inherit;
            text-align: left;
        }
        .block_t {
            font-size: inherit;
            text-decoration: underline;
        }
        .n1 { width:260px; }
        .n2 { width:210px; }
        .n3 { width:300px; }
        .customDate { margin-bottom: 5px; }
    </style>   
@endsection