<?php
/**
 * Created by PhpStorm.
 * User: Comp-2
 * Date: 22.07.14
 * Time: 16:46
 */

class XMLWrapperSelect {

    public $fileName = null;
    public $path = null;
    public $xml = null;
    public $xmlAttr = null;
    public $xmlItem = null;


    public function init($xml)
    {
        $this->xml = $xml;
        $xmlArray = (array) $this->xml;
        $this->xmlAttr = $xmlArray['@attributes'];

        $this->xmlItem = array_map(function($array){
            $array = (array) $array;
            $newDataArray = $array;
            array_shift($newDataArray);
            $newDataArray['attr'] = $array['@attributes'];
            return $newDataArray;
        }, $xmlArray['item']);

    }


    public function toArray($typeBuild = 1)
    {
        $iter = 0;
        $rootItems = array();
        $xml = $this->xml;

        foreach ($xml->item as $_item) {
            $_attr = (array) $_item;
            $_attrShift = array_shift($_attr);
            if ($typeBuild == 1) {
                $rootItems[$iter] = $_attr;
                $rootItems[$iter]['attr'] = $_attrShift;
            } else {
                if ($typeBuild == 2) {
                    $rootItems[$iter] = $_attrShift;
                    $rootItems[$iter]['item'] = $_attr;
                } else {
                    $rootItems[$iter] = array_merge($_attr, $_attrShift);
                }
            }
            $iter++;
        }
        return $rootItems;
    }


    public function item($id = null, $itemElement = null)
    {
        if ($id == null) {
            return $this->xmlItem;
        } else {
            if (is_numeric($id) && $itemElement == null) {
                foreach ($this->xmlItem as $item) {
                    var_dump($item);
                    if ($item['attr']['id'] == $id) {
                        return $item;
                    }
                }
            } else {
                if (is_numeric($id) && $itemElement != null) {
                    foreach ($this->xmlItem as $item) {
                        if ($item['attr']['id'] == $id) {
                            return $item->$itemElement;
                        }
                        else return null;
                    }
                }
                else return null;
            }
        }
        return null;
    }

/*    public function attr($attr = null)
    {
        $_attrs = (array)$this->xml;
        if ($attr == null) {
            return $_attrs['@attributes'];
        } else {
            if (isset($_attrs['@attributes'][$attr])) {
                return $_attrs['@attributes'][$attr];
            } else {
                return FALSE;
            }
        }
    }*/

    public function sort($attr = 'id', $asc = 'ASC', $num = true)
    {
        $selectData = $this->xmlItem;

        if ($num){
            usort($selectData, function ($first, $second) use ($attr) {
                return ($first['attr'][$attr] - $second['attr'][$attr]);
            });
        } else {
            uasort($selectData, function ($first, $second) use ($attr) {
                if ($first['attr'][$attr] == $second['attr'][$attr])
                    return 0;
                return ($first['attr'][$attr] > $second['attr'][$attr]) ? 1 : -1;
            });
        }

        if (strtoupper($asc) == 'DESC'){
            $selectData = array_reverse($selectData);
        }

        $selectData = array_values($selectData);

        return $selectData;
    }

    /*public function __get($item)
    {
        var_dump($this->xmlItem);
        if (isset($this->xmlAttr[$item])) {
            return $this->xmlAttr[$item];
        } else {

            if (isset($this->xmlItem->$item)) {
                return $this->xmlItem->$item;
            } else {
                return null;
            }

        }
    }*/

} 