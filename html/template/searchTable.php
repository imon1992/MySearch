<h2>Search</h2>
<div id="search">
    <button id="addFieldToSearch">Добавить поля для поиска</button>
    <p><input id="withCityOrNot" type="checkbox" checked="checked"> Поиск по городу(уберите галочку и выбирете город, иначе поиск будет идти только по тегу)</p>
    <p><input id="withDateOrNot" type="checkbox"> Поставте галочку для поиска по дате(иначе поиск будет идти по имеющимся на сайте вакансиям)</p>
    <div id="date">
        <p>Кликните на поле ввода даты для вызова календаря:<br>
            с <input type="text" id="from" value="30-07-2015" onfocus="this.select();lcs(this)"
                     onclick="event.cancelBubble=true;lcs(this)">
            по <input type="text" id="by" value="30-06-2015" onfocus="this.select();lcs(this)"
                      onclick="event.cancelBubble=true;this.select();lcs(this)">
        </p>
    </div>
    <p>В каком разделе ищем</p>
    <?
    if(checkKey($_GET, 'dou')!==null){
        include_once 'html/douSearchTable.html';
    }
    if(checkKey($_GET, 'stackoverflow')!==null){
        include_once 'html/stackoverflowSearchTable.html';
    }
    if(checkKey($_GET, 'rabota')!==null){
        include_once 'html/rabotaSearchTable.html';
    }
    function checkKey($array, $key)
    {
        return array_key_exists($key, $array) ? $array[$key] : null;
    }
    ?>
    <div id="city">
        <p>В каком городе ищем</p>
        <select >
            <!--<option value="Николаев">Николаев</option>-->
        </select>
    </div>


    <table id="searchTable">
        <tbody>
        <tr>
            <td align="center">Что ищем</td>
            <td align="center">Чего не должно быть</td>
        </tr>
        </tbody>
    </table>
    <button id="sendDataToSearch">Отправить данные на обработку</button>
</div>
<div id="searchResult">
    <div>Search Result</div>
</div>