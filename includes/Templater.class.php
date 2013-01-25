<?php

class Templater
{
	private $template;
	// template directory absolute path.  This may need to be moved to a config file
	private $templateRoot = TEMPLATE_ROOT;
	public $var = Array();

	/**
	* constructor
	*/
	function __construct($template=null)
	{
		$this->load($template);
	}
	
	/**
	*	load template
	*	@params String template
	*/
	public function load($template)
	{
		// add the template directory root
		$template = $this->templateRoot . $template;
		if (!is_file($template)){
			// does the file exist?
			throw new Exception('File not found');
		} elseif (!is_readable($template)){
			// is it readable?
			throw new Exception('Could not access file');
		} else {
			// file loaded
			$this->template = $template;
		}
	}

	public function render()
	{
		// render the template, should really do some more validation here
		require $this->template;
	}

	public function set($key = '', $value = '')
	{
		$this->var[$key] = $value;
	}
}
?>
