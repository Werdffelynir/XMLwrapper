<?php
/**
 * Created by PhpStorm.
 * User: Werdffelynir
 * Date: 29.07.14
 * Time: 1:19
 */

require_once ('Controller.php');

class ControllerIndex extends Controller
{

    public function init()
    {
        $this->innerLayout(array(
            'TITLE'=>'ControllerIndex',
            'RIGHT'=>'Иногда, в создаваемых веб-приложениях необходимо использовать фронт-контроллер, использующий человечески-понятную строку запроса. Как правило, начинающие PHP-программисты делают при этом перенаправление всей строки запроса в нужный скрипт. ',
        ));
    }

    public function actionIndex()
    {
        $this->render(true, array(
            'title'=>'Hello Requestor!',
            'content'=>'Несмотря на то, что данный пример легко найти в любом поисковике за полторы минуты, и он при этом прекрасно работает, хотелось бы отметить один нюанс для начинающих PHP-программистов.',
        ));
    }

    public function actionBlog()
    {
        $this->render(true, array(
            'title'=>'Hello actionBlog!',
            'content'=>'В некоторых случаях при таком использовании mod_rewrite, скрипт будет вызываться неявно несколько раз. И если в коде скрипта до обработки строки запроса присутствуют какие-либо действия с базой данных, или другой функционал, затрагивающий важные данные — он тоже будет выполняться несколько раз, что в некоторых случаях может привести ко крайне нежелательным последствиям, не говоря уже о лишней нагрузке на сервер.',
        ));
    }

    public function actionLogin()
    {
        $this->render(true, array(
            'title'=>'Hello actionLogin!',
            'content'=>'У себя, например, я это заметил лишь когда счётчик посещения страницы по необъяснимым причинам инкрементировался дважды при одном посещении страницы, что для меня поначалу было абсолютно непостижимо.',
        ));
    }

    public function actionParams($category=null,$id=null)
    {
        var_dump($category,$id);
    }


} 