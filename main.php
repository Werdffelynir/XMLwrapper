<?php

include './source/DBxml.php';

$xml = new DBxml();

/*
$xml->config(array(
	'dbPath'=>__DIR__.'/xml/';
	));
*/

/* @ready Возвращает список файлов в каталоге XML DB
$listDocs = $xml->listDocs();
*/


/*
$records = $xml->doc('articles')->item(1);
$title = $records->title;

$title = $xml->doc('articles')->item(1,'title');

$xml->doc('articles');
$title = $xml->item(1,'title');

$xml->doc('articles');
$records = $xml->item(1);
$title = $records->title;
*/





//$articles = $xml->doc('articles')->item;
//var_dump($articles);

/*
foreach($xml->doc('articles')->item as $items){
  var_dump($items);
}
*/

foreach($xml->doc('articles')->common->rating as $items){
  var_dump($items);
}


//$articles = $xml->doc('articles');

/*
foreach($articles->items() as $item){
  echo "<h2> <a href=\"$item[id]\"> $item->title</a> </h2>";
  echo "<div> $item->content </div>";
}
*/

/*
$items = $articles->items(1);
echo "<h2> <a href=\"$items[id]\"> $items->title</a> </h2>";
echo "<div> $items->content </div>";
*/


