<div class='card'>
    <div class='card-body'>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap align-items-center table-select" id="data">
                <thead class="thead-light">
                    <tr>
                        <th>id</th>
                        <th>Дисциплина</th>
                        <th>Тип теста</th>
                        <th>Всего</th>
                        <th>Мак. балл</th>
                        <th>Ф.И.О (макс. балл)</th>
                        <th>Мин. балл</th>
                        <th>Ф.И.О. (мин. балл)</th>
                        <th>Ср. балл</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tests as $test)
                    <tr class="user-row">
                        <td>{{ $test->id }}</td>
                        <td style="width: 100px; word-wrap : break-word;">{{ $test->discipline }}</td>
                        <td>{{ $test->type }}</td>
                        <td>{{ $test->countPers }}</td>
                        <td>{{ $test->max_ball }}</td>
                        <td>{{ $test->fio_max_ball }}</td>
                        <td>{{ $test->min_ball }}</td>
                        <td>{{ $test->fio_min_ball }}</td>
                        <td>{{ $test->sred_ball }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-body">
        <div class="row">Всего зарегистрировано: {{ $persCount }} чел. | Проблемная регистрация: {{ $persCount_bad }} чел.</div>
        <div class="row">Проходит тестирование: <span style="color:darkgoldenrod; font-weight:600; margin:0 5px;"> {{ $count_pers_in_test }} </span> чел.</div>  
        <div class="row">Прошло тестирование (минимум 1 тест): <span style="color:green; font-weight:600; margin:0 5px;"> {{ $count_pers_end_test }} </span> чел.</div>  
        <div class="row">На паузе (минимум 1 тест): <span style="color:darkred; font-weight:600; margin:0 5px;"> {{ $count_pers_pause_test }} </span> чел.</div>  
</div>