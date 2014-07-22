<?php

include './source/XMLWrapper.php';

$xml = new XMLWrapper();

/*
$xml->config(array(
	'dbPath'=>__DIR__.'/xml/';
	));
*/

/* @ready Возвращает список файлов в каталоге XML DB
$listDocs = $xml->listDocs();
*/


/* @ready
$records = $xml->doc('articles')->item(3);
$records = $xml->doc('articles')->item(3,'title');
*/


/* @ready doc attr. Возвращает атрибут документа
$records = $xml->doc('articles')->id; // return string '6'
*/


/* @ready doc items. Возвращает обект, первый елемент обекта под инексом [0]
$records = $xml->doc('articles')->item; // return object(SimpleXMLElement)
*/

/* @ready
$records = $xml->doc('articles')->attr(); // return array все атрибуты
 */

/* @ready
$records = $xml->doc('articles')->attr('id'); // return string '6'
 */



/* @ready
$items = $xml->doc('articles')->item;
//$items = $xml->doc('articles')->item();
foreach($items as $item){
    echo "<h1> <a href='$item[url]'>$item->title</a> </h1>";
    echo "<div>$item->content</div>";
    echo "<i>Опубликовано: ".date('m.d.Y H:i', ''+$item['date']).". Категория ($item[group]) </i>";
}
 */


/*
foreach($xml->doc('articles')->common->rating as $items){
    var_dump($items);
}
*/

//$articles = $xml->doc('articles');

/*
foreach ($articles->items() as $item) {
    echo "<h2> <a href=\"$item[url]\"> $item->title</a> </h2>";
    echo "<div> $item->content </div>";
    echo <i>Опубликовано: ".date('m.d.Y H:i', (string)$item['date']).". Категория (".$item['group'].") </i>";
}
*/





/* @ready toArray(TYPE=1) default
$articles = $xml->doc('articles')->toArray();
foreach ($articles as $item) {
echo "<h2> <a href=\"$item[url]\">$item[title]</a> </h2>";
echo "<div> $item[content] </div>";
echo "<i>Опубликовано: ".date('m.d.Y H:i', $item['date']).". Категория ($item[group]) </i>";
}
*/


/* @ready toArray(TYPE) TYPE 2
$articles = $xml->doc('articles')->toArray(2);
foreach ($articles as $item) {
    echo "<h2> <a href=\"".$item['url']."\">".$item['item']['title']."</a> </h2>";
    echo "<div> ".$item['item']['content']." </div>";
    echo "<i>Опубликовано: ".date('m.d.Y H:i', $item['date']).". Категория (".$item['group'].") </i>";
}
*/

/* @ready toArray(TYPE) TYPE 3
$articles = $xml->doc('articles')->toArray(3);
foreach ($articles as $item) {
echo "<h2> <a href=\"".$item['attr']['url']."\">$item[title]</a> </h2>";
echo "<div> $item[content] </div>";
echo "<hr/><i>Опубликовано: ".date('m.d.Y H:i', $item['attr']['date']).". Категория (".$item['attr']['group'].") </i>";
}
*/
















