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
    public $xml = null;
    public $dump = null;
    public $itemId = null;

    public function delete($fileName, $itemId=null)
    {
        $this->fileName = $fileName;

        if($itemId != null)
            $this->item($itemId);
    }

    public function doc($makeDump)
    {
        $this->dump = $makeDump;
    }

    public function item($itemId)
    {
        $xml = new DOMDocument;
        $xml->load($this->path.$this->fileName.'.xml');

        $itemsDoc = $xml->documentElement;
        $list = $itemsDoc->getElementsByTagName('item');

        foreach ($list as $domElement){
            $attrValue = $domElement->getAttribute('id');
            if ($attrValue == $itemId) {
                $itemsDoc->removeChild($domElement);
            }
        }

        $this->xml = $xml->saveXML();
    }

    public function save()
    {
        if($this->dump === true){
            rename($this->path.$this->fileName.'.xml',$this->path.'_dump_'.date('d.m.y_H-i-s').'_'.$this->fileName.'.xml');
        }else if($this->dump === false){
            unlink($this->path.$this->fileName.'.xml');
        }

        if ($this->xml) {
            file_put_contents($this->path.$this->fileName.'.xml', $this->xml);
        } else
            return false;
    }
} 