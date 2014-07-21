<?php


include 'DBxmlDoc.php';
include 'DBxmlItem.php';

class DBxml
{
	public $config = array();

	public $_listDocs = array();
	public $_listItems = array();
	
	public function __construct()
	{


		$this->config();
	}


	public function config(array $c=null)
	{
		if($c==null){
			$path = dirname(__DIR__).'/db-xml/';
			if(!$this->isDir($path)){
				$this->error('Не удалось создать директорию!');
			}
			$this->config['dbPath'] = $path;
		}else if(!empty($c['dbPath'])){
			if(!$this->isDir($c['dbPath'])){
				$this->error('Не удалось создать директорию!');
			}else
				$this->config = $c;
		}else
			$this->error('Параметр конфигурации должен быть массивом!');
	}


  public $_dataXml = null;
  public function doc($doc)
  {
    $xml = $this->xml($doc);
    $this->_dataXml = $xml;



    return $this;
  }

  public function items($id=null)
  {

    if($id==null && $this->_dataXml==null)
    {
      $this->error('XML resource is empty!');
    }
    else if($id==null)
    {
      return $this->_dataXml;
    }
    else if(is_numeric($id))
    {
      foreach ($this->_dataXml->item as $item) {
        if((string) $item['id'] == $id)
          return $item;
        else
          return false;
      }
    }
    return false;
  }

  public function __get($item)
  {
    if(isset($this->_dataXml->$item)){
      return $this->_dataXml->$item;
    }
  }

  public function xml($file)
  {
    $path = $this->config['dbPath'].'/'.$file.'.xml';
    $content = new SimpleXMLElement(file_get_contents($path));
    return $content;
  }


	public function listDocs()
	{
		$dirRes = dir($this->config['dbPath']);
    while($dir = $dirRes->read()){
      if($dir!='.' and $dir!='..'){
        $this->_listDocs[] = $dir;
      }
    }
    return $this->_listDocs;
	}


	public function listItems($doc)
	{
    return $this;
	}


	public function createDoc()
	{

	}


	public function createItem()
	{

	}


	public function getDoc()
	{

	}


	public function getItem()
	{

	}

 

    #          S Y S T E M   M E T H O D S         #

	
	/** 
	 * [isDir description]
	 * @param  [type]  $path [description]
	 * @return boolean       [description]
	 */
	public function isDir($path)
	{
		if(!is_dir($path)){
			if (!mkdir($path, 0777, true))
			    return false;
			else
				return true;
		} else 
			return true;
	}

	public function error($text='')
	{
		die('ERROR: '.$text);
	}
}



