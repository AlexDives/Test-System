<div class='card-body'>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap  align-items-center">
            <thead class="thead-light">
                <tr>
                    <th>№</th>
                    <th>Название теста</th>
                    <th class='text-center'>Статус</th>
                    <th class='text-center'>Дата прохождения</th>
                    <th class='text-center'>Затраченное время</th>
                    <th class='text-center'>Баллов</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($persTests as $pt)
                    <tr <?php echo htmlspecialchars_decode($successTest[$pt->test_id]); ?>>
                        <td>{{$loop->iteration}}</td>
                        <td class="text-sm font-weight-600">{{ $pt->discipline }}</td>
                        <td class='text-center'><?php echo htmlspecialchars_decode($statusTest[$pt->test_id]); ?></td>
                        <td class='text-center'>{{ $pt->start_time }}</td>
                        <td class='text-center'>
                            @if (!empty($pt->minuts_spent)){{ $pt->minuts_spent }} мин. @endif
                        </td>
                        <td class='text-center'>{{ $pt->test_ball_correct }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>