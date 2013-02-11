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
			//echo "<br />";
			$this->$key = $value;
		}
	}

	/**
	* return String username
	*/
	public function getUsername()
	{
		return $this->username;
	}

}
