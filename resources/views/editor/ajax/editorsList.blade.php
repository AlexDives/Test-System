<div class="row">
    <div class="col-md-12 mb-2"><b>Владелец:</b> {{ $owner }}</div>
    <div class="col-md-12 mb-2"><input type="text" class="form-control" id="search-text" name="search-text" onkeyup="tableSearch()" placeholder="Поиск..."></div>
</div> 
<table class="table">
    <thead>
        <th>Доступ</th>
        <th>Ф. И. О.</th>
    </thead>
</table>
<div class="scrollDiv">
    <table class="table" id="info-table" name="info-table">
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>
                        <div class="form-elements">
                            <div class="custom-controls-stacked">
                                <label class="custom-control custom-checkbox m-0">
                                <input type="checkbox" class="custom-control-input" name="is_editor" id="{{$user->id}}" @if ( $editors[$user->id] != 0) checked="" @endif>
                                    <span class="custom-control-label">Включить</span>
                                </label>										
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->famil }} {{ $user->name }} {{ $user->otch }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>