<?php

include_once 'XMLWrapperSelect.php';
include_once 'XMLWrapperCreate.php';
include_once 'XMLWrapperUpdate.php';
include_once 'XMLWrapperInsert.php';
include_once 'XMLWrapperDelete.php';

class XMLWrapper
{
    public $debug = true;
    public $path = null;
    public $pathFile = null;

    public $docAttr = null;
    public $docTree = null;

    /** @var object */
    public $XMLWrapperSelect = null;
    /** @var object */
    public $XMLWrapperCreate = null;
    /** @var object */
    public $XMLWrapperUpdate = null;
    /** @var object */
    public $XMLWrapperInsert = null;
    /** @var object */
    public $XMLWrapperDelete = null;

    public $saveType = null;

    public function __construct($path = null)
    {
        if($path==null)
            $path = dirname(__DIR__).'/xmlDB/';
        $this->path = $path;
    }

    public function refresh()
    {
        $this->XMLWrapperSelect = null;
        $this->pathFile = null;
        $this->docAttr = null;
    }

    /*   *   *   *   *   *   *   *   *   *   *   *   *   *   *
    select
    */
    public function select($fileName)
    {
        $this->refresh();
        $this->currentFileName = $fileName;

        $this->pathFile = $this->getFile($fileName);
        $this->XMLWrapperSelect = new XMLWrapperSelect();
        $this->XMLWrapperSelect->init($this->xml());
        $this->docAttr = $this->XMLWrapperSelect->docAttr;
        return $this;
    }

    public function items($id = null, $attr = null)
    {
        $this->XMLWrapperSelect->items($id, $attr);
        return $this;
    }

    public function object()
    {
        $this->XMLWrapperSelect->xml;
        return $this;
    }

    public function where($rule)
    {
        $this->XMLWrapperSelect->where($rule);
        return $this;
    }

    public function whereAnd($rule)
    {
        $r = $this->XMLWrapperSelect->whereAnd($rule);
        if($r===false)
            $this->error('Method whereOr(...) must set after where(...)');
        return $this;
    }

    public function whereOr($rule)
    {
        $r = $this->XMLWrapperSelect->whereOr($rule);
        if($r===false)
            $this->error('Method whereOr(...) must set after where(...)');
        return $this;
    }

    public function sort($attr = 'id', $asc = 'ASC', $num = true)
    {
        $this->XMLWrapperSelect->sort($attr, $asc, $num );
        return $this;
    }

    public function result()
    {
        $result = $this->XMLWrapperSelect->result();
        return $result;
    }


    /*   *   *   *   *   *   *   *   *   *   *   *   *   *   *
    create
    */

    public function create($fileName)
    {
        $this->currentFileName = $fileName;

        $this->XMLWrapperCreate = new XMLWrapperCreate();
        $this->XMLWrapperCreate->fileName = $fileName;
        $this->saveType = 'create';
        return $this;
    }


    /**
     * @param array $items
     * @return $this
     */
    public function createItems(array $items)
    {
        if ($this->XMLWrapperCreate == null) $this->error();

        $this->XMLWrapperCreate->items($items);
        return $this;
    }


    /**
     * @param array $attrs
     * @return $this
     */
    public function createAttrs(array $attrs)
    {
        if ($this->XMLWrapperCreate == null) $this->error();

        $this->XMLWrapperCreate->attrs($attrs);
        return $this;
    }

    /**
     * @param $itemAttr
     * @return $this
     */
    public function createItemsAttrs($itemAttr)
    {
        if ($this->XMLWrapperCreate == null) $this->error();

        $this->XMLWrapperCreate->itemsAttrs($itemAttr);
        return $this;
    }


    /*   *   *   *   *   *   *   *   *   *   *   *   *   *   *
    update
    */
    public $count = 0;

    public function update($fileName, $selectValue, $selectLocation = 'attr')
    {

        $this->currentFileName = $fileName;

        if ($xml = $this->xml($fileName)) {
            $this->XMLWrapperUpdate = new XMLWrapperUpdate();
            $this->XMLWrapperUpdate->xml = $xml;
            $this->XMLWrapperUpdate->fileName = $fileName;

            if (is_numeric($selectValue)) {
                $this->XMLWrapperUpdate->selectValue = array('id' => $selectValue);
                $this->XMLWrapperUpdate->selectLocation = $selectLocation;
            } else if (is_array($selectValue)) {
                $this->XMLWrapperUpdate->selectValue = $selectValue;
                $this->XMLWrapperUpdate->selectLocation = $selectLocation;
            }
            $this->saveType = 'update';
            return $this;
        } else
            $this->error();
    }


    public function updateItem($item, $value)
    {
        if ($this->XMLWrapperUpdate == null) $this->error();
        $result = $this->XMLWrapperUpdate->item($item, $value);

        if ($result) {
            $this->count += $result;
            return $this;
        }
        $this->error();
    }


    public function updateAttr($item, $value)
    {
        if ($this->XMLWrapperUpdate == null) $this->error();
        $result = $this->XMLWrapperUpdate->attr($item, $value);

        if ($result) {
            $this->count += $result;
            return $this;
        }
        $this->error('У документа не существует атрибута: ' . $item);
    }


    public function updateItemAttr($item, $value)
    {
        if ($this->XMLWrapperUpdate == null) $this->error();
        $result = $this->XMLWrapperUpdate->itemAttr($item, $value);

        if ($result) {
            $this->count += $result;
            return $this;
        }
        $this->error('У елемента item не существует атрибута: ' . $item);
    }


    /*   *   *   *   *   *   *   *   *   *   *   *   *   *   *
    insert
    */
    public $currentFileName = null;

    public function insert($fileName)
    {
        $this->currentFileName = $fileName;
        if ($xml = $this->xml($fileName)) {
            $this->XMLWrapperInsert = new XMLWrapperInsert();
            $this->XMLWrapperInsert->xml = $xml;
            $this->XMLWrapperInsert->fileName = $fileName;
            $this->XMLWrapperInsert->structureItem = $this->structure('item');
            $this->XMLWrapperInsert->structureAttr = $this->structure('attr');

            $this->saveType = 'insert';
            return $this;
        } else
            $this->error();
    }


    public function insertItem($item, $value = null)
    {
        $result = false;
        if (is_string($item)) {
            if ($this->XMLWrapperInsert == null)
                $this->error();

            $result = $this->XMLWrapperInsert->item($item, $value);

        } else if (is_array($item)) {
            if ($this->XMLWrapperInsert == null)
                $this->error();

            $result = $this->XMLWrapperInsert->item($item, false);
        }
        if ($result) {
            $this->count += $result;
            return $this;
        }
    }


    public function insertAttr($item, $value = null)
    {
        $result = false;
        if (is_string($item)) {
            if ($this->XMLWrapperInsert == null) $this->error();
            $result = $this->XMLWrapperInsert->attr($item, $value);
        } else if (is_array($item)) {
            if ($this->XMLWrapperInsert == null) $this->error();
            $result = $this->XMLWrapperInsert->attr($item, false);
        }

        if ($result) {
            $this->count += $result;
            return $this;
        }
    }


    /*   *   *   *   *   *   *   *   *   *   *   *   *   *   *
    delete
    */
    public function delete($fileName)
    {
        if(is_file($this->getFile($fileName))){

            $this->XMLWrapperDelete = new XMLWrapperDelete();
            $this->XMLWrapperDelete->fileName = $fileName;
            $this->XMLWrapperDelete->path = $this->path;

            $this->saveType = 'delete';
            return $this;
        } else
            $this->error("Файла $fileName не существует!");
    }

    public function deleteDoc($makeDump=true)
    {
        $this->XMLWrapperDelete->doc($makeDump);
    }

    public function deleteItem($item)
    {
        $this->XMLWrapperDelete->item($item);
    }

    /*   *   *   *   *   *   *   *   *   *   *   *   *   *   *
    common
    */



    public function xml($nameFile=null)
    {
        if($nameFile==null)
            $file_get = $this->pathFile;
        else
            $file_get = $this->path.$nameFile.'.xml';

        if ($fileData = file_get_contents($file_get)) {
            return new SimpleXMLElement($fileData);
        } else {
            return $this->error("Невозможно создать XML объект с файла: " . $this->pathFile);
        }
    }

    public function save()
    {
        $save = false;

        if ($this->saveType == 'create') {
            # create
            if ($this->XMLWrapperCreate == null) $this->error();
            $save = $this->XMLWrapperCreate->save();

        } else if ($this->saveType == 'select') {
            # select



        } else if ($this->saveType == 'update') {
            # update
            if ($this->XMLWrapperUpdate == null) $this->error();
            $save = $this->XMLWrapperUpdate->save();

        } else if ($this->saveType == 'insert') {
            # insert
            if ($this->XMLWrapperInsert == null) $this->error();
            $save = $this->XMLWrapperInsert->save();

        } else if ($this->saveType == 'delete') {
            # delete
            if ($this->XMLWrapperDelete == null) $this->error();
            $this->XMLWrapperDelete->save();
            $this->saveType = null;
        }

        if ($save) {
            $saveFile = $this->path . $save['fileName'] . ".xml";
            if (file_put_contents($saveFile, $save['data']->asXML())) {
                $this->saveType = null;
                return true;
            } else {
                return false;
            }
        } else
            return false;

    }

    public $structureAttr = null;
    public $structureItem = null;
    /**
     * @param string $type  'item' or 'attr'. Default 'item'
     * @return array
     */
    public function structure($type='item')
    {
        if($this->structureItem==null){
            $xml = $this->xml($this->currentFileName);
            foreach((array) $xml->item as $structureKey=>$structureValue)
            {
                if($structureKey != '@attributes')
                    $this->structureItem[] = $structureKey;
                if(is_array($structureValue))
                    $this->structureAttr = array_keys($structureValue);
            }
        }

        if($type=='item')
            return $this->structureItem;
        else if($type=='attr')
            return $this->structureAttr;
    }



    /**
     * @param $fileName
     * @return bool|string
     */
    public function getFile($fileName)
    {
        $path = ($this->path != null) ? $this->path : dirname(__DIR__) . DIRECTORY_SEPARATOR . 'xwdb' . DIRECTORY_SEPARATOR;
        if (is_dir($path)) {
            if (is_file($path . $fileName . '.xml')) {
                return $path . $fileName . '.xml';
            } else {
                return $this->error("Не верный путь или имя файла: " . $path . $fileName . '.xml');
            }
        } else {
            return $this->error("Не верный путь к директории: " . $path);
        }
    }


    public function error($text = '')
    {
        if ($this->debug) {
            die('ERROR: ' . $text);
        } else {
            return false;
        }
    }


}