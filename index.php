<?php
header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="ru">
<head>
    <meta charset="UTF-8">
    <script src="getHmlHttpRequest.js" type="text/javascript"></script>
    <script src="MySearch.js" type="text/javascript"></script>
    <!--    --><?// if ($_GET['dou'] !== null) { ?>
    <!--        <script src="dou.js" type="text/javascript"></script>-->
    <!--    --><?//
    //    }
    //    if ($_GET['stackoverflow'] !== null) {
    //        ?>
    <!--        <script src="stackoverflow.js" type="text/javascript"></script>-->
    <!--    --><?//
    //    }
    //    ?>
    <link rel="stylesheet" type="text/css" href="http://localhost/Search/css.css"/>
    <title>My Search</title>
</head>
<body>
<div id="wrapper">
    <table width="960" cellpadding="0" cellspacing="0" align="center" id="maintable">
        <tr>
            <td class="maintable-content">
                <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td class="content-row">
                            <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                                <tr>
                                    <td class="navbar-row" align="center">
                                        <table cellspacing="0" width="100%" align="center" class="navbar-row-table">
                                            <tr align="center" class="vbmenu_dark">


                                                <td width="100%">&nbsp;</td>

                                                <td class="vbmenu_control"><a rel="nofollow"
                                                                              href="http://localhost/Search/index.php?dou"
                                                        >Поиск на DOU</a></td>

                                                <td id="navbar_search" class="vbmenu_control"><a
                                                        href="http://localhost/Search/index.php?stackoverflow" rel="nofollow">Поиск на stackoverflow</a>
                                                </td>
                                            <tr>
                                        </table>
                                    </td>

                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

</div>


<div id="search" align="center">
    <?
    if($_GET['dou']!==null){
        include_once 'html/douSearchTable.html';
    }
    if($_GET['stackoverflow']!==null){
        include_once 'html/stackoverflowSearchtable.html';
    }
    ?>
</div>




</body>
</html>

