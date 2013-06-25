<?php

	require_once('htmlelement.class.php');

	class img extends HTMLElement{

		public function __construct($args){
			parent::__construct($args);
		}

		public function output_html(){
			$this->html_string = "<img ";
			$this->html_string .= parent::add_arguments();
			$this->html_string .= ">";
			return $this->html_string;
		}

	}

?>