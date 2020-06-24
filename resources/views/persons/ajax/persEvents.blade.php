<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<script src="{{ asset('/js/script.js') }}"></script>

<ul class="open-list accordionjs m-0" data-active-index="false">
    @foreach ($persEvents as $pe)
        <li class="acc_section" onclick="
                                        peid = {{$pe->id}};
                                        $('#slideEdit').css('display', '');
                                        $('#event_name').html('{{ $pe->name }}');
                                        $('#event_name2').html('{{ $pe->name }}');
                                        $('#event_name3').html('{{ $pe->name }}');
                                        $('#event_date').html('{{ date('d.m.Y', strtotime($pe->date_start))}}');
                                        $('.exam_list').show();
                                    ">
            <div class="acc_head">
                <h3>														
                    <div class='row'>
                        <div class='col-md-1 col-2'>{{ $loop->iteration }}</div>
                        <div class='col-md-4 col-5'>{{ $pe->name }}</div>
                        <div class='col-md-4 m-h'>{{ date('d.m.Y H:i', strtotime($pe->date_start))}}</div>
                        <div class='col-md-3 m-h'>{{ date('d.m.Y H:i', strtotime($pe->date_end))}}</div>
                    </div>
                </h3>
            </div>
            <div class="acc_content" style="display: none;">
                <p>
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
                                @foreach ($persTests[$pe->id] as $pt)
                                    @if (isset($pt->discipline))
                                        <tr onclick="selected_test({{$pt->id}}, $(this), {{$successTest[$pt->id]}}, {{$pt->status}})">
                                            <td>{{$loop->iteration}}</td>
                                            <td class="text-sm font-weight-600">{{ $pt->discipline }}</td>
                                            <td class='text-center'><?php echo htmlspecialchars_decode($statusTest[$pt->id]); ?></td>
                                            <td class='text-center'>{{ date('d.m.Y H:i', strtotime($pt->start_time))}}</td>
                                            <td class='text-center'>
                                                @if (!empty($pt->minuts_spent)){{ $pt->minuts_spent }} мин. @endif
                                            </td>
                                            <td class='text-center'>{{ $pt->test_ball_correct }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </p>
            </div>
        </li>		
    @endforeach						 	
</ul>
