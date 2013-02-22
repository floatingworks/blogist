<?php

// autoloader
function __autoload($classname) {
	$filename = "./includes/" . $classname . ".class.php";
	include_once($filename);
}

// config file
require_once('/home/blogist/config.php'); // this config is outside the project directory owned by root, with read only permissions

