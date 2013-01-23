<?php

class Controller {

	public $view;

	/**
	*	constructor
	*	@param string $mode
	*	@return Object $view
	*/
	function __construct($mode='form')  
	{
		try {
		    $this->view = new Templater($mode.'.tpl.php');
			// we need to check whats happening at this point. Is it a submit or a display?
			switch ($mode){
				case 'form':
				//iterate through post variables and set them as template variables
				foreach ($_POST as $index => $value){
					$this->view->set($index, $value);
				}
				break;
			}
			$this->view->render();

		} catch (Exception $e) {
		    echo $e->getMessage();
		}
	}
}
