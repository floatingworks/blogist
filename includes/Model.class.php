<?php

class Model {

	protected $dbal;

	protected function __construct ()
	{
		$this->dbal = new Database();
		$this->dbal->getConnection();
		exit(var_dump($this->dbal));
	}

	protected function getDbal()
	{
		return $this->dbal;
	}
}

