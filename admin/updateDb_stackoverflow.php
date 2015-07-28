<?php
header("Content-Type: text/html; charset=utf-8");
include_once '../lib/simpl/simple_html_dom.php';
include_once '../fillingTheDatabase/stackoverflow/updateDb_stackoverflow.php';

$html = file_get_html("../html/stackoverflowSearchTable.html");

foreach ($html->find('#searchTag option') as $elements) {
    $searchTags[] = $elements->value;

}

$searchQueryStackoverflowAddToDb = new UpdateDb_stackoverflow();
foreach ($searchTags as $searchTag) {

    $searchQueryStackoverflowAddToDb->updateDb($searchTag);

}

echo 'Обновление базы данных stackoverflow успешно завершено';

?>

<a href="http://localhost/Search/admin/adminPanel.php">Назад к панеле администратора</a>