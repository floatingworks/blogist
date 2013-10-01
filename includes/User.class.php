<?php

class User extends Model 
{
	private $id;
	private $username;
	private $password;
	private $datecreated;
	private $email;
	private $isLoggedIn;

	public function __construct()
	{
		// constructor does not load user, will need to call loadUser and pass in a username
		parent::__construct();
	}

	/**
	* @param String username the users unique identification 
	* @param String password the users password
	* @return Boolean is this a valid user?
	*/
	public function authenticate($username = '', $password = '')
	{
		$this->dbal->getConnection();
		$results = $this->dbal->select('user', 'username', $username);
		// the salt is currently an md5 of the users name.  I might make this the datecreated field.
		// just to throw the rainbow tables off a little.
		if (($results['password'] === md5($results['username'].$password)) && ($results['username'] === $username)) {
			$this->isLoggedIn = true;
			return true;
		} else {
			return false;
		}
	}

	/**
	* A method to load an existing user by username
	* @param username string the username
	*/
	public function loadUser($username)
	{
		$this->dbal->getConnection();
		$results = $this->dbal->select('user', 'username', $username);
		// as long as the key value pairs match the db fields we can iterate over the results and set instance variables this way
		// allowing for future changes to the db automatically like an ORM
		foreach ($results as $key => $value) {
			//echo "key : {$key}";
			//echo " - value : {$value}";
			//cho "<br />";
			$this->$key = $value;
		}
	}

	/**
	* A method to register a user on the system
	* @params String username The chosen username
	* @params String password1 The password to be used
	* @params String password2 The password as a duplicate check
	* @return Boolean Has the user been added
	* @TODO check that passwords match
    * @TODO check that username is unique
	* @TODO insert values into user table
	*/
	public function registerUser($username, $password1, $password2, $email)
	{
		$isAlreadyUsername = User::doesUserNameAlreadyExist($username, $this->dbal);
		$passwordthesame = ($password1 == $password2);
		if (!$isAlreadyUsername && $passwordthesame) {
			// registration process requirements satisfied. Username unique and passwords match
			$this->dbal->getConnection();
			// create fields for insert statement
			$value = Array('username' => $username, 'password' => md5($username.$password1), 'email' => $email);
			$result = $this->dbal->insert('user', $value);
		} else {
		  	echo "registration process failed";
		}
	}

	/**
	* Check if provided username already exists on the system
	* @params String username the username to check
	* @return Boolean Whether the username is on the system already
	*/
	public static function doesUsernameAlreadyExist($username, &$dbal)
	{
		$dbal->getConnection();
	    $result = $dbal->select('user', 'username', $username);
		// select will return an array if the user exists, but false if no record is found.
		return $result;
	}

	/**
	* @return String username
	*/
	public function getUsername()
	{
		return $this->username;
	}
	
	/**
	* @return Int userid
	*/
	public function getUserId()
	{
		return $this->id;
	}

	/**
	* user log in check
	*/
	public function getIsLoggedIn()
	{
		return $this->isLoggedIn;
	}
	
	/**
	* set authentication
	* @param Boolean state the logged in state
	*/
	public function setIsLoggedIn($state)
	{
		$this->isLoggedIn = $state;
	}
}
