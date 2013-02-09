<?php

class Controller extends Model
{

	public $view;

	/**
	*	constructor
	*	@param string $mode
	*	@return Object $view
	*/
	function __construct()  
	{
		// first call the parent constructor to get the database 
		parent::__construct();

		// is this a login request.
		if (!empty($_POST['username']) && !empty($_POST['password'])) {
			$user =  new User();
			// if this is a valid username password combo start the session and set session variables
			if ($user->authenticate($_POST['username'], $_POST['password'])) {
				echo "logging in...";
				$_SESSION['id'] = $user->getUsername();
				unset($_POST['username']);
				unset($_POST['password']);
			}
		}
		
		$mode = isset($_GET['mode']) ? $_GET['mode'] : 'list';

		echo $mode;

		if (!isset($_SESSION['id'])) {
			$mode='login';
		}
		
		// check to see if this is a save , if so do the save then unset the submit variable.
		// is this a save after an edit, in which case there should be an id in $_GET['id']
		if (isset($_POST['submit'])) {
			try {
				isset($_POST['id']) ? $id = $_POST['id'] : $id = NULL;
				isset($_POST['title']) ? $title = $_POST['title'] : $title = NULL;
				isset($_POST['blogcontent']) ? $blogcontent = $_POST['blogcontent'] : $blogcontent = NULL;
				$blog = new BlogEntry($id, $title, $blogcontent);
				$blog->save();
				// delete the post variable so that we don't multiply add more records
				unset($_POST['submit']);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			$mode = 'list';
		}

		// load header template
		$this->view = new Templater('header.tpl.php');
		$this->view->render();

		try {
			
			// load the appropriate template for the mode
			$this->view = new Templater($mode . '.tpl.php');
			
			// this is a template / view controller, stop trying to put model logic in here
			switch ($mode){

				/** check for a login session
				*	if no session, show the login form
				**/
				case 'login':
				break;
				
				/** check for a login session
				*	if no session, show the login form
				**/
				case 'logout':
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
