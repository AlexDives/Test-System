<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/CustomCSS/shortResult.css') }}">
    <title>Развернутый результат тестирования</title>
    <style>
        @media print
{
table {page-break-after:auto}
}
    </style>
</head>
<body>
    <div style="width: 100%; max-width: 170mm; margin: auto">
        <header>
            <h2 style="font-size: 18px;">ГОУ ВПО ЛНР Луганский национальный университет имени Тараса Шевченко</h2>
            <h3>Развернутый результат тестирования</h3>
        </header>
        <div class="content">
            <table>
                <tr>
                    <td>
                        Фамилия, имя, отчество:</td>
                    <td>
                        <b>{{ $fio }}</b>
                    </td>
                </tr>
                <tr>
                    <td>Наименование экзамена: </td>
                    <td>
                        <b>{{ $test_name }}</b>
                    </td>
                </tr>
            </table>
            @foreach ($fullResult as $fr)
                <table style="border-spacing: 0px; border-collapse: collapse;">
                    <thead>
                        <td colspan="3"><b>Вопрос {{ $fr['quest_num'] }}:</b></td>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="3" style="border: 1px solid black; width: 170mm; height: auto; padding-left: 5px;"><? echo htmlspecialchars_decode($fr['quest_text']) ?></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; width: 45%; height: auto; text-align: center;"><b>Ваш вариант ответа:</b></td>
                        <td style="width: 10%; height: auto;"></td>
                        <td style="border: 1px solid black; width: 45%; height: auto; text-align: center;"><b>Правильный ответ:</b></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; width: 45%; height: auto; padding-left: 5px;"><? echo htmlspecialchars_decode($fr['answer_text']) ?></td>
                        <td style="width: 10%; height: auto;"></td>
                        <td style="border: 1px solid black; width: 45%; height: auto; padding-left: 5px;"><? echo htmlspecialchars_decode($fr['cor_text']) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border: 1px solid black; width: 170mm; height: auto; text-align: center;">Не зачитано: {{ $fr['quest_ball'] }} б.</td>
                    </tr>
                    </tbody>
                </table>
            @endforeach
            <table>
                <tr>
                    <td style="width: 170mm;">Ответил(а) на <b>{{ $countAnswer }}</b> вопросов из <b>{{ $countAllQuestion }}</b>:</td>
                </tr>
                <tr>
                    <td>Правильных ответов: <b>{{ $countTrueAnswer }}</b></td>
                </tr>
                <tr>
                    <td>Неправильных ответов: <b>{{ $countFalseAnswer }}</b></td>
                </tr>
                <tr>
                    <td>Получил(а) <b>{{ $correctBall }}</b> баллов из <b>{{ $maxBall }}</b> возможных. </td>
                </tr>
                <tr >
                    <td style="width: 120mm;">С результатами тестирования согласен(на)</td>
                    <td>
                        <input type="text" disabled style="max-width: 50mm;"/>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
