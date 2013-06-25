<?php

	require_once('htmlelement.class.php');

	class h extends HTMLElement{

		protected $content;
		protected $scale;

		public function __construct($content,$scale,$args){
			$this->content = $content;
			$this->scale = $scale;
			parent::__construct($args);
		}

		public function output_html(){
			$this->html_string = "<h".$this->scale;
			$this->html_string .= parent::add_arguments();
			$this->html_string .= ">";
			$this->html_string .= $this->content;
			$this->html_string .= "</h".$this->scale.">";
			return $this->html_string;
		}

	}

?>