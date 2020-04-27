<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<script src="{{ asset('/js/script.js') }}"></script>
<ul class="open-list accordionjs m-0" data-active-index="false">
    @foreach ($tests as $test) 
        <li class="acc_section" onclick="$('#changedTest').attr('href', '{{ url('/info') }}?id=' + {{ $test->id }}); $('#slideEdit').css('display', '');">
            <div class="acc_head" @if(isset($successTest[$test->id])) 
                                    @if($successTest[$test->id] == 'true') 
                                        @IF($test->status == 2) 
                                            style="background: tomato; color:#fff" 
                                        @else 
                                            style="background: lightgreen;" 
                                        @ENDIF 
                                    @ELSE 
                                        @IF($test->status == 2) 
                                            style="background: tomato; color:#fff" 
                                        @ENDIF 
                                    @ENDIF
                                  @ENDIF>
                <h3>														
                    <div class='row'>
                        <div class='col-md-1 col-2'>{{ $loop->iteration }} @if($role_id == 1) ({{ $test->id }}) @endif</div>
                        <div class='col-md-4 col-5'>{{ $test->discipline }}</div>
                        <div class='col-md-2 col-5'>{{ $test->targetAudienceName }}</div>
                        <div class='col-md-2 col-5'>{{ $test->typeTestName }}</div>
                        <div class='col-md-3 m-h'>{{ date('d.m.Y', strtotime($test->date_crt))}}</div>
                    </div>
                </h3>
            </div>
            <div class="acc_content" style="display: none;">
                <p>
                    <div class='row'>
                        <div class='col-md-6'>
                            <div class='block_h'>Тип теста:</div> 
                            <div class='block_t'>{{ $test->typeTestName }}</div>

                             <div class='block_h'>Где и кем утвержден: </div> 
                            <div class='block_t'>{{ $test->validator }}</div>

                            <div class='block_h'>Максимальный бал:</div> 
                            <div class='block_t'>{{ $test->max_ball }}</div>

                            <div class='block_h'>Проходной бал:</div> 
                            <div class='block_t'>{{ $test->min_ball }}</div>
                                        
                            <div class='block_h'>Количество вопросов:</div> 
                            <div class='block_t'>{{ $test->count_question }}</div>
                                        
                            <div class='block_h'>Длительность тестов:</div> 
                            <div class='block_t'>{{ $test->test_time }}</div>
                            <p>
                                <div class='block_h'>Разработчик теста:</div> 
                                <div class='block_t'>{{ $test->famil }} {{ $test->name }} {{ $test->otch }}</div>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class='text-center'>
                                <h4>Разбаловка</h4>
                            </div>
                            <table class='table table-sublist text-center'>
                                <thead>
                                    <th>Сложность вопросов</th>
                                    <th>Количество вопросов</th>
                                </thead>
                                <tbody>
                                    @foreach ($testScatter[$test->id] as $scatter)
                                        <tr>
                                            <td>{{ $scatter->ball }}</td>
                                            <td>{{ $scatter->ball_count }}</td>
                                        </tr>
                                    @endforeach                                                           
                                </tbody>
                            </table>
                        </div>
                    </div>
                </p>
            </div>
        </li>		
    @endforeach						 	
</ul>