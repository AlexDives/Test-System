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
                            <a href="{{ url("/info") }}?id={{ $test_id }}" class="side-menu__item"><i class="side-menu__icon fa fa-info-circle"></i><span class="side-menu__label">Информация</span></a>
                        </li>
                        <li class="slide">
                            <a href="{{ url("/questions") }}?id={{ $test_id }}" class="side-menu__item active"><i class="side-menu__icon  fa fa-question-circle"></i><span class="side-menu__label">Вопросы</span></a>
                            <ul class="side-menu toggle-menu">
                                <li class="active">
                                    <a href="#" class="side-menu__item" onclick="newQuest();"><i class="side-menu__icon fa fa-plus"></i><span class="side-menu__label">Новый вопрос</span></a>
                                </li>
                                <li class="slide">
                                    <a href="#" class="side-menu__item" onclick="saveQuest();"><i class="side-menu__icon  fa fa-floppy-o"></i><span class="side-menu__label">Сохранить <img id="okImg" src="{{ asset('images/sources/ok.png') }}" style="height: 20px;padding-left: 15px;display:none;"></span></a>
                                </li>
                                
                                @if($role_id == 1)
                                    <li class="slide">
                                        <a href="#" class="side-menu__item" onclick="speedFillQuest();"><i class="side-menu__icon  fa fa-floppy-o"></i><span class="side-menu__label">Быстро заполнить <img id="okImg" src="{{ asset('images/sources/ok.png') }}" style="height: 20px;padding-left: 15px;display:none;"></span></a>
                                    </li>
                                @endif
                                <li class="slide">
                                    <a href="#" class="side-menu__item" onclick="deleteQuest();"><i class="side-menu__icon  fa fa-minus"></i><span class="side-menu__label">Удалить вопрос</span></a>
                                </li>							 
                            </ul>
                        </li>
                        <li class="slide">
                            <a href="{{ url("/statistic") }}?id={{ $test_id }}" class="side-menu__item"><i class="side-menu__icon  fa fa-pie-chart"></i><span class="side-menu__label">Статистика</span></a>
                        </li>							 
                    </ul>
                </div>
                <div class='btn-back' style='position: fixed'>
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
                <li class="breadcrumb-item"><a href="#">Вопросы</a></li>
            </ol>							
        </div>
        <form action='' method='POST' name="questionForm" id="questionForm">
            {{ csrf_field() }}
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='card-body'>
                            <div class='row'>
                                <div class='col-md-8'>
                                    <label class="form-label">Дисциплина</label>
                                    <label class="form-control">{{ $test_name }}</label>
                                </div>
                                <div class='col-md-4'>
                                    <label class="form-label">Количество баллов за вопрос</label>
                                    <input type="text" class="form-control" name="ballQuest" id="ballQuest" placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>							
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row row-cards">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="pagination mg-b-0 page-0 li" style="overflow-x: auto;"></div> 
                                
                                <input type="hidden" name="tid" id="tid" value="{{ $test_id }}">
                                <input type="hidden" name="idQuest" id="idQuest">
                                <input type="hidden" name="textQuest" id="textQuest">
                                <input type="hidden" name="answerTrueQuest" id="answerTrueQuest">
                                <input type="hidden" name="answerFalse1Quest" id="answerFalse1Quest">
                                <input type="hidden" name="answerFalse2Quest" id="answerFalse2Quest">
                                <input type="hidden" name="answerFalse3Quest" id="answerFalse3Quest">
                
                                <div class="card-body">
                                    <label>Вопрос:</label>
                                    <div class="question" name="question" id="question">Вопрос</div>
                                    <div class='row' style='margin:15px -10px'>
                                        <div class='col-md-6'>
                                            <div class="d-answer">
                                                <label>Правильный ответ:</label>
                                                <div class="q-ask" id="answerTrue" name="answerTrue">Ответ 1</div>
                                            </div>
                                        </div>
                                        <div class='col-md-6 '>
                                            <div class="d-answer">
                                                <label>Ложный ответ:</label>
                                                <div class="q-ask" id="answerFalse1" name="answerFalse1">Ответ 2</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col-md-6'>
                                            <div class="d-answer">
                                                <label>Ложный ответ:</label>
                                                <div class="q-ask" id="answerFalse2" name="answerFalse2">Ответ 3</div>
                                            </div>
                                        </div>
                                        <div class='col-md-6'>
                                            <div class="d-answer">
                                                <label>Ложный ответ:</label>
                                                <div class="q-ask" id="answerFalse3" name="answerFalse3">Ответ 4</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>	
                </div>
            </div>
        </form>
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
    <script src="{{ asset('js/quill2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
@endsection

@section('includeBeforeScripts')
    <script src="{{ asset('js/CustomJS/editorQuestions.js') }}"></script>
    <script>ajaxQuestList({{ $test_id }});</script>
@endsection

@section('includeStyles')
    <title>Вопросы</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('fonts/fonts/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Scrollbar.css') }}" rel="stylesheet" />

    <link href="{{ asset('css/quill.snow.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/quill-better-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
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
        #answerTrue.ql-container {
            border: 2px solid green;
        }
	</style>
@endsection