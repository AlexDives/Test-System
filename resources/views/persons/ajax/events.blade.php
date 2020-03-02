<table class="table">
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
</div>