<?php
header("Content-Type: text/html; charset=utf-8");
include_once '../lib/simpl/simple_html_dom.php';
include_once '../fillingTheDatabase/rabota/updateDb_rabota.php';

$html = file_get_html("../html/rabotaSearchTable.html");

foreach($html->find('#searchTag option') as $elements){
    $searchTags[] = $elements->value;
}

$updateDb = new UpdateDb_rabota();

foreach($searchTags as $searchTag){
    $updateDb->updateDb($searchTag);
}

echo 'Обновление базы дфнных rabota.ua успешно завершено';

?>

<a href="http://localhost/Search/admin/adminPanel.php">Назад к панеле администратора</a>