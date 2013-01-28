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
	* Blogentry constructor
	*/
	function __construct ($titl, $blogConten, $db)
	{
		$this->title = ($titl);
		$this->blogContent = ($blogConten);
		$this->database = $db;
	}

	function getBlogs ()
	{
		return true;
	}

	function save ()
	{
		//have to send an array so create array
		$values = Array('title' => $this->title, 'content' => $this->blogContent);
		// save the object to db
		$this->database->insert('blogentry', $values);
	}
}
	
