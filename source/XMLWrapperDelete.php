<?php
/**
 * Created by PhpStorm.
 * User: Comp-2
 * Date: 22.07.14
 * Time: 14:32
 */

class XMLWrapperDelete {

    public $fileName = null;
    public $path = null;
    public $dump = null;

    public function delete()
    {
        if($this->dump){
            rename($this->path.$this->fileName.'.xml',$this->path.'_dump_'.date('d.m.y_H-i-s').'_'.$this->fileName.'.xml');
        }else{
            unlink($this->path.$this->fileName.'.xml');
        }
    }

} 