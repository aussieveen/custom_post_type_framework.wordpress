<?php

	require_once('htmlelement.class.php');

	class p extends HTMLElement{

		protected $content;

		public function __construct($content,$args = null){
			$this->content = $content;
			parent::__construct($args);
		}

		public function output_html(){
			$this->html_string = "<p";
			$this->html_string .= parent::add_arguments();
			$this->html_string .= ">";
			$this->html_string .= $this->filter_content($this->content);
			$this->html_string .= "</p>";
			return $this->html_string;
		}

	}

?>