<?php


class XMLWrapperInsert
{
  public $fileName=null;
  public $xml=null;
  public $itemData=null;
  public $structureItem=null;
  public $structureAttr=null;

  public function init()
  {

    if($this->itemData==null){
      $this->itemData = $this->xml->addChild('item');
      foreach($this->structureAttr as $attr){
        $this->itemData->addAttribute($attr,'');
      }
      foreach($this->structureItem as $item){
        $this->itemData->addChild( $item, null);
      }
    }
  }
  public function item($itemSelector, $itemValue)
  {
    $this->init();

    if(is_string($itemSelector) && $itemValue!==false)
    {
      if(in_array($itemSelector,$this->structureItem))
        $this->itemData->$itemSelector = $itemValue;//$this->itemData->addChild($itemSelector,$itemValue);
      else
        return false;
    }
    else if(is_array($itemSelector))
    {
      foreach($itemSelector as $itemKey=>$itemValue){
        if(in_array($itemKey,$this->structureItem))
          $this->itemData->$itemKey = $itemValue; //$this->itemData->addChild($itemKey,$itemValue);
        else
          return false;
      }
    }
  }

  public function attr($itemSelector, $itemValue)
  {
    $this->init();

    if(is_string($itemSelector) && $itemValue!==false)
    {
      if(in_array($itemSelector,$this->structureAttr))
        $this->itemData[$itemSelector] = $itemValue;//$this->itemData->addAttribute($itemSelector,$itemValue);
      else
        return false;
    }
    else if(is_array($itemSelector))
    {
      foreach($itemSelector as $itemKey=>$itemValue){
        if(in_array($itemKey,$this->structureAttr))
          $this->itemData[$itemKey] = $itemValue; //$this->itemData->addAttribute($itemKey,$itemValue);
        else
          return false;
      }
    }
  }

  public function save()
  {
    $iterator = (int) $this->xml['iterator'];
    //$countItems = count($this->xml->item);

    $this->attr('id', $iterator);
    $this->xml['iterator'] = $iterator+1;
    $this->xml['date'] = time();

    if(!empty($this->xml)) {
      return array('fileName'=>$this->fileName,'data'=>$this->xml);
    } else
      return false;
  }

}