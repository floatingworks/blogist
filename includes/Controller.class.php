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
		if (isset($_POST['submit'])) {
			$mode = 'save';
		} else if (isset($_GET['id'])) {
			$mode = 'edit';
		} else if (isset($_GET['add'])){
			$mode = 'form';
		} else {
			$mode = 'list';
		}

		$mode = 'list';
		
		try {
			
			switch ($mode){
				
				case 'edit':
				// load blog entry object with id
				$blog = new BlogEntry($_GET['id']);
				$this->view->set($blog->title, $blog->body);	
				$this->view->render();
				break;

				case 'form':
				// this case is more like an interface in development
				// load the form template
				$this->view = new Templater('form.tpl.php');
				//iterate through post variables and set them as template variables
				foreach ($_POST as $index => $value) {
					$this->view->set($index, $value);
				}
				$this->view->render();
				break;

				case 'list':
				$this->view = new Templater('list.tpl.php');
				// show the list of blogs
				try {
					$database = new Database();
					$connection = $database->getConnection();
				} catch (Exception $e) {
					echo $e->getMessage();
				}

				$this->view->render();
				break;

				case 'save':
				$blog = new BlogEntry();
				break;

				default:
				throw new Exception("no mode set");
			
			}
		
		} catch (Exception $e) {
		    echo $e->getMessage();
		}
	}
}

?>
