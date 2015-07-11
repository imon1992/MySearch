<?php
header("Content-Type: text/html; charset=utf-8");
include_once '../lib/simpl/simple_html_dom.php';
include_once '../fillingTheDatabase/dou/updateDb_dou.php';

$html = file_get_html("../html/douSearchTable.html");

foreach($html->find('#searchTag option') as $elements){
    $searchTags[] = $elements->value;
//    echo $elements->value;
}
//var_dump(http_response_code());
        $searchQueryDouAddToDb = new UpdateDb_dou();
foreach($searchTags as $searchTag){
//    echo $searchTag;
    var_dump(http_response_code());
    $searchQueryDouAddToDb->updateDb($searchTag);
//    throw new Exception();
}
//$url = 'http://localhost/Search/admin/updateDb_dou.php';

//print_r(get_headers($url));
echo 'Обновление базы данных DOU успешно завершено';

?>

<a href="http://localhost/Search/admin/adminPanel.php">Назад к панеле администратора</a>