<?php

include_once '../source/XMLWrapper.php';

$xml = new XMLWrapper(array('dbPath'=>__DIR__.'/database/'));


$request = (isset($_GET['r'])) ? preg_replace('|[^a-z0-9_-]|','',trim($_GET['r'])) : 'main';

$xml->select('blog');
//$item = $xml->item(1);
$sortGroups = $xml->sort('group',null,false);
//$sortGroups = $xml->sort('id','DESC');
//$item = $xml->item(2);
//$attrs = $xml->attr();


//var_dump($xml->listDocs());
var_dump($sortGroups);























