<<<<<<< HEAD
<div class="table-responsive"> <table class="table">
=======
<table class="table">
>>>>>>> af6d712a7431e3b4be6cbde0e50d93227f613f2c
    <thead>
        <th>Наименование</th>
        <th>Начало мероприятия</th>
        <th>Конец мероприятия</th>
    </thead>
</table>
<div class="scrollDiv">
    <table class="table" id="info-table" name="info-table">
        <tbody>
            @foreach ($events as $event)
                <tr onclick='selectedEvent({{ $event->id }});'>
                    <td>{{ $event->name }}</td>
                    <td>{{ date('d.m.Y H:i', strtotime($event->date_start))}}</td>
                    <td>{{ date('d.m.Y H:i', strtotime($event->date_end))}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
<<<<<<< HEAD
</div>
=======
>>>>>>> af6d712a7431e3b4be6cbde0e50d93227f613f2c
</div>