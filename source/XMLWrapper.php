<?php


include 'XMLWrapperSelect.php';
include 'XMLWrapperCreate.php';
include 'XMLWrapperInsert.php';
include 'XMLWrapperUpdate.php';
include 'XMLWrapperDelete.php';

class XMLWrapper
{
    public $config = array();
    public $debug = true;

    public $_listDocs = array();
    public $_listItems = array();

    public $structureItem=null;
    public $structureAttr=null;

    public $XMLWrapperSelect = null;
    public $XMLWrapperCreate = null;
    public $XMLWrapperInsert = null;
    public $XMLWrapperUpdate = null;
    public $XMLWrapperDelete = null;

    /** @var int $count */
    public $count = 0;
    public $updateRows = 0;
    public $saveType = null;
    public $xmlItem = null;
    public $xmlAttr = null;
    public $xml = null;


    public function __construct(array $config = null)
    {
        $this->config($config);
    }


    public function config(array $config = null)
    {
        if ($config == null) {
            $path = dirname(__DIR__) . '/db-xml/';
            if (!$this->isDir($path)) {
                $this->error('Не удалось создать директорию!');
            }
            $this->config['dbPath'] = $path;
        } else {
            if (!empty($config['dbPath'])) {
                if (!$this->isDir($config['dbPath'])) {
                    $this->error('Не удалось создать директорию!');
                } else {
                    $this->config = $config;
                }
            } else {
                $this->error('Параметр конфигурации должен быть массивом!');
            }
        }
    }



    #          S E L E C T   D O C   M E T H O D S         #

    public function select($failName)
    {
        if ($xml = $this->xml($failName)) {
            $this->XMLWrapperSelect = new XMLWrapperSelect();
            $this->XMLWrapperSelect->fileName = $failName;
            $this->XMLWrapperSelect->init($xml);
            $this->xmlAttr = $this->XMLWrapperSelect->xmlAttr;
            $this->xmlItem = $this->XMLWrapperSelect->xmlItem;
            $this->saveType = null;
            return $this;
        } else
            $this->error();
    }

    public function item($id = null, $itemElement = null)
    {
        if ($this->xmlItem == null) {
            $this->error('XML resource is empty!');
        } else {
            $result = $this->XMLWrapperSelect->item($id, $itemElement);
            if($result!=null)
                return $result;
            else
                $this->error('XML resource is empty!');
        }
    }

    public function items($id=null)
    {
        if ($this->xmlItem == null)
            $this->error('XML resource is empty!');

        if ($id == null)
            return $this->xmlItem;
        else {
            $result = $this->XMLWrapperSelect->item($id, null);

            if($result!=null)
                return $result;
            else
                $this->error('XML resource is empty!');
        }
    }

    public function sort($attr = 'id', $asc = 'ASC', $num = true)
    {
        return $this->XMLWrapperSelect->sort($attr, $asc, $num);
    }


    public function listDocs()
    {
        $dirRes = dir($this->config['dbPath']);
        while ($file = $dirRes->read()) {
            if ($file != '.' and $file != '..' and !is_dir($file)) {
                $this->_listDocs[] = $file;
            }
        }
        return $this->_listDocs;
    }



    /**
     * @param string $type  'item' or 'attr'. Default 'item'
     * @return array
     */
    public function structure($type='item')
    {
        if($this->structureItem==null){
            foreach((array) $this->xml->item as $structureKey=>$structureValue){
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




    #          C R E A T E   D O C   M E T H O D S         #

    public function create($failName)
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



    #          U P D A T E   D O C   M E T H O D S         #


    public function update($failName, $selectValue, $selectLocation = 'attr')
    {
        if ($xml = $this->xml($failName)) {
            $this->XMLWrapperUpdate = new XMLWrapperUpdate();
            $this->XMLWrapperUpdate->xml = $xml;
            $this->XMLWrapperUpdate->fileName = $failName;

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
            $this->updateRows += $result;
            return $this;
        }
        $this->error();
    }


    public function updateAttr($item, $value)
    {
        if ($this->XMLWrapperUpdate == null) $this->error();
        $result = $this->XMLWrapperUpdate->attr($item, $value);

        if ($result) {
            $this->updateRows += $result;
            return $this;
        }
        $this->error('Документ Не существует атрибута: ' . $item);
    }


    public function updateItemAttr($item, $value)
    {
        if ($this->XMLWrapperUpdate == null) $this->error();
        $result = $this->XMLWrapperUpdate->itemAttr($item, $value);

        if ($result) {
            $this->updateRows += $result;
            return $this;
        }
        $this->error('Документ Не существует атрибута: ' . $item);
    }


    #          I N S E R T   M E T H O D S         #
    public function insert($fileName)
    {
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
            if ($this->XMLWrapperInsert == null) $this->error();
            $result = $this->XMLWrapperInsert->item($item, $value);
        } else if (is_array($item)) {
            if ($this->XMLWrapperInsert == null) $this->error();
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



    #          D E L E T E   M E T H O D S         #


    public function delete($fileName, $dump=true)
    {
        if(is_file($this->config['dbPath'].$fileName.'.xml')){
            $this->XMLWrapperDelete = new XMLWrapperDelete();
            $this->XMLWrapperDelete->fileName = $fileName;
            $this->XMLWrapperDelete->path = $this->config['dbPath'];
            $this->XMLWrapperDelete->dump = $dump;

            $this->saveType = 'delete';
            return $this;
        } else
            $this->error("Файла $fileName не существует!");
    }



    #          S Y S T E M   M E T H O D S         #


    public function xml($file)
    {
        $path = $this->config['dbPath'] . $file . '.xml';
        if ($fileData = file_get_contents($path)) {
            $xml = new SimpleXMLElement($fileData);
            if ($xml) {
                //$this->xml = $xml;
                return $xml;
            } else
                return false;
        } else
            return false;
    }


    /**
     * @return bool
     */
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
            $save = $this->XMLWrapperDelete->delete();
        }

        if ($save) {

            $this->xml = $save['data'];
            $saveFile = $this->config['dbPath'] . $save['fileName'] . ".xml";

            if (file_put_contents($saveFile, $save['data']->asXML())) {
                $this->saveType = null;
                return true;
            } else {
                return false;
            }
        } else
            return false;

    }

    /**
     * [isDir description]
     * @param  [type]  $path [description]
     * @return boolean       [description]
     */
    public function isDir($path)
    {
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

    public function enCode($text)
    {
        return htmlentities($text,ENT_QUOTES);
    }


    public function deCode($text)
    {
        return html_entity_decode($text,ENT_QUOTES);
    }


    public function error($text = '')
    {
        if($this->debug)
            die('ERROR: '.$text);
        else
            return false;
    }
}



