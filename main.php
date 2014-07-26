<?
define('TIMESTART',microtime(true));

//include './source/XMLWrapper.php';
//$xml = new XMLWrapper();


/*  *   *   *   *   *   *   *   *   *   *   *   *   *   *
 * select
 */

/*
$xml->select('articles');
$xml->items(2);
$records = $xml->result();
*/

/*
$records = $xml->select('articles')
    ->items('php','group')
    ->result();
*/

/*
$records = $xml->select('articles')
    ->where('order>5')
    ->whereOr('id=1')
    ->sort('id','ASC')
    ->result();
*/

/*
foreach($records as $item){
    echo "<h1> <a href='".$item['attr']['url']."'>".$item['title']."</a> </h1>";
    echo "<div>".$item['content']."</div>";
    echo "<i>Опубликовано: ".date('m.d.Y H:i', $item['attr']['date']).". Категория (".$item['attr']['group'].") </i>";
}
*/

/*  *   *   *   *   *   *   *   *   *   *   *   *   *   *
 * create new document
 */
/*
$xml->create('newDocCreate');
$xml->createAttrs(array(
    'group' => 'group',
    'date' => date('m/d/Y H:i:s', time() - 3600 * 24 * 30),
));
$xml->createItems(array(
    'title' => 'title',
    'charset' => 'charset',
    'keywords' => 'keywords',
    'description' => 'description',
));
$xml->createItemsAttrs(array(
    'group' => '',
    'date' => '',
    'url' => '',
    'order' => '',
));
$xml->save();
*/






/*  *   *   *   *   *   *   *   *   *   *   *   *   *   *
 * update

$xml->update('newDocCreate', 1);
$xml->update('newDocCreate', array('title'=>'titleText'), 'item');

$xml->updateItem('title','update title BY titleText!');
$xml->updateItem('charset','update windows-1251');
$xml->updateAttr('group','testDoc');
$xml->updateItemAttr('url','https://bce.cosmonova.net/service/payu/generate.php');
$xml->save();
 */






/*  *   *   *   *   *   *   *   *   *   *   *   *   *   *
 * insert Создание новой записи
 */
/*$xml->insert('newDocCreate');

$xml->insertItem('title','insert title!');
$xml->insertItem(
    array(
        'charset'=>'insert charset UTF-8!',
        'keywords'=>'insert KEYWORDS!',
));

$xml->insertAttr('order','120');
$xml->insertAttr(
    array(
        'url'=>'https://bce.cosmonova.net',
        'group'=>'killers',
));
$xml->save();*/





/*  *   *   *   *   *   *   *   *   *   *   *   *   *   *
 * delete

$xml->delete('newDocCreate');
$xml->save();
 */





echo "<hr>".round(microtime(true)-TIMESTART,4).'sec.';