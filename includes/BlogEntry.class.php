<?php

/**
*	Blogentry is a class for each submitted entry to a blog
*	@params String title, String blogContent
*	@return Boolean 
*/
class Blogentry 
{
	public $title;
	public $blogContent;

	/**
	* Blogentry constructor
	*/
	function Blogentry ()
	{
		return true;
	}

	function getBlogs ()
	{
		return true;
	}

	function save ()
	{
		// save the object to db
		return true;
	}
}
	
