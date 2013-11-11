<?php

// autoloader
function autoloadClasses($classname) {
	$filename = "./includes/" . $classname . ".class.php";
	include_once($filename);
}

spl_autoload_register('autoloadClasses');

// config file
require_once('~/config.php'); // this config is outside the project directory owned by root, with read only permissions

