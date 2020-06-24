<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<script src="{{ asset('/js/script.js') }}"></script>
<div class="card">
    <div class="card-body">
        <div class="row">
            Всего: {{ count($doublePers) }}<br>
            Дубликатов (по ФИО): <?php $c = 0; foreach($doublePers as $dp) $c = $dp ? $c + 1 : $c; echo $c; ?>
        </div>
    </div>
    <div class="table-responsive">
        <ul class="open-list accordionjs m-0" data-active-index="false">
            <li>
                <div class='acc_head head_test' style='background: #fff !important'>
                    <h3>
                        <div class='row'>
                            <div class='col-md-1 col-2'>№</div>
                            <div class='col-md-3 col-5'>ФИО</div>
                            <div class='col-md-2 m-h'>Результат</div>
                            <div class='col-md-2 m-h'>Статус</div>
                            <div class='col-md-1 col-2'>Группа</div>
                            <div class='col-md-2 m-h'>Дата прохождения</div>
                        </div>
                    </h3>
                </div>
            </li>
            @foreach ($pers as $p)
                <li class="acc_section">
                    <div class="acc_head">
                        <h3>
                            <div class='row' onclick="ptid = {{ $p->ptid }}">
                                <div class='col-md-1 col-2'>{{ $loop->iteration }}</div>
                                <div class='col-md-3 col-5'>{{ $p->famil }} {{ $p->name }} {{ $p->otch }} {{ $doublePers[$p->id] ? '(есть дубликат)' : '' }}</div>
                                <div class='col-md-2 m-h'>{{ !empty($p->test_ball_correct) ? $p->test_ball_correct.' б.' : '' }}</div>
                                <div class='col-md-2 m-h'>
                                    @if($p->status == 0)
                                        <span class='badge badge-primary'>Готов к прохождению</span>
                                    @elseif($p->status == 1)
                                        <span class='badge badge-warning'>В процессе</span>        
                                    @elseif($p->status == 2)
                                        @if ($p->test_ball_correct >= $p->min_ball)
                                            <span class='badge badge-success'>Пройден</span>
                                        @else
                                            <span class='badge badge-danger'>Не пройден</span>
                                        @endif
                                    @elseif($p->status == 3)
                                        <span class='badge badge-danger'>Приостановлен</span>
                                    @endif
                                </div>
                                <div class='col-md-1 col-2'>{{ $p->study_place }}</div>
                                <div class='col-md-2 m-h'>{{ date('d.m.Y H:i', strtotime($p->start_time))}}</div>
                            </div>
                        </h3>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
