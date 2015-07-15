<?php
header("Content-Type: text/html; charset=utf-8");
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="http://localhost/Search/style1.css"/>
    <script src="getHmlHttpRequest.js" type="text/javascript"></script>
    <script src="MySearch.js" type="text/javascript"></script>
    <script src="calendar.js" type="text/javascript"></script>
    <title>VacationSkillSearcher</title>
</head>
<body>
<div id="head">
    <h1>VacationSkillSearcher</h1>
</div>
<div id="menu">
    <div><a href="http://localhost/Search/newStyle.php?rabota">Поиск на rabota</a></div>
    <div><a href="http://localhost/Search/newStyle.php?dou">Поиск на DOU</a></div>
    <div><a href="http://localhost/Search/newStyle.php?stackoverflow">Поиск на stackoverflow</a></div>
</div>
<div id="content">
<!--    <h2>Главная</h2>-->
    <?
    include_once 'html/template/searchTable.php';

    ?>
</div>
<!--<div id="foot">-->
<!--</div>-->
</body>
</html>