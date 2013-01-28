<?php

class Database 
{

	protected static $dbal;
	private $host;
	private $dbname;
	private $user;
	private $pass;

	public function __construct()
	{
		$this->dbal = self::getConfig();
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
	*	@return	Object dbal
	*
	*/
	public function getConnection()
	{
		$this->dbal = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
		//$this->dbal->exec("SET CHARACTER SET utf8");
	}

	public function selectAll($tablename = '')
	{
		$sth = $this->dbal->prepare('SELECT * FROM {$tablename}');
		$sth->execute();
		$result = $sth->fetchAll();
		var_dump($result);
	}

	public function insert($tablename, $value)
	{
		foreach ($value as $field => $v) {
			$ins[] = ':' . $field;
		}
		$ins = implode(',', $ins);
		$fields = implode(',', array_keys($value));
		$sql = "INSERT INTO $tablename ($fields) VALUES ($ins)";

		$sth = $this->dbal->prepare($sql);
		foreach ($value as $f => $v) {
			$sth->bindValue(':' . $f, $v);
		}
		$sth->execute();
	}
}
