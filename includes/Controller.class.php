<?php

class Controller extends Model
{

	public $view;
	public $mode;
	public $database;

	/**
	*	constructor
	*	@param string $mode
	*	@return Object $view
	*/
	function __construct()  
	{
		// get the database connection
		$this->getDB();
		
		// check to see if this is a save , if so do the save then unset the submit variable.
		if (isset($_POST['submit'])) {
			try {
				$blog = new BlogEntry($_POST['title'], $_POST['blogcontent'], $this->database);
				$blog->save();
				$this->doList();
				unset($_POST['submit']);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} 
		
		//  find the mode, what is happening at this point. A save, show a blank form,
		//	an edit, or a list of blogs.
		$mode = isset($_GET['mode']) ? $_GET['mode'] : 'form';

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
				$this->doList();
				break;

				default:
				throw new Exception("invalid mode");
			
			}
		
		} catch (Exception $e) {
		    echo $e->getMessage();
		}
	}

	public function getDb()
	{
		try {
			$this->database = new Database();
			$this->database->getConnection();
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function doList()
	{
		$this->view = new Templater('list.tpl.php');
        // show the list of blogs
		$this->database->selectAll('blogentry');
	    $this->view->render();
	}
}

?>
