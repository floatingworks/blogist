<?php

class Database 
{

	public static $dbal;
	private $host;
	private $dbname;
	private $user;
	private $pass;

	public function __construct()
	{
		self::getConfig();
		// self::getConnection();  ** do we want to get the connection in the constructor? Or do we want the caller to initiate the connection as a lazy loader?
	}

	/**
	* 	config files
	*/
	private function getConfig()
	{
		// config settings from config.php outside the webroot
		$this->host = DB_HOST; 
		$this->dbname = DB_NAME;
		$this->user = DB_USERNAME;
		$this->pass = DB_PASSWORD;
	}

	/**
	* 	lazy loaded getConnection method
	*/
	public function getConnection()
	{
		$this->dbal = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
		//$this->dbal->exec("SET CHARACTER SET utf8");
	}

	/**
	* @param tablename the name of the table to use
	* @return result Array of data from the query
	*/
	public function selectAll($tablename)
	{
		$sql = "SELECT * FROM $tablename";
		$sth = $this->dbal->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll();
		return $result;
	}
	
	/**
	* @param String tablename the name of the table to use
	* @param String column the column to search on
	* @param String search the search string
	* @return Array result An array as specified by FETCH_ASSOC
	*/
	public function select($tablename, $column, $search)
	{
		$sql = "SELECT * FROM $tablename WHERE $column = :id";
		$sth = $this->dbal->prepare($sql);
		$sth->bindParam(':id', $search);
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	* @param String tablename the table to search on
	* @param Array value an array of keys and values to insert into the db
	* @param String duplicateKey To determine whether this is an insert or an update
	*/
	public function insert($tablename, $value, $duplicateKey = '')
	{
		foreach ($value as $field => $v) {
			$ins[] = ':' . $field;
		}
		$ins = implode(',', $ins);
		$fields = implode(',', array_keys($value));
		$sql = "INSERT INTO $tablename ($fields) VALUES ($ins) $duplicateKey";
		$sth = $this->dbal->prepare($sql);
		foreach ($value as $f => $v) {
			$sth->bindValue(':' . $f, $v);
		}
		$sth->execute();
	}
}
