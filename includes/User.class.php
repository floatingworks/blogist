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

	public function authenticate($username, $password)
	{
		return true;
	}
		
}
