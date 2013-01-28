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

				case 'save':
				try {
					$blog = new BlogEntry($_POST['title'], $_POST['blogcontent'], $this->database);
					$blog->save();
					$this->doList();
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				break;

				default:
				throw new Exception("no mode set");
			
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
