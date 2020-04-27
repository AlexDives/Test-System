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
                            <a href="{{ isset($test) ? url("/info")."?id=".$test[0]->id : "#"}}" class="side-menu__item active"><i class="side-menu__icon fa fa-info-circle"></i><span class="side-menu__label">Информация</span></a>
                            <ul class="side-menu toggle-menu">
                                <li class="slide">
                                    <a href="#" class="side-menu__item active" onclick="saveTestInfo({{ isset($test) ? $test[0]->id : 0 }});"><i class="side-menu__icon fa fa-floppy-o"></i><span class="side-menu__label">Сохранить</span></a>
                                </li>
                                <li class="slide">
                                    <a href="#" class="side-menu__item" onclick="test_duplicate({{ isset($test) ? $test[0]->id : 0 }});"><i class="side-menu__icon fa fa-clone"></i><span class="side-menu__label">Дубликат теста</span></a>
                                </li>
                            </ul>
                        </li>
                        @if (isset($test))
                            <li class="slide">
                                <a href="{{ url("/questions") }}?id={{ $test[0]->id }}" class="side-menu__item"><i class="side-menu__icon  fa fa-question-circle"></i><span class="side-menu__label">Вопросы</span></a>
                            </li>
                            <li class="slide">
                                <a href="{{ url("/statistic") }}?id={{ $test[0]->id }}" class="side-menu__item"><i class="side-menu__icon  fa fa-pie-chart"></i><span class="side-menu__label">Статистика</span></a>
                            </li>
                            <li class="slide">
                                <a href="#" class="side-menu__item" onclick="loadUsers();"><i class="side-menu__icon  fa fa-question-circle"></i><span class="side-menu__label">Редакторы теста</span></a>
                            </li>
                            @if( $test[0]->status == 1 )							 
                                <li class="slide">
                                    <a href="{{ url("/info/delete") }}?id={{ $test[0]->id }}" class="side-menu__item" data-toggle="slide"><i class="side-menu__icon  fa fa-trash"></i><span class="side-menu__label">Удалить тест</span></a>
                                </li>
                            @else
                                <li class="slide">
                                    <a href="{{ url("/info/rollback") }}?id={{ $test[0]->id }}" class="side-menu__item" data-toggle="slide"><i class="side-menu__icon  fa fa-retweet"></i><span class="side-menu__label">Восстановить тест</span></a>
                                </li> 
                            @endif
                        @endif
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
                    <li class="breadcrumb-item"><a href="#">Информация о тесте</a></li>
                </ol>							
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form action='' method='POST' name="testInfoForm" id="testInfoForm">
                        {{ csrf_field() }}
                        <div class="card">
                            <div class="card-header">
                                <h3 class="mb-0 card-title">Редактировать информацию</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Направление подготовки</label>
                                            <input type="hidden" name="tid" id="tid" value="{{ isset($test) ? $test[0]->id : 0 }}">
                                            <input type="text" class="form-control" name="discipline" id="discipline" placeholder="Направление подготовки" value="{{ isset($test) ? $test[0]->discipline : '' }}">
                                        </div>
                                        <div class='row mb-3'>
                                            <div class='col-md-6'>
                                                <label class="form-label">Тип теста</label>
                                                <select name="typeTest" class="form-control custom-select" id="typeTest">
                                                    <option value="">Выберите тип</option>
                                                    @foreach ($typeTest as $type)
                                                        <option value="{{ $type->id }}" @if ( (isset($test) ? $test[0]->typeTestid : 0) == $type->id) selected @endif>{{ $type->name }}</option>
                                                    @endforeach															
                                                </select>
                                            </div>
                                            <div class='col-md-6'>
                                                <label class="form-label">Целевая аудитория</label>
                                                <select name="targetAudience" class="form-control custom-select" id="targetAudience">
                                                    <option value="">Выберите аудиторию</option>
                                                    @foreach ($targetAudience as $ta)
                                                        <option value="{{ $ta->id }}" @if ((isset($test) ? $test[0]->targetAudienceid : 0) == $ta->id) selected @endif>{{ $ta->name }}</option>
                                                    @endforeach																	
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Где и кем утвержден</label>
                                            <input type="text" class="form-control" name="validator" id="validator" placeholder="Где и кем утвержден.." value="{{ isset($test) ? $test[0]->validator : '' }}">
                                        </div>
                                        <div class='row'>
                                            <div class='col-md-6'>
                                                <div class="form-group">
                                                    <label class="form-label">Максимальный балл</label>
                                                    <input type="text" class="form-control" name="max_ball" id="max_ball" placeholder="0 - 100" value="{{ isset($test) ? $test[0]->max_ball : ' '}}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Проходной балл</label>
                                                    <input type="text" class="form-control" name="min_ball" id="min_ball" placeholder="0 - 100" value="{{ isset($test) ?  $test[0]->min_ball : '' }}">
                                                </div>
                                            </div>
                                            <div class='col-md-6'>
                                                <div class="form-group">
                                                    <label class="form-label">Количество вопросов</label>
                                                    <input type="text" class="form-control" name="count_question" id="count_question" placeholder="0 - 100" value="{{ isset($test) ? $test[0]->count_question : '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Длительность теста (минут)</label>
                                                    <input type="text" class="form-control" name="test_time" id="test_time" placeholder="" value="{{ isset($test) ? $test[0]->test_time : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="card">
                                    <div class='card-header'>
                                        <h3 class='mb-0 card-title'>Разбаловка</h3>
                                    </div>
                                    <div class='card card-body'>
                                        <div class='row'>
                                            <div class='col-md-12'>
                                                <div class='row'>
                                                    <div class='col-md-6 col-6'>
                                                        <label class="form-label">Балл за вопрос</label>
                                                    </div>
                                                    <div class='col-md-6 col-6'>
                                                        <label class="form-label">Количество вопросов</label>
                                                    </div>
                                                 </div>
                                            </div>
                                            @if (isset($test))
                                                @foreach ($testScatter as $ts)
                                                    <div class='col-md-12'>
                                                        <div class='row'>
                                                            <div class='col-md-6 col-6'>		
                                                                <input type='hidden' name="scatterId[{{ $loop->iteration }}]" id="scatterId[{{ $loop->iteration }}]" value="{{ $ts->id }}" >											 
                                                                <input type='text' class='form-control mt-3' value='{{ $ts->ball }}' placeholder="" name="scatterBall[{{ $loop->iteration }}]" id="scatterBall[{{ $loop->iteration }}]">
                                                            </div>
                                                            <div class='col-md-6 col-6'>
                                                                <input type='text' class='form-control mt-3' value='{{ $ts->ball_count }}' placeholder="" name="scatterBallCount[{{ $loop->iteration }}]" id="scatterBallCount[{{ $loop->iteration }}]">	
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach	
                                            @endif
                                            @for ($i = isset($test) ? count($testScatter) + 1 : 0 + 1; $i <= 5; $i++)
                                                <div class='col-md-12'>
                                                    <div class='row'>
                                                        <div class='col-md-6 col-6'>
                                                            <input type='hidden' name="scatterId[{{ $i }}]" id="scatterId[{{ $i }}]" value="" >													 
                                                            <input type='text' class='form-control mt-3' value='' placeholder="" name="scatterBall[{{ $i }}]" id="scatterBall[{{ $i }}]">
                                                        </div>
                                                        <div class='col-md-6 col-6'>
                                                            <input type='text' class='form-control mt-3' value='' placeholder="" name="scatterBallCount[{{ $i }}]" id="scatterBallCount[{{ $i }}]">	
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="card">
                                    <div class='card-header'>
                                        <h3 class='mb-0 card-title'>Сборный тест</h3>
                                    </div>
                                    <div class='card card-body'>
                                        <div class='table-responsive'>
                                            <label class="form-label">В разработке<!--Дисциплина--></label>
                                            <!--<div class="container1">
                                                <div class="input-group mt-1">
                                                    <button class="add_form_field btn btn-add btn-success">
                                                        <span style="font-size:16px; font-weight:bold;">+</span>
                                                    </button> 
                                                    <input type="hidden" name="testListId[]" id="testList[]" value="" >
                                                    <select name="testList[]" id="testList[]" class="form-control custom-select">
                                                        <option value=""></option>
                                                        @foreach ($testList as $tl)
                                                            <option value="{{ $tl->id }}">{{ $tl->discipline }} ({{ $tl->typeTestName }} | {{ $tl->targetAudienceName }})</option>
                                                        @endforeach			
                                                    </select>
                                                </div>
                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
    <script src="{{ asset('js/CustomJS/editorInfo.js') }}"></script>

    <script src="{{ asset('js/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert4.min.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <script>
        
    </script>
@endsection

@section('includeStyles')
    <title>Редактировать информацию</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <style>
        .scrollDiv{
            height: 200px !important;
            overflow-y: auto;
        }    
    </style>
@endsection