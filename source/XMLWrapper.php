<?php


include 'XMLWrapperCreate.php';
include 'XMLWrapperInsert.php';
include 'XMLWrapperUpdate.php';
include 'XMLWrapperDelete.php';

class XMLWrapper {
  public $config = array();

  public $_listDocs = array();
  public $_listItems = array();

  public $XMLWrapperCreate = null;
  public $XMLWrapperInsert = null;
  public $XMLWrapperUpdate = null;
  public $XMLWrapperDelete = null;



  public function __construct() {
    $this->config();
  }


  public function config(array $c = null) {
    if ($c == null) {
      $path = dirname(__DIR__) . '/db-xml/';
      if (!$this->isDir($path)) {
        $this->error('Не удалось создать директорию!');
      }
      $this->config['dbPath'] = $path;
    } else {
      if (!empty($c['dbPath'])) {
        if (!$this->isDir($c['dbPath'])) {
          $this->error('Не удалось создать директорию!');
        } else {
          $this->config = $c;
        }
      } else {
        $this->error('Параметр конфигурации должен быть массивом!');
      }
    }
  }



  public function doc($doc)
  {
    $this->xml($doc);
    return $this;
  }


  public function toArray($typeBuild = 1) {
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

  public function item($id = null, $itemElement = null) {
    if ($id == null && $this->xml == null) {
      $this->error('XML resource is empty!');
    } else {
      if ($id == null) {
        return $this->xml;
      } else {
        if (is_numeric($id) && $itemElement == null) {
          foreach ($this->xml->item as $item) {
            if ((string) $item['id'] == $id) {
              return $item;
            }
          }
        } else {
          if (is_numeric($id) && $itemElement != null) {
            foreach ($this->xml->item as $item) {
              if ((string) $item['id'] == $id) {
                return (string) $item->$itemElement;
              }
            }
          }
        }
      }
    }
    return null;
  }

  public function attr($attr = null) {
    $_attrs = (array) $this->xml;
    if ($attr == null) {
      return $_attrs['@attributes'];
    } else {
      if (isset($_attrs['@attributes'][$attr])) {
        return $_attrs['@attributes'][$attr];
      } else {
        return FALSE;
      }
    }
  }


  public function __get($item) {
    if (isset($this->xml[$item])) {
      return (string) $this->xml[$item];
    } else {
      if (isset($this->xml->$item)) {
        return $this->xml->$item;
      } else {
        return null;
      }
    }
  }


  public function xml($file)
  {
    $path = $this->config['dbPath'] . $file . '.xml';
    if($fileData = file_get_contents($path)){
      $xml = new SimpleXMLElement($fileData);
      if($xml){
        $this->xml = $xml;
        return $xml;
      } else
        return false;
    } else
      return false;
  }


  public function listDocs() {
    $dirRes = dir($this->config['dbPath']);
    while ($dir = $dirRes->read()) {
      if ($dir != '.' and $dir != '..') {
        $this->_listDocs[] = $dir;
      }
    }
    return $this->_listDocs;
  }


  public function listItems($doc) {
    return $this;
  }


  #          C R E A T E   D O C   M E T H O D S         #

  public function createDoc($failName)
  {
    $this->XMLWrapperCreate = new XMLWrapperCreate();
    $this->XMLWrapperCreate->fileName = $failName;
    $this->saveType = 'create';
    return $this;
  }


  /**
   * @param array $items
   * @return $this
   */
  public function addItems(array $items)
  {
    if($this->XMLWrapperCreate==null) $this->error();

    $this->XMLWrapperCreate->addItems($items);
    return $this;
  }


  /**
   * @param array $attrs
   * @return $this
   */
  public function addAttrs(array $attrs)
  {
    if($this->XMLWrapperCreate==null) $this->error();

    $this->XMLWrapperCreate->addAttrs($attrs);
    return $this;
  }

  /**
   * @param $itemAttr
   * @return $this
   */
  public function addItemsAttrs($itemAttr)
  {
    if($this->XMLWrapperCreate==null) $this->error();

    $this->XMLWrapperCreate->addItemsAttrs($itemAttr);
    return $this;
  }

  public $saveType = null;
  public $xml = null;

  /**
   * @return bool
   */
  public function save()
  {
    $save = false;

    if($this->saveType == 'create'){
      # create
      if($this->XMLWrapperCreate==null) $this->error();
      $save = $this->XMLWrapperCreate->save();

    }else if($this->saveType == 'select'){
      # select

    }else if($this->saveType == 'update'){
      # update
      if($this->XMLWrapperUpdate==null) $this->error();
      $save = $this->XMLWrapperUpdate->save();

    }else if($this->saveType == 'insert'){
      # insert
      if($this->XMLWrapperInsert==null) $this->error();
      $save = $this->XMLWrapperInsert->save();

    }else if($this->saveType == 'delete'){
      # delete

    }

    if($save){

      $this->xml = $save['data'];
      $saveFile = $this->config['dbPath'].$save['fileName'].".xml";

      if(file_put_contents($saveFile,$save['data']->asXML())){
        $this->saveType = null;
        return true;
      }else{
        return false;
      }
    } else
      return false;

  }


  #          U P D A T E   D O C   M E T H O D S         #


  public function updateDoc($failName, $selectValue, $selectLocation='attr')
  {
    if($xml = $this->xml($failName))
    {
      $this->XMLWrapperUpdate = new XMLWrapperUpdate();
      $this->XMLWrapperUpdate->xml = $xml;
      $this->XMLWrapperUpdate->fileName = $failName;

      if(is_numeric($selectValue)){
        $this->XMLWrapperUpdate->selectValue = array('id'=>$selectValue);
        $this->XMLWrapperUpdate->selectLocation = $selectLocation;
      }else if(is_array($selectValue)){
        $this->XMLWrapperUpdate->selectValue = $selectValue;
        $this->XMLWrapperUpdate->selectLocation = $selectLocation;
      }
      $this->saveType = 'update';
      return $this;
    }else
      $this->error();
  }

  public $updateRows = 0;
  public function updateItem($item, $value)
  {
    if($this->XMLWrapperUpdate==null) $this->error();
    $result = $this->XMLWrapperUpdate->updateItem($item, $value);

    if($result){
      $this->updateRows += $result;
      return $this;
    }
    $this->error();
  }


  public function updateAttr($item, $value)
  {
    if($this->XMLWrapperUpdate==null) $this->error();
    $result = $this->XMLWrapperUpdate->updateAttr($item, $value);

    if($result){
      $this->updateRows += $result;
      return $this;
    }
    $this->error('Документ Не существует атрибута: '.$item);
  }


  public function updateItemAttr($item, $value)
  {
    if($this->XMLWrapperUpdate==null) $this->error();
    $result = $this->XMLWrapperUpdate->updateItemAttr($item, $value);

    if($result){
      $this->updateRows += $result;
      return $this;
    }
    $this->error('Документ Не существует атрибута: '.$item);
  }



  #          I N S E R T   M E T H O D S         #
  public function insert($failName)
  {
    if($xml = $this->xml($failName))
    {
      $this->XMLWrapperInsert = new XMLWrapperInsert();
      $this->XMLWrapperInsert->xml = $xml;
      $this->XMLWrapperInsert->fileName = $failName;

      $this->saveType = 'insert';
      return $this;
    }else
      $this->error();
  }

  /** @var int $count  */
  public $count = 0;

  public function insertItem($item, $value=null)
  {
    $result = false;
    if(is_string($item)){
      if($this->XMLWrapperInsert==null) $this->error();
      $result = $this->XMLWrapperInsert->item($item, $value);
    } else if(is_array($item)){
      if($this->XMLWrapperInsert==null) $this->error();
      $result = $this->XMLWrapperInsert->item($item,false);
    }
    if($result){
      $this->count += $result;
      return $this;
    }
  }


  public function insertAttr($item, $value=null)
  {
    $result = false;
    if(is_string($item)){
      if($this->XMLWrapperInsert==null) $this->error();
      $result = $this->XMLWrapperInsert->attr($item, $value);
    } else if(is_array($item)){
      if($this->XMLWrapperInsert==null) $this->error();
      $result = $this->XMLWrapperInsert->attr($item,false);
    }

    if($result){
      $this->count += $result;
      return $this;
    }
  }

  #          D E L E T E   M E T H O D S         #





  #          S Y S T E M   M E T H O D S         #


  /**
   * [isDir description]
   * @param  [type]  $path [description]
   * @return boolean       [description]
   */
  public function isDir($path) {
    if (!is_dir($path)) {
      if (!mkdir($path, 0777, TRUE)) {
        return FALSE;
      } else {
        return TRUE;
      }
    } else {
      return TRUE;
    }
  }

  public function error($text = '') {
    die('ERROR: ' . $text);
  }
}



