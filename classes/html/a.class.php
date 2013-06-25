<?php

	require_once('htmlelement.class.php');

	class a extends HTMLElement{

		protected $content;

		public function __construct($content,$args){
			$this->content = $content;
			parent::__construct($args);
		}

		public function output_html(){
			$this->html_string = "<a ";
			$this->html_string .= parent::add_arguments();
			$this->html_string .= ">";
			$this->html_string .= $this->content;
			$this->html_string .= "</a>";
			return $this->html_string;
		}

	}

?>