<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        *, body,
        html {
            font-family: DejaVu Sans, sans-serif;
        }

        header {
            width: 100%;
            height: 120px;
            text-align: center;
        }

        header h3 {
            font-weight: normal;
            margin-top: 16px;
        }

        .content table {
            margin: 20px 0px 20px 0px;
            font-size: 14px;
            width: 100%;
            border-collapse: collapse;
        }

        .content table tr td {
            border: 1px solid black;
            height: 15px;
            padding-left: 5px;
            padding-right: 5px;
        }

    </style>
    <title>Document</title>
</head>
<body>
    <div style="width: 100%; max-width: 170mm; margin: auto">
        <header>
            <h2 style="font-size: 18px;">ГОУ ВПО ЛНР Луганский национальный университет имени Тараса Шевченко</h2>
            <h3>Результаты тестирования</h3>
        </header>
        <div class="content">
            @foreach ($groups as $group)
                <table>
                    <tr>
                        <td style="text-align: center;"><b>{{ 'Группа '.$group->study_place }}</b></td>
                    </tr>
                </table>
                <table>
                    <thead>
                        <tr>
                            <td style="text-align: center; width: 10%;"><b>№</b></td>
                            <td style="text-align: center; width: 30%;"><b>ФИО</b></td>
                            <td style="text-align: center; width: 50%;"><b>Экзамен</b></td>
                            <td style="text-align: center; width: 10%;"><b>Баллы</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($persons[$group->study_place] as $pers)
                            <tr>
                                <td style="text-align: center;">{{ $loop->iteration }}</td>
                                <td>{{ $pers->famil.' '.$pers->name.' '.$pers->otch }}</td>
                                <td>Экзамен по государственному и муниципальному управлению</td>
                                <td style="text-align: center;">{{ $pers->test_ball_correct }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
</body>
</html>
