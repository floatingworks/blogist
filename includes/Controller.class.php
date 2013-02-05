<?php

class Controller extends Model
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
		// first call the parent constructor to get the database 
		parent::__construct();
		
		//  find the mode, what is happening at this point. A save, show a blank form,
		//	an edit, or a list of blogs.
		$this->mode = isset($_GET['mode']) ? $_GET['mode'] : 'list';

		// check to see if this is a save , if so do the save then unset the submit variable.
		// is this a save after an edit, in which case there should be an id in $_GET['id']
		if (isset($_POST['submit'])) {
			try {
				isset($_POST['id']) ? $id = $_POST['id'] : $id = NULL;
				$blog = new BlogEntry($id, $_POST['title'], $_POST['blogcontent']);
				$blog->save();
				// delete the post variable so that we don't multiply add more records
				unset($_POST['submit']);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			$this->mode = 'list';
		} 

		if (!isset($_SESSION['usersession'])) {
			$this->mode = 'login';
		}

		try {
			
			// load the template
			$this->view = new Templater($this->mode . '.tpl.php');

			switch ($this->mode){

				/** check for a login session
				*	if no session, show the login form
				**/
				case 'login':
					$user =  new User();
					$user->authenticate($_POST['username'], $_POST['password']);
				break;

				/** edit an entry */
				case 'edit':
					$blog = new BlogEntry();
					$blog->loadBlogById($_GET['id']);
					$value = Array('title' => $blog->title, 'blogcontent' => $blog->blogContent, 'id' => $blog->id, 'timeposted' => $blog->timeposted);
					foreach ($value as $index => $val) {
						$this->view->set($index, $val);
					}
				break;

				/** show the form */
				case 'form':
					foreach ($_POST as $index => $value) {
						$this->view->set($index, $value);
					}
				break;
				
				/** list all items */
				case 'list':
					$this->dbal->getConnection();
					$results = $this->dbal->selectAll('blogentry');
					$this->view->set('array', $results);
				break;

				/** show an individual item */
				case 'show':
				break;

				/** default throws an exception for invalid mode */
				default:
				throw new Exception("invalid mode");
			
			}

			// render the template
			$this->view->render();
		
		} catch (Exception $e) {
		    echo $e->getMessage();
		}
	}
}
?>
