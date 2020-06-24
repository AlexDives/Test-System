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
				<div class='row'>
					<div class='col-md-7'>
						<div class="form-group row" style="display: flex !important;">
							<label class="col-form-label col-sm-4 text-sm-right">Тип теста:</label>
							<div class="col-sm-8 form-control">{{ $test->typeTestName }}</div>
						</div>
						<div class="form-group row" style="display: flex !important;">
							<label class="col-form-label col-sm-4 text-sm-right">Где и кем утвержден:</label>
							<div class="col-sm-8 form-control">{{ $test->validator }}</div>
						</div>
						<div class="form-group row" style="display: flex !important;">
							<label class="col-form-label col-sm-4 text-sm-right">Максимальный бал:</label>
							<div class="col-sm-8 form-control">{{ $test->max_ball }}</div>
						</div>
						<div class="form-group row" style="display: flex !important;">
							<label class="col-form-label col-sm-4 text-sm-right">Проходной бал:</label>
							<div class="col-sm-8 form-control">{{ $test->min_ball }}</div>
						</div>
						<div class="form-group row" style="display: flex !important;">
							<label class="col-form-label col-sm-4 text-sm-right">Количество вопросов:</label>
							<div class="col-sm-8 form-control">{{ $test->count_question }}</div>
						</div>
						<div class="form-group row" style="display: flex !important;">
							<label class="col-form-label col-sm-4 text-sm-right">Длительность тестов:</label>
							<div class="col-sm-8 form-control">{{ $test->test_time }}</div>
						</div>
						<div class="form-group row" style="display: flex !important;">
							<label class="col-form-label col-sm-4 text-sm-right">Разработчик теста:</label>
							<div class="col-sm-8 form-control">{{ $test->famil }} {{ $test->name }} {{ $test->otch }}</div>
						</div>
					</div>
					<div class="col-md-1"></div>
					<div class="col-md-2">
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
						@IF($test->status == 2) 
							<button class="btn btn-danger" onclick="full_delete_test({{ $test->id }});">Полное удаление</button> 
						@ENDIF 
					</div>
				</div>
			</div>
		</li>		
	@endforeach						 	
</ul>