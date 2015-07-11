<?php
header("Content-Type: text/html; charset=utf-8");
include_once '../lib/simpl/simple_html_dom.php';
include_once '../fillingTheDatabase/rabota/updateDb_rabota.php';

$html = file_get_html("../html/rabotaSearchTable.html");

foreach($html->find('#searchTag option') as $elements){
    $searchTags[] = $elements->value;
//    echo $elements->value;
}
//var_dump(http_response_code());
$updateDb = new UpdateDb_stackoverflowAddToDb();
foreach($searchTags as $searchTag){
//    echo $searchTag;
    var_dump(http_response_code());
    $updateDb->updateDb($searchTag);
//    throw new Exception();
}
//$url = 'http://localhost/Search/admin/updateDb_dou.php';

//print_r(get_headers($url));
echo 'Обновление базы дфнных rabota.ua успешно завершено';

?>

<a href="http://localhost/Search/admin/adminPanel.php">Назад к панеле администратора</a>