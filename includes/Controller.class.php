<?php

class Controller extends Model
{

	public $view;
	public $user;

	/**
	*	constructor
	*/
	function __construct()  
	{
		// first call the parent constructor to get the database 
		parent::__construct();

		// instantiate a new user
		$this->user = new User();
		
		$mode = isset($_GET['mode']) ? $_GET['mode'] : 'login';

		// login handler
		if (!empty($_POST['username']) && !empty($_POST['password'])) {
			// if this is a valid username password combo start the session and set session variables
			if ($this->user->authenticate($_POST['username'], $_POST['password'])) {
				$this->user->loadUser($_POST['username']);
				$this->user->setIsLoggedIn(true);
				$_SESSION['id'] = $this->user->getUsername();
				unset($_POST['username']);
				unset($_POST['password']);
				$mode = 'login';
			}
		}
		
		// logout handler
		if ($mode === 'logout') {
			session_destroy();
			session_unset();
			unset($_SESSION['id']);
			$mode = 'login';
		}

		// blog save handler
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

		// check for new user registration
		if (isset($_POST['passwordsubmit'])) {
			$this->registrationHandler();
		}

		// load the current user if this is a session
		if (isset($_SESSION['id'])) {
			$this->user->loadUser($_SESSION['id']);
			$this->user->setIsLoggedIn(true);
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
				
				/** edit an entry */
				case 'edit':
					if ($this->authTest()) {
						$blog = new BlogEntry();
						$blog->loadBlogById($_GET['id']);
						$value = Array('title' => $blog->title, 'blogcontent' => $blog->blogContent, 'id' => $blog->id, 'timeposted' => $blog->timeposted);
						foreach ($value as $index => $val) {
							$this->view->set($index, $val);
						}
					}
				break;

				/** show the form */
				case 'form':
					if ($this->authTest()) {
						foreach ($_POST as $index => $value) {
							$this->view->set($index, $value);
						}
					}
				break;
				
				/** list all items */
				case 'list':
					if ($this->authTest()) {
						$this->dbal->getConnection();
						$results = $this->dbal->selectAll('blogentry');
						$this->view->set('array', $results);
					}
				break;

				/** show an individual item */
				case 'show':
				break;

				/** registration form */
				case 'register':
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

	/**
	* Check whether there is a current logged in user
	*/

	public function authTest()
	{
		return $this->user->getIsLoggedIn();
	}

}
?>
