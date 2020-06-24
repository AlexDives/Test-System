<!DOCTYPE html>
<html>
<head>
    <title>Экзаменационный лист</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .info {
            text-align:center;
        }
        .info h3 {
            padding: 0px;
            margin: 2px;
        }
    </style>
</head>
<body>
    <div style="width: 100%; max-width: 170mm; margin: auto">
        <table width="100%">
            <tr style="border-bottom: 1px solid #000000">
                <td style="width: 125px;"><img src="{{ asset('images/logo.png') }}" style="width: 125px;"></td>
                <td style="text-align: justify;">ГОУ ВПО ЛНР «ЛУГАНСКИЙ НАЦИОНАЛЬНЫЙ УНИВЕРСИТЕТ ИМЕНИ ТАРАСА ШЕВЧЕНКО»</td>
            </tr>
            <tr>
                <td style="padding-bottom: 8px;text-align:center;" colspan="2">
                    <strong>ЭКЗАМЕНАЦИОННЫЙ ЛИСТ</strong>
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 16px;text-align:center;" colspan="2">
                    <strong>({{ strtoupper($event_name) }})</strong><br>
                </td>
            </tr>
            <tr>
                <td class="info" colspan="2">
                    <h3>Фамилия: {{ $famil }}</h3>
                </td>
            </tr>
            <tr>
                <td class="info" colspan="2">
                    <h3>Имя: {{ $name }}</h3>
                </td>
            </tr>
            <tr>
                <td class="info" colspan="2">
                    <h3>Отчество: {{ $otch }}</h3>
                </td>
            </tr>
            <tr>
                <td class="info" colspan="2" >
                    <h3 style="margin: 20px;">PIN: {{ $PIN }}</h3>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" cellpadding="0" cellspacing="0" border="1">
                        <thead>
                            <tr style="background-color: #eee">
                                <th style="text-align: center; padding: 5px; width: 5%;">№</th>
                                <th style="text-align: center; padding: 5px; width: 50%;">Предмет</strong></th>
                                <th style="text-align: center; padding: 5px; width: 15%;">Дата</th>
                                <th style="text-align: center; padding: 5px; width: 15%;">Время</th>
                                <th style="text-align: center; padding: 5px; width: 15%;">Баллы</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tests as $test)
                                <tr>
                                    <td style="text-align: center; padding: 5px;">{{ $loop->iteration }}</td>
                                    <td style="text-align: center; padding: 5px;">{{ $test->discipline }}</td>
                                    <td style="text-align: center; padding: 5px;">{{ date('d.m.Y', strtotime($test->start_time)) }}</td>
                                    <td style="text-align: center; padding: 5px;">{{ date('H:i', strtotime($test->start_time)) }}</td>
                                    <td style="text-align: center; padding: 5px;">{{ $test->status == 2 ? $test->test_ball_correct : '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="info" colspan="2">
                    <br>
                    Данный экзаменационный лист является пропуском для прохождения тестирования
                </td>
            </tr>
        </table>
    </div>
</body>
</html>