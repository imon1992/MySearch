window.onload = function () {

    document.getElementById('addFieldToSearch').onclick = addFields;
    document.getElementById('sendDataToSearch').onclick = getAndSendSearchData;
    if (document.getElementById('searchTag') != null)
        addCity();
    if (document.getElementById('withCityOrNot') != null) {
        document.getElementById('withCityOrNot').onchange = function () {
            if (document.getElementById('city').style.display == 'none') {
                document.getElementById('city').style.display = 'block';
            } else {
                document.getElementById('city').style.display = 'none'
            }
        }
    }
    if (document.getElementById('withDateOrNot') != null) {
        if (document.getElementById('withDateOrNot').checked == false) {
            document.getElementById('withDateOrNot').onchange = function () {
                if (document.getElementById('date').style.display == 'block') {
                    document.getElementById('date').style.display = 'none';
                } else {
                    document.getElementById('date').style.display = 'block'
                }
            }
        }
    }
    //console.log(document.getElementById('city'));
    if (document.getElementById('searchTag') != null)
        document.getElementById('searchTag').onchange = addCity;

    function addFields() {
        var searchTable = document.getElementById('searchTable');
        var tr = document.createElement('tr');
        var td1 = document.createElement('td');
        var td2 = document.createElement('td');
        var button = document.createElement('button');
        var input1 = document.createElement('input');
        var input2 = document.createElement('input');
        var tbody = searchTable.getElementsByTagName('tbody')[0];
        tbody.appendChild(tr);
        tr.appendChild(td1);
        tr.appendChild(td2);
        td2.appendChild(button);
        td1.appendChild(input1);
        button.innerText = 'Добавление колонки для того чего быть не должно';
        button.setAttribute('id', 'addNotPresentedField');
        //button.style.width = '150px';
        td1.setAttribute('height', '50');
        td2.setAttribute('height', '50');
        addButtonAction();
    }

    function addButtonAction() {
        var searchTable = document.getElementById('searchTable').children[0];
        //console.log(searchTable)
        var trCount = searchTable.children.length;
        //console.log(trCount);
        for (i = 1; i < trCount; i++) {
            searchTable.rows[i].lastChild.onclick = addNotPresentedField;
        }
    }


    function addNotPresentedField() {
        var tr = this.parentElement;
        var td = document.createElement('td');
        td.setAttribute('height', '50');
        td.style.display = 'block';
        var input = document.createElement('input');
        tr.insertBefore(td, this);
        td.appendChild(input);
    }

    function addCity() {
        //var body = document.getElementById('search');
        //var children = body.childNodes;
        //
        //while (children.length) {
        //    body.removeChild(children[0]);
        //}
        var city = document.getElementById('city').lastElementChild;
        var children = city.childNodes;
        while (children.length) {
            city.removeChild(children[0]);
        }
        //console.log(city);

        var searchTag = document.getElementById('searchTag').value;
        console.log(searchTag);
        function addOptionToSelect(data) {
            data = JSON.parse(data);
            for (var position in data) {
                console.log(data[position]['city'])
                var option = document.createElement('option');
                option.innerText = data[position]['city'];
                option.setAttribute('value', data[position]['city'])
                //var searchTown = document.getElementById('city')
                city.appendChild(option);
            }
        }

        function consoleLogAjaxError(data) {
            data = JSON.parse(data);
            console.log(data.response);
            console.log(data);
        }

        var site = window.location.search;
        if (document.getElementById('city').style.display != 'none') {
            ajax('ajaxHandlers/searchQueryHandler.php?tag=' + searchTag + '&site=' + site,
                addOptionToSelect,
                true,
                'GET')
        }
    }

    function getAndSendSearchData() {
        //console.log(document.getElementById('withCityOrNot').checked);
        var searchArray = getSearch();
        var notPresentedArray = getNotPresented();
        var date = getDate();
        var searchTag = document.getElementById('searchTag').value;
        var city = document.getElementById('city');
        console.log(city)
        var site = window.location.search;
        //console.log(city);
        var searchLength = searchArray.length;
        var searchDataArray = new Array();
        if (city === null) {

            //searchDataArray[0] = new Array(searchTag);
            searchDataArray[0] = {'searchTag': searchTag, 'site': site};
        } else {
            city = city.lastElementChild.value;
            searchDataArray[0] = {'searchTag': searchTag, 'site': site, 'city': city};
        }
        console.log(searchDataArray);
        if (document.getElementById('withDateOrNot').checked) {
            searchDataArray[0].date = date;
        }
        if (document.getElementById('withCityOrNot') != null) {

            //if (document.getElementById('withCityOrNot').checked == false) {
            //    searchDataArray[0].withCityOrNot = false;
            //    //console.log((searchDataArray));
            //}else{
            //    searchDataArray[0].withCityOrNot = true;
            //}
        }

        for (i = 0; i < searchLength; i++) {
            searchDataArray[i + 1] = {
                "name": searchArray[i],
                "search": new Array(),
                "notPresented": new Array()
            };
            searchDataArray[i + 1].search[0] = {"name": searchArray[i]};
            var notPresentedLength = notPresentedArray[i].length;
            searchDataArray[i + 1].notPresented[0] = {};
            for (j = 0; j < notPresentedLength; j++) {
                var name = 'name' + j;
                searchDataArray[i + 1].notPresented[0][name] = notPresentedArray[i][j];
            }
        }

        function showResult(data) {
            data = JSON.parse(data);
            console.log(data)
            for (var val in data) {
                //console.log(data[val]);
                //console.log(val);
                var div = document.getElementById('searchResult');
                var p = document.createElement('p');
                div.appendChild(p);
                //document.body.appendChild(div);
                p.innerText = val + " : " + data[val];
            }
        }

        function consoleLogAjaxError(data) {
            data = JSON.parse(data);
            //console.log(data.response);
            console.log(data);
        }

        var searchData = JSON.stringify(searchDataArray);
        searchData = 'searchData=' + searchData;
        waitingForResponse();
        console.log(searchData);

        ajax('ajaxHandlers/searchQueryHandler.php',
            showResult,
            true,
            'POST', searchData);
    }

    function getSearch() {
        var searchTableTbody = document.getElementById('searchTable').children[0];
        var trCount = searchTableTbody.children.length;
        var searchArray = new Array();
        for (var i = 0; i < trCount; i++) {
            if (i != 0) {
                searchArray[i - 1] = searchTableTbody.children[i].children[0].children[0].value;
            }
        }
        return searchArray;
    }

    function getNotPresented() {
        var searchTableTbody = document.getElementById('searchTable').children[0];
        var tbody = searchTableTbody.children;
        var trCount = searchTableTbody.children.length;
        var notPresentedArray = new Array();
        for (i = 1; i < trCount; i++) {
            notPresentedArray[i - 1] = new Array();
            var tdInTrCount = tbody[i].children.length - 1;
            for (j = 1; j < tdInTrCount; j++) {
                var notPresented = tbody[i].children[j].children[0].value;
                notPresentedArray[i - 1][j - 1] = notPresented;
            }
        }
        return notPresentedArray;
    }

    function getDate() {
        //if(document.getElementById(''))
        var from = document.getElementById('from').value;
        var by = document.getElementById('by').value;
        var date = {
            'from': from,
            'by': by
        };
        return date
    }

    function ajax(url, callback, type, method, params, header) {
        if (params == undefined) {
            params = '';
        }
        var xmlHttp = getXmlHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                callback(xmlHttp.response);
        }
        if (method == 'POST') {
            xmlHttp.open(method, url, type);
            xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        } else {

            xmlHttp.open(method, url, type);
        }
        if (params != '' && params != undefined) {
            xmlHttp.send(params);
        } else {
            xmlHttp.send(null);
        }
    }

    function waitingForResponse() {
        var searchResult = document.getElementById('searchResult');
        var children = searchResult.childNodes;

        while (children.length) {
            searchResult.removeChild(children[0]);
        }
        var waitingResult = document.createElement('div');

    }

}
