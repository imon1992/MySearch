<?php
header("Content-Type: text/html; charset=utf-8");
include_once '../lib/simpl/simple_html_dom.php';
include_once '../fillingTheDatabase/dou/updateDb_dou.php';

$html = file_get_html("../html/douSearchTable.html");

foreach ($html->find('#searchTag option') as $elements) {
    $searchTags[] = $elements->value;
}

$searchQueryDouAddToDb = new UpdateDb_dou();
foreach ($searchTags as $searchTag) {

    $searchQueryDouAddToDb->updateDb($searchTag);

}

echo 'Обновление базы данных DOU успешно завершено';

?>

<a href="adminPanel.php">Назад к панеле администратора</a>