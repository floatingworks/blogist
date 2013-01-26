<?php

class Database 
{

	private static $dbal;
	private $host;
	private $dbname;
	private $user;
	private $pass;

	public function __construct()
	{
		self::getConfig();
	}

	/**
	* 	config files
	*/
	private function getConfig()
	{
		$this->host = DB_HOST; 
		$this->dbname = DB_NAME;
		$this->user = DB_USERNAME;
		$this->pass = DB_PASSWORD;
	}

	/**
	* 	lazy loaded getConnection method
	*	@return	Object dbal
	*
	*/
	public function getConnection()
	{
		$this->dbal = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
	}
}
