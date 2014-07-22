<?php


class XMLWrapperCreate
{
  public $encoding = 'UTF-8';
  public $iterator = 1;
  public $fileName = null;
  public $items = array();
  public $attrRoot = array();
  public $attrItem = array();

  public $xml = null;

	public function templateXml($rootAttrs,$itemAttrs,$itemRows)
  {
		$xml = "<?xml version=\"1.0\" encoding=\"$this->encoding\"?>

<root $rootAttrs>

  <item $itemAttrs >
$itemRows

  </item>

</root>";

    return $xml;
	}

	public function save()
  {

    $attrsRootDefault = array(
        'iterator'=>2,
        'group'=>'',
        'date'=>time(),
    );
    $attrsItemDefault = array(
        'id'=>1,
        'order'=>1,
        'group'=>'',
        'date'=>time(),
        'url'=>'',
    );

    $rootAttrsString = " ";
    foreach(array_merge($attrsRootDefault,$this->attrRoot) as $attrKey=>$attrVal)
      $rootAttrsString .= $attrKey."=\"".$attrVal."\" ";

    $itemAttrsString = " ";
    foreach(array_merge($attrsItemDefault,$this->attrItem) as $attrKey=>$attrVal)
      $itemAttrsString .= $attrKey."=\"".$attrVal."\" ";

    $itemsString = "";
    foreach($this->items as $attrKey=>$attrVal)
      $itemsString .= "\n\t<".$attrKey.">".$attrVal."</".$attrKey.">";

    $xmlString = $this->templateXml($rootAttrsString,$itemAttrsString,$itemsString);
    $xml = new SimpleXMLElement($xmlString);
    $this->xml = $xml;

    if($this->xml){
      $this->iterator ++;
      return array('fileName'=>$this->fileName,'data'=>$xml);
    }else
      return false;
	}


  public function addItems(array $items)
  {
    $this->items = array_merge($this->items, $items);
  }


  public function addAttrs(array $attrs=array())
  {
    $this->attrRoot = $attrs;
  }

  public function addItemsAttrs(array $itemsAttrs)
  {
    $this->attrItem = $itemsAttrs;
  }

}
