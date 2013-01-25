<?php

class Model {

	protected $dbal;

	protected function __construct ()
	{
		$this->dbal = new Database();
	}
}

