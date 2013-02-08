<?php

class User extends Model 
{
	private $id;
	private $username;
	private $password;
	private $datecreated;
	private $email;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	* @param String username the users unique identification 
	* @param String password the users password
	* @return Boolean is this a valid user?
	*/
	public function authenticate($username = '', $password = '')
	{
		return true;
	}

	/**
	* return String username
	*/
	public function getUsername()
	{
		return "fred";
		return $this->username;
	}
}
