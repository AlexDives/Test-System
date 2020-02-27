<table class="table card-table table-vcenter text-nowrap align-items-center table-select" id="data">
    <thead class="thead-light">
        <tr>
            <th>Статус</th>
            <th>Логин</th>
            <th>Ф.И.О.</th>
            <th>Место работы</th>
            <th>№ Телефона</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
                <tr class="user-row uid-{{ $user->id }}" 
                    OnClick="user_change({{ $user->id }}, '{{ $user->login }}', '{{ $user->famil }}'
                    , '{{ $user->name }}', '{{ $user->otch }}', '{{ $user->workplace }}'
                    , '{{ $user->doljn }}', '{{ $user->aud_num }}', '{{ $user->tel_num }}'
                    , '{{ $user->role_id }}', '{{ $user->is_block }}', '{{ $user->secret_question }}'
                    , '{{ $user->secret_answer }}', '{{ $userStatus[$user->id] }}');">
                    @if ($user->is_block == 'T') 
                        <td>
                            <span class="status-span">
                                <div class="led-red-off"></div>
                                <p class="status-p">Заблокирован</p>
                                <p class="last-activ">{{$user->last_active}}</p>
                            </span>
                        </td>
                    @elseif ($userStatus[$user->id] == 'online' ) 
                        <td>
                            <span class="status-span">
                                <div class="led-green"></div>
                                <p class="status-p">В сети</p>
                            </span>
                            <p class="last-activ">{{$user->last_active}}</p>
                        </td>
                    @else 
                        <td>
                            <span class="status-span">
                                <div class="led-red-on"></div>
                                <p class="status-p">Не в сети</p>
                            </span>
                            <p class="last-activ">{{$user->last_active}}</p>
                        </td>
                    @endif
                    <td>{{ $user->login }}</td>
                    <td>{{ $user->famil }} {{ $user->name }} {{ $user->otch }}</td>
                    <td>{{ $user->workplace }}</td>
                    <td>{{ $user->tel_num }}</td>
                </tr>
        @endforeach
    </tbody>
</table>