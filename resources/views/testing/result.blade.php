@extends('layer')

@section('header')
    <div class="app-header header d-flex">
        <div class="container-fluid">
            <div class="d-flex">
                <a class="header-brand" href="index.html">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img main-logo" alt="Hogo logo">
                    <img src="{{ asset('images/logo.png') }}" class="header-brand-img icon-logo" alt="Hogo logo">
                    <span class='logo-name'>Результат тестирования</span>
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
                        <li class="slide show_report">
                            <a href="#" onclick="shortResult();" class="side-menu__item" ><i class="side-menu__icon  fa fa-file-text-o"></i><span class="side-menu__label">Краткий отчет</span></a>
                        </li>
                        @if ($role == 1 || $role == 2)
                            <li class="slide show_report">
                                <a href="#" class="side-menu__item"><i class="side-menu__icon  fa fa-file-text-o"></i><span class="side-menu__label">Полный отчет</span></a>
                            </li>
                        @endif
                        								 
                    </ul>
                </div>
                @if ($role == 5 && $pers_type == 't')
                    <div class='btn-back'>
                        <a href="/persons" class="side-menu__item"><i class="side-menu__icon  fa fa-sign-out"></i><span class="side-menu__label">Назад</span></a>
                    </div>
                @elseif ($role == 5 && $pers_type == 'g')
                    <div class='btn-back'>
                        <a href="/quit" class="side-menu__item"><i class="side-menu__icon  fa fa-sign-out"></i><span class="side-menu__label">Выход</span></a>
                    </div>
                @elseif ($role == 5 && $pers_type == 'a')
                    <div class='btn-back'>
                        <a href="https://abit.ltsu.org/profile?pid=".$pid class="side-menu__item"><i class="side-menu__icon  fa fa-sign-out"></i><span class="side-menu__label">Назад</span></a>
                    </div>
                @else
                    <div class='btn-back'>
                        <a href="/pers/cabinet" class="side-menu__item"><i class="side-menu__icon  fa fa-sign-out"></i><span class="side-menu__label">Назад</span></a>
                    </div>
                @endif
            </div>
        </div>
    </aside>
@endsection

@section('content')
    <div class="app-content  my-3 my-md-5 toggle-content">
        <div class="side-app">
            <div class="page-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Результат тестирования</a></li>
                </ol>							
            </div>
            <div class="row" >
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='card-body'>
                            <div class='row'>
                                <div class='col-md-12 text-center'>
                                    <label class="test-name">{{ $test_name }}</label>
                                    <hr>
                                    <h1>
                                        @if ($resultTest)
                                            <span class='text-success'>Тест пройден! </span>
                                        @else
                                            <span class='text-danger'>Тест не пройден!</span>
                                        @endif
                                    </h1>
                                    <div class="row">
                                        
                                        <div class='col-md-12'>
                                             <div class='row'>
                                                <div class="col-md-12">
                                                    <div class="block_n">Количество вопросов:</div> 
                                                    <div class="block_v">{{ $countAllQuestion }} </div>
                                                </div>	
                                        
                                                <div class="col-md-12">
                                                    <div class="block_n">Количество отвеченных вопросов:</div>
                                                    <div class="block_v">{{ $countAnswer }}</div>
                                                </div>	
                                                
                                                <div class="col-md-12">											
                                                    <div class="block_n">Правильных ответов:</div> 
                                                    <div class="block_v">{{ $countTrueAnswer }}</div>
                                                </div>	
                                                
                                                <div class="col-md-12">											
                                                    <div class="block_n">Не правильных ответов:</div> 
                                                    <div class="block_v">{{ $countFalseAnswer }}</div>
                                                </div>	
                                                <div class="col-md-12">
                                                    <h1>
                                                        @if ($resultTest)
                                                            <span class='text-success'>
                                                                <div class="block_n d">Набранно баллов:</div> 
                                                                <div class="block_v d">{{ $correctBall }}</div>
                                                            </span>
                                                        @else
                                                            <span class='text-danger'>
                                                                <div class="block_n d">Набранно баллов:</div> 
                                                                <div class="block_v d">{{ $correctBall }}</div>
                                                            </span>
                                                        @endif
                                                    </h1>
                                                    
                                                </div>

                                                <div class="col-md-12">											
                                                    <div class="block_n">Время начала теста:</div> 
                                                    <div class="block_v">{{ date("d.m.Y H:i", strtotime($startTime)) }}</div>
                                                </div>
                                                <div class="col-md-12">											
                                                    <div class="block_n">Время завершения теста:</div> 
                                                    <div class="block_v">{{ date("d.m.Y H:i", strtotime($endTime)) }}</div>
                                                </div>
                                                <div class="col-md-12">											
                                                    <div class="block_n">Затраченное время на тест:</div> 
                                                    <div class="block_v">{{ $correntMinutes }} мин.</div>
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
        function shortResult()
        {
            let form = document.createElement('form');
            form.action = '/test/result/short';
            form.method = 'POST';
            form.target = '_blank';
            form.innerHTML = '<input name="ptid" value="{{ $ptid }}">{{ csrf_field() }}';
            document.body.append(form);
            form.submit();
        }
    </script>
@endsection

@section('includeStyles')
    <title>Результат тестирования</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
    <style>
        .stat-info p{
			width:300px;
		}
		 
		.block_n {
			width: 50%;
		    font-size: 20px;
		    float: left;
            text-align: right;
            margin-right: 15px;
            margin-bottom: 15px;
		}
		.block_v {
			font-size: 20px;
			text-align: left;
		}
		.d { text-decoration: underline; font-size: inherit; }
        .test-name {
            font-size: 20px;
            margin: 0px !important;
        }
    </style>	
@endsection