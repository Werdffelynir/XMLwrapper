<?php

/**
 * Class XMLWrapperSelect
 *
 * Author: OLWerdffelynir
 * Date: 22.07.14
 * Time: 16:46
 */

class XMLWrapperSelect
{

    public $fileName = null;
    public $filePath = null;

    public $xml = null;
    public $docAttr = null;


    public $selectType = null;
    public $itemsArray = null;
    public $itemsArrayWhere = null;
    public $itemsArrayAnd = null;
    public $itemsArrayOr = null;
    public $itemsArrayResult = null;

    public function init($xml)
    {
        $this->xml = $xml;
        $xmlArray = (array) $xml;

        $this->docAttr = $xmlArray['@attributes'];
        $this->itemsArray = array_map(function ($array) {
            $array = (array)$array;
            $newDataArray = $array;
            array_shift($newDataArray);
            $newDataArray['attr'] = $array['@attributes'];
            return $newDataArray;
        }, $xmlArray['item']);

        $xml = null;
        $xmlArray = null;
    }


    public function items($id, $attr)
    {
        $this->selectType = 'items';
        $result = null;

        if ($id == null) {
            $this->itemsArrayResult = $this->itemsArray;
        } else {

            if ($attr == null) {
                foreach ($this->itemsArray as $item) {
                    if ($item['attr']['id'] == $id) {
                        $result[] = $item;
                    }
                }
            } else if ($attr != null) {

                foreach ($this->itemsArray as $item) {
                    if ($item['attr'][$attr] == $id) {
                        $result[] = $item;
                    }
                }
            } else return null;
        }
        $this->itemsArrayResult = $result;
    }

    public function object()
    {
        $this->itemsArrayResult = $this->xml;
    }

    public function where($rule)
    {
        $this->selectType = 'where';
        $this->itemsArrayResult = $this->rules($this->itemsArray, $rule);
    }

    public function whereAnd($rule)
    {
        if ($this->itemsArrayResult == null) return false;
        $this->itemsArrayResult = $this->rules($this->itemsArrayResult, $rule);
        return true;
    }

    public function whereOr($rule)
    {
        if ($this->itemsArrayResult == null) return false;
        $itemsWhereOr = $this->rules($this->itemsArray, $rule);
        $this->itemsArrayResult = array_merge($this->itemsArrayResult,$itemsWhereOr);
        return true;
    }

    protected function rules($itemsArray, $rule)
    {
        $itemsNew = null;
        $col = null;
        $val = null;

        if ($el = stripos($rule, '<=')) {
            $col = mb_strtolower(trim(substr($rule, 0, $el)));
            $val = mb_strtolower(trim(substr($rule, $el + 2)));
            foreach ($itemsArray as $item) {
                if ($item['attr'][$col] <= $val)
                    $itemsNew[] = $item;
            }
        } else
        if ($el = stripos($rule, '>=')) {
            $col = mb_strtolower(trim(substr($rule, 0, $el)));
            $val = mb_strtolower(trim(substr($rule, $el + 2)));
            foreach ($itemsArray as $item) {
                if ($item['attr'][$col] >= $val)
                    $itemsNew[] = $item;
            }
        } else
        if ($el = stripos($rule, '!=')) {
            $col = mb_strtolower(trim(substr($rule, 0, $el)));
            $val = mb_strtolower(trim(substr($rule, $el + 2)));
            foreach ($itemsArray as $item) {
                if ($item['attr'][$col] != $val)
                    $itemsNew[] = $item;
            }
        } else
        if ($el = stripos($rule, '<')) {
            $col = mb_strtolower(trim(substr($rule, 0, $el)));
            $val = mb_strtolower(trim(substr($rule, $el + 1)));
            foreach ($itemsArray as $item) {
                if ($item['attr'][$col] < $val)
                    $itemsNew[] = $item;
            }
        } else
        if ($el = stripos($rule, '>')) {
            $col = mb_strtolower(trim(substr($rule, 0, $el)));
            $val = mb_strtolower(trim(substr($rule, $el + 1)));
            foreach ($itemsArray as $item) {
                if ($item['attr'][$col] > $val)
                    $itemsNew[] = $item;
            }
        } else
        if ($el = stripos($rule, '=')) {
            $col = mb_strtolower(trim(substr($rule, 0, $el)));
            $val = mb_strtolower(trim(substr($rule, $el + 1)));
            foreach ($itemsArray as $item) {
                if (mb_strtolower($item['attr'][$col]) == $val)
                    $itemsNew[] = $item;
            }
        }
        return $itemsNew;
    }


    public function result()
    {
        return $this->itemsArrayResult;
    }


    public function sort($attr, $asc, $num)
    {
        $selectData = $this->itemsArrayResult;

        if ($num) {
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

        if (strtoupper($asc) == 'DESC') {
            $selectData = array_reverse($selectData);
        }

        $this->itemsArrayResult = array_values($selectData);
    }

} 