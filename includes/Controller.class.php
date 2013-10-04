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
		parent::__construct();
		$this->user = new User();
		//$mode = isset($_GET['mode']) ? filter_input($_GET['mode']) : '';
		$mode = isset($_GET['mode']) ? $_GET['mode'] : 'list';

		// login handler
		if (!empty($_POST['username']) && !empty($_POST['password'])) {
			// if this is a valid username password combo start the session and set session variables
			if ($this->user->authenticate($_POST['username'], $_POST['password'])) {
				$this->user->loadUser($_POST['username']);
				$this->user->setIsLoggedIn(true);
				$_SESSION['id'] = $this->user->getUsername();
				unset($_POST['username']);
				unset($_POST['password']);
			}
		} else {
        }
		
		// logout handler
		if ($mode === 'logout') {
			session_destroy();
			session_unset();
			unset($_SESSION['id']);
			$mode = 'login';
		}
		
		// load the current user if this is a session
		if (isset($_SESSION['id'])) {
			$this->user->loadUser($_SESSION['id']);
			$this->user->setIsLoggedIn(true);
		}


		// blog save handler
		if (isset($_POST['submit'])) {
			try {
				isset($_POST['id']) ? $id = $_POST['id'] : $id = NULL;
				isset($_POST['title']) ? $title = $_POST['title'] : $title = NULL;
				isset($_POST['blogcontent']) ? $blogcontent = $_POST['blogcontent'] : $blogcontent = NULL;
				$blog = new BlogEntry($id, $title, $blogcontent);
				$userid = $this->user->getUserId();
				$blogid = $blog->save($userid);
				// we need to add the posted by entry for this saved blog
				// $postedby = new PostedBy();
				// delete the post variable so that we don't multiply add more records
				unset($_POST['submit']);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			$mode = 'list';
		}

		// check for new user registration
		if (isset($_POST['passwordsubmit'])) {
			$this->registrationHandler($_POST['username'], $_POST['password1'], $_POST['password2'], $_POST['email']);
		}

		// load header template
		$this->view = new Templater('header.tpl.php');
		$this->view->render();

		try {
			
			// load the appropriate template for the mode
			$this->view = new Templater($mode . '.tpl.php');
			
			if ($this->authTest()) {
			
                // this is a template / view controller, stop trying to put model logic in here
                switch ($mode){

                    /** check for a login session
                    *	if no session, show the login form
                    **/
                    case 'login':
                    break;
                    
                    /** edit an entry */
                    case 'edit':
                    //	if ($this->authTest()) {
                            $blog = new BlogEntry();
                            $blog->loadBlogById($_GET['id']);
                            $value = Array('title' => $blog->title, 'blogcontent' => $blog->blogContent, 'id' => $blog->id, 'timeposted' => $blog->timeposted);
                            foreach ($value as $index => $val) {
                                $this->view->set($index, $val);
                            }
                    //	}
                    break;

                    /** show the form */
                    case 'form':
                    //	if ($this->authTest()) {
                            foreach ($_POST as $index => $value) {
                                $this->view->set($index, $value);
                            }
                    //	}
                    break;
                    
                    /** list all users blogs */
                    case 'list':
                    //	if ($this->authTest()) {
                            $this->dbal->getConnection();
                            $results = $this->dbal->selectAllUsersBlogs($this->user->getUserId());
                            $this->view->set('array', $results);
                    //	}
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
	
	/**
	* @TODO check that passwords match
	* @TODO check that username is unique
	* @TODO insert values into user table
	*/
	public function registrationHandler($username, $password1, $password2, $email)
	{
		$user = new User();
		$user->registerUser($username, $password1, $password2, $email);
		//$this->dbal->getConnection();
		//$result = $this->dbal->select('user', 'username', $username);
		//var_dump($result);		
		//return $valid_user;
	}
}
