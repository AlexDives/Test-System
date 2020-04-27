<div class="card">	
    <table class="table card-table table-vcenter text-nowrap align-items-center table-select" id="data">
        <thead class="thead-light">
            <tr>
                <th style="width: 10px;">№</th>
                <th>Ф.И.О.</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($persList as $pers)
                <tr class="user-row" onclick="$('.block_user').hide();$('#photouser{{ $loop->iteration }}').show();persId={{ $pers->id }};checkedRow($(this));">
                    <td style="width: 10px;">{{ $loop->iteration }}</td>
                    <td>
                        <div class='row' style="align-items: center;">
                            <div class='col-md-2 block_user' id='photouser{{ $loop->iteration }}' style='display: none'>
                                <div style='border: 1px solid #ccc; width:25mm; height:35mm; background: url("{{ $pers->photo_url }}");background-size:cover'></div>
                            </div>
                            <div class='col-md-10 t-text' >{{ $pers->famil.' '.$pers->name.' '.$pers->otch }} </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div style="display:flex; justify-content:center;">{{ $persList->links("pagination::bootstrap-4") }}</div>