<?php

class Database
{

	private static $dbal;
	private $host;
	private $dbname;
	private $user;
	private $pass;

	private __construct()
	{
		self::getConfig();
	}

	/**
	* 	config files
	*/
	private function getConfig()
	{
		$this->host = '';
		$this->dbname = '';
		$this->user = '';
		$this->pass = '';
	}

	/**
	* 	lazy loaded getConnection method
	*	@return	Object dbal
	*
	*/
	private function getConnection()
	{
		$this->dbal = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
	}
}
