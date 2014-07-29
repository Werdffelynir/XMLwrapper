<?php
/**
 * Created by PhpStorm.
 * User: Werdffelynir
 * Date: 29.07.14
 * Time: 1:36
 */

class Controller
{
    public $layout = 'main';
    public $partial = 'main';
    public $innerLayout = array();

    public function __construct()
    {
        $this->init();
    }

    public function init() { }

    public function renderPartial($partial=true, array $data = array(), $returned=true)
    {
        if(is_string($partial))
            $this->partial = $partial;

        $viewPartial = Request::$path.'/views/'.$this->partial.'.php';

        ob_start();
        extract($data);
        if(is_file($viewPartial))
            require_once($viewPartial);

        $view = ob_get_clean();

        if($returned)
            return $view;
        else
            echo $view;
    }

    public function render($partial=true, array $data = array(), $returned=false)
    {
        if(is_string($partial))
            $this->partial = $partial;

        $viewPartial = Request::$path.'/views/'.$this->partial.'.php';

        ob_start();
        extract($data);
        if(is_file($viewPartial))
            require_once($viewPartial);

        $this->innerLayout['CONTENT'] = ob_get_clean();

        $this->renderLayout($returned);
    }

    public function renderLayout($returned)
    {
        $viewLayout = Request::$path.'/views/layout/'.$this->layout.'.php';

        ob_start();
        extract($this->innerLayout);
        if(is_file($viewLayout))
            require_once($viewLayout);

        $view = ob_get_clean();

        if($returned)
            return $view;
        else
            echo $view;
    }

    public function innerLayout(array $data)
    {
        foreach($data as $variablePosition=>$variableData){
            $this->innerLayout[$variablePosition]=$variableData;
        }
    }
} 