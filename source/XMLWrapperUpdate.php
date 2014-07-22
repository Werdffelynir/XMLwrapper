<?php
/**
 * Created by PhpStorm.
 * User: Comp-2
 * Date: 22.07.14
 * Time: 14:31
 */

class XMLWrapperUpdate
{
  public $fileName=null;
  public $xml=null;
  public $xmlSelect=null;
  public $selectValue=null;     // array('id'=>1)
  public $selectLocation=null;  // attr

  public function select()
  {

  }

  public function updateItem($itemSelector, $itemValue)
  {
    $selectKey = key($this->selectValue);
    $selectVal = $this->selectValue[$selectKey];
    $updateRows = 0;

    if($this->selectLocation=='attr'){
      foreach($this->xml->item as $item){
        if($item[$selectKey]==$selectVal){
          if(isset($item->$itemSelector)){
            $item->$itemSelector = $itemValue;
            $updateRows++;
          }
        }
      }
    }else if($this->selectLocation=='item'){
      foreach($this->xml->item as $item){
        if($item->$selectKey==$selectVal){
          if(isset($item->$itemSelector)){
            $item->$itemSelector = $itemValue;
            $updateRows++;
          }
        }
      }
    }

    if($updateRows===0)
      return false;
    return $updateRows;
  }


  public function updateAttr($itemSelector, $itemValue)
  {
    $updateRows = 0;

    if(isset($this->xml[$itemSelector])){
      $this->xml[$itemSelector] = $itemValue;
      $updateRows++;
    }

    if($updateRows===0)
      return false;
    return $updateRows;
  }



  public function updateItemAttr($itemSelector, $itemValue)
  {
    $selectKey = key($this->selectValue);
    $selectVal = $this->selectValue[$selectKey];
    $updateRows = 0;

    if($this->selectLocation=='attr'){
      foreach($this->xml->item as $item){
        if($item[$selectKey]==$selectVal){
          if(isset($item[$itemSelector])){
            $item[$itemSelector] = $itemValue;
            $updateRows++;
          }
        }
      }
    }else if($this->selectLocation=='item'){
      foreach($this->xml->item as $item){
        if($item->$selectKey==$selectVal){
          if(isset($item[$itemSelector])){
            $item[$itemSelector] = $itemValue;
            $updateRows++;
          }
        }
      }
    }

    if($updateRows===0)
      return false;
    return $updateRows;
  }


  public function save()
  {
    if($xml = $this->xml){
      return array('fileName'=>$this->fileName,'data'=>$xml);
    } else
      return false;
  }

}