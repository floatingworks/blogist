<?php

/**
*	Blogentry is a class for each submitted entry to a blog
*	@params String title, String blogContent
*	@return Boolean 
*/
class Blogentry extends Model
{
	public $title;
	public $blogContent;
	public $table = 'blogist';
	public $database;

	/**
	* 	Blogentry constructor
	*	@param String t		//title  
	*	@param String bc 	//blogcontent
	*	@param String db	//database
	*/
	protected function __construct ($t, $bc, $db)
	{
		$this->title = $t;
		$this->blogContent = $bc;
		$this->database = $db;
	}

	protected function getBlogs ()
	{
		return true;
	}

	protected function save ()
	{
		//have to send an array so create array
		$values = Array('title' => $this->title, 'content' => $this->blogContent);
		// save the object to db
		$this->database->getConnection();
		$this->database->insert('blogentry', $values);
	}

	protected function validate ()
	{
		return;
	}
}
	
