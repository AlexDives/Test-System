<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/CustomCSS/shortResult.css') }}">
    <title>Document</title>
</head>
<body>
    <div style="width: 100%; max-width: 170mm; margin: auto">
        <header>
            <h2 style="font-size: 18px;">ГОУ ВПО ЛНР Луганский национальный университет имени Тараса Шевченко</h2>
            <h3>Результаты тестирования</h3>
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
            </table>
            <table>
                <tr>
                    <td>Наименование экзамена: </td>
                    <td>
                        <b>{{ $test_name }}</b>
                    </td>
                </tr>
            </table>
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
            </table>
            <table>
                <tr>
                    <td>Получил(а) <b>{{ $correctBall }}</b> баллов из <b>{{ $maxBall }}</b> возможных. </td>
                </tr>
            </table>
            <table>
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
