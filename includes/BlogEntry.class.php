<?php

/**
*	Blogentry is a class for each submitted entry to a blog
*	@params String title, String blogContent
*	@return Boolean 
*/
class Blogentry extends Model
{
	public $id;
	public $title;
	public $blogContent;
	public $isDeleted;
	public $table = 'blogist';

	/**
	* 	Blogentry constructor
	*	@param String t		//title  
	*	@param String bc 	//blogcontent
	*	@param String db	//database
	*/
	public function __construct ($id = NULL, $t = '', $bc = '')
	{
		parent::__construct();
		$this->id = $id;
		$this->title = $t;
		$this->blogContent = $bc;
		$this->isDeleted = 0;
	}

	public function getBlogs ()
	{
		return true;
	}

	public function loadBlogById($id)
	{
		$this->dbal->getConnection();
		$result = $this->dbal->select('blogentry', 'id', $id);
		$this->id = $result['id'];
		$this->title = $result['title'];
		$this->blogContent = $result['content'];
	}


	public function save ()
	{
		// have to send an array so create array
		$values = Array('title' => $this->title, 'content' => $this->blogContent, 'isDeleted' => $this->isDeleted);
		// check if the object is new and has no id, if so dont add a Duplicate Key Update
		is_null($this->id) ? $idKeyVal = Array() : $idKeyVal = Array('id' => $this->id);
		$values = $idKeyVal + $values;
		// save the object to db
		$this->dbal->getConnection();
		$duplicateUpdate = " ON DUPLICATE KEY UPDATE title = '$this->title', content = '$this->blogContent', isDeleted = '$this->isDeleted'";
		$this->dbal->insert('blogentry', $values, $duplicateUpdate);
	}

	public function validate ()
	{
		return;
	}
}
	
