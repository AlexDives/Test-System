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
                        <b>Иванов Иван Иванович</b>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width: 50mm;">Специальность:</td>
                    <td>
                        <textarea style="
                        border: none; 
                        border-bottom: 1px solid black;
                        background-color: inherit;
                        color: black;
                        font-weight: bold;
                        font-style: italic;
                        font-size: 12px;
                        height: 15px;
                        width: 120mm;"> 21.22.33 Физическая культура для лиц с отклонениями в состоянии здоровья (адаптивная физическая культура) (Физическая реабилитация) (на базе СПО)</textarea>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50mm;">Форма обучения:</td>
                    <td>
                        <input type="text" Value="Очная форма" disabled/>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50mm;">Факультет, институт:</td>
                    <td>
                        <input type="text"  Value="ИФМИТ" disabled/>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50mm;">ОКУ:</td>
                    <td>
                        <input type="text" Value="Бакалавриат" disabled/>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>Наименование экзамена: </td>
                    <td>
                        <b>Вступительный тест - Математика</b>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width: 170mm;">Ответил(а) на <b>60</b> вопросов из <b>60</b>:</td>
                </tr>
                <tr>
                    <td>Правильных ответов: <b>30</b></td>
                </tr>
                <tr>
                    <td>Неправильных ответов: <b>30</b></td>
               </tr>
            </table>
            <table>
                <tr>
                    <td>Получил(а) <b>48</b> баллов из <b>100</b> возможных </td>
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
