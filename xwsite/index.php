<?php
define('TIMESTART',microtime(true));
include_once '../source/XMLWrapper.php';
$xml = new XMLWrapper(__DIR__.'/database/');
$request = (isset($_GET['r'])) ? preg_replace('|[^a-z0-9_-]|','',trim($_GET['r'])) : 'main';

$title = strtoupper($request);
$content = '<h1> Error 404 Page Not Found! </h1>';
$right = null;


$xml->select('blog');
$xml->items($request,'group');
if(isset($_GET['id'])) $xml->items($_GET['id']);
$result = $xml->result();

if(is_array($result)){
    $content = null;
    foreach($result as $item){
        $content .= '<div class="item">';
        $content .= '   <h2><a href="?r=main&id='.$item['attr']['id'].'">'.$item['title'].'</a></h2>';

        if(!isset($_GET['id'])){
            $itemsContents = cutText(strip_tags( $item['content'] ));
        }else{
            $itemsContents = $item['content'];
            $title = $item['title'];
        }

        $content .= '   <div class="item-content">'.$itemsContents.'</div>';
        $content .= '   <div class="item-footer"><i><b>Опубликовано: </b>'.date('m.d.Y H:i', $item['attr']['date']).'. <b>Категория: </b>'.$item['attr']['group'].'</i></div>';
        $content .= '</div>';
    }
}

function cutText($text, $length = 25, $end = '...'){
    $text = implode( ' ', array_slice( explode( ' ', $text ), 0, $length ) ).$end;
    unset($arr);
    return $text;
}


include_once 'main.php';