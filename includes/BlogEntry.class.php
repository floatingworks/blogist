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
	public $timeposted;
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
		$this->timeposted = time();
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
		$this->timeposted = $result['timeposted'];
		$this->isDeleted = $result['isdeleted'];
	}


	public function save ()
	{
		try {
			// have to send an array of key => values to the database method
			$values = Array('title' => $this->title, 'content' => $this->blogContent, 'isDeleted' => $this->isDeleted);
			// check if this entry has an existing id.  If not then it is a new entry, so we want the db to auto increment.
			// else set the id and then add the two arrays together.
			is_null($this->id) ? $idKeyVal = Array() : $idKeyVal = Array('id' => $this->id);
			$values = $idKeyVal + $values;
			// save the object to db
			$this->dbal->getConnection();
			$duplicateUpdate = " ON DUPLICATE KEY UPDATE title = '$this->title', content = '$this->blogContent', isDeleted = '$this->isDeleted', timeposted ='$this->timeposted'";
			$this->dbal->insert('blogentry', $values, $duplicateUpdate);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function validate ()
	{
		return;
	}
}
	
