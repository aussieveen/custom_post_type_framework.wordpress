<?php

	require_once('htmlelement.class.php');

	class ul extends HTMLElement{

		private $content;

		public function __construct($content,$args){
			$this->content = $content;
			parent::__construct($args);
		}

		public function output_html(){
			$this->html_string = "<ul";
			$this->html_string .= parent::add_arguments();
			$this->html_string .= ">";
			$this->html_string .= $this->content;
			$this->html_string .= "</ul>";
			return $this->html_string;
		}

	}

?>