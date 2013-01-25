<?php

class Controller 
{

	public $view;
	public $mode;

	/**
	*	constructor
	*	@param string $mode
	*	@return Object $view
	*/
	function __construct()  
	{
		
		//  find the mode, what is happening at this point. A save, show a blank form,
		//	an edit, or a list of blogs.
		$mode = '';
		if (isset($_POST)) {
			$mode = 'save';
		} else if (isset($_GET)) {
			$mode = 'list';
		} else {
			$mode = 'form';
		}

		try {
			
			switch ($mode){
				
				case 'edit':
				// load blog entry object with id
				$blog = new BlogEntry($_GET['id']);
				$this->view->set($blog->title, $blog->body);	
				$this->view->render();
				break;

				// this case is more like an interface in development
				// load blog entry object with id
				// load the form template
				$this->view = new Templater('form.tpl.php');
				//iterate through post variables and set them as template variables
				foreach ($_POST as $index => $value) {
					$this->view->set($index, $value);
				}
				$this->view->render();
				break;

				case 'list':
				// load blog entry object with id
				// show the list of blogs
				$this->view->render();
				break;

				case 'save':
				$blog = new BlogEntry();
				var_dump($blog);
				break;

				default:
				exit("no mode set");
			
			}
		
		} catch (Exception $e) {
		    echo $e->getMessage();
		}
	}
}

?>
