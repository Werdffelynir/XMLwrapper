<?php

include './source/XMLWrapper.php';

$xml = new XMLWrapper();

/** @ready
$xml->config(array(
	'dbPath'=>__DIR__.'/xml/';
	));
*/

/* @ready Возвращает список файлов в каталоге XML DB
 * $listDocs = $xml->listDocs();
 */


/* @ready
$records = $xml->doc('articles')->item(3);
 * $records = $xml->doc('articles')->item(3,'title');
 */


/* @ready doc attr. Возвращает атрибут документа
 * $records = $xml->doc('articles')->id; // return string '6'
 */


/* @ready doc items. Возвращает обект, первый елемент обекта под инексом [0]
 * $records = $xml->doc('articles')->item; // return object(SimpleXMLElement)
 */

/* @ready
$records = $xml->doc('articles')->attr(); // return array все атрибуты
 */

/* @ready
$records = $xml->doc('articles')->attr('id'); // return string '6'
 */


/* @ready
$items = $xml->doc('articles')->item;
 * //$items = $xml->doc('articles')->item();
 * foreach($items as $item){
 * echo "<h1> <a href='$item[url]'>$item->title</a> </h1>";
 * echo "<div>$item->content</div>";
 * echo "<i>Опубликовано: ".date('m.d.Y H:i', ''+$item['date']).". Категория ($item[group]) </i>";
 * }
 */



/* @ready toArray(TYPE=1) default
 * $articles = $xml->doc('articles')->toArray();
 * foreach ($articles as $item) {
 * echo "<h2> <a href=\"$item[url]\">$item[title]</a> </h2>";
 * echo "<div> $item[content] </div>";
 * echo "<i>Опубликовано: ".date('m.d.Y H:i', $item['date']).". Категория ($item[group]) </i>";
 * }
 */


/* @ready toArray(TYPE) TYPE 2
 * $articles = $xml->doc('articles')->toArray(2);
 * foreach ($articles as $item) {
 * echo "<h2> <a href=\"".$item['url']."\">".$item['item']['title']."</a> </h2>";
 * echo "<div> ".$item['item']['content']." </div>";
 * echo "<i>Опубликовано: ".date('m.d.Y H:i', $item['date']).". Категория (".$item['group'].") </i>";
 * }
 */

/* @ready toArray(TYPE) TYPE 3
 * $articles = $xml->doc('articles')->toArray(3);
 * foreach ($articles as $item) {
 * echo "<h2> <a href=\"".$item['attr']['url']."\">$item[title]</a> </h2>";
 * echo "<div> $item[content] </div>";
 * echo "<hr/><i>Опубликовано: ".date('m.d.Y H:i', $item['attr']['date']).". Категория (".$item['attr']['group'].") </i>";
 * }
 */

/* @no-ready toArray(TYPE) TYPE 4
 * $item['attr-url']
 * $item['a-url']
 * $articles = $xml->doc('articles')->toArray(3);
 * foreach ($articles as $item) {
 * echo "<h2> <a href=\"".$item['attr-url']."\">$item[title]</a> </h2>";
 * echo "<div> $item[content] </div>";
 * echo "<hr/><i>Опубликовано: ".date('m.d.Y H:i', $item['attr-date']).". Категория (".$item['attr-group'].") </i>";
 * }
 *
 * /* @no-ready toArray(TYPE) TYPE 5
 * $articles = $xml->doc('articles')->toArray(3);
 * foreach ($articles as $item) {
 * echo "<h2> <a href=\"".$item['attr']['url']."\">$item['item'][title]</a> </h2>";
 * echo "<div> $item['item'][content] </div>";
 * echo "<hr/><i>Опубликовано: ".date('m.d.Y H:i', $item['attr']['date']).". Категория (".$item['attr']['group'].") </i>";
 * }
 */


/* @ready create new document
$xml->createDoc('newDoc2');
$xml->addAttrs(array(
        'group' => 'group',
        'date' => date('m/d/Y H:i:s', time()-3600*24*30),
    ));
$xml->addItems(array(
        'title' => 'title',
        'charset' => 'charset',
        'keywords' => 'keywords',
        'description' => 'description',
    ));
$xml->addItemsAttrs(array(
        'group' => '',
        'date' => '',
        'url' => '',
        'order' => '',
    ));
$xml->save();
*/


/** @ready  update records
$xml->updateDoc('newDoc2', 1);
//$xml->updateDoc('newDoc2', array('keywords'=>'key'), 'item');

$xml->updateItem('title','update title!');
$xml->updateItem('charset','update windows-1251');
$xml->updateAttr('group','testDoc');
$xml->updateItemAttr('url','https://bce.cosmonova.net/service/payu/generate.php');
$xml->save();
 */


/** @ready Создание новой записи
$xml->insert('newDoc2');

$xml->insertItem('title','insert title!');
$xml->insertItem(
array(
    'charset'=>'insert charset UTF-8!',
    'keywords'=>'insert KEYWORDS!',
));

$xml->insertAttr('order','120');
$xml->insertAttr(array(
    'url'=>'https://bce.cosmonova.net',
    'group'=>'killers',
));
*/



/** @ready Удаление файлов
$xml->delete('newDoc');
$xml->save();
 */

$header[] = '<h1>Декодирование HTML-сущностей</h1>';
$header[] = '<h1>Можно заострить внимание посетителя</h1>';
$header[] = '<h1>SQL-инъекции</h1>';
$text[] = '<p>Может показаться странным, что результатом вызова trim(html_entity_decode()); не является пустая строка. Причина том, преобразуется не в символ с ASCII-кодом 32(который удаляется функцией trim())</p><p>Данная функция предназначена для отображения кода и HTML-разметки на Web-странице, но вводимые пользователем данные не мешает пропускать через неё, во избежание неприятностей. </p>';
$text[] = '<p>Следует проверять ВСЕ переменные получаемые от пользователя — GET, POST, COOKIE. Во все из них без труда можно встроить зловредный код. Особое внимание следует уделять паролям, так как в них не принято вводить ограничения, на символы.</p><p>SQL-инъекции — абсолютно другой вид атаки и он наиболее опасен. В Web-приложения, где есть данного  вида уязвимость, злоумышленник может внедрить свой SQL код, что может провести к потери информации, краже или полному  уничтожению базы данных.</p>';
$text[] = '<p>SQL-инъекциито предпринять — выбор за вами. Лучше написать о разрешенных символах где-нибудь рядом с соответствующим полем HTML-формы, иначе посетитель может вести свое имя и обнаружить что оно отображается не совсем так, как он ожидал (например Elvi$ как Elvi) и ему придется проходить процедуру регистрации повторно. Кроме того, посетитель может случайно вести неверный символ и устрашающий вид страницы plz_die.php, может его отпугнуть.</p>';




























