<?php

	require_once('input.class.php');
	
	class TextField extends input{

		public function __construct($name,$args){
			parent::__construct($name,$args);
		}

		public function generate_html(){
			$this->html_string = "<p>";
			if($this->title){
				$this->html_string .= $this->title.": ";
			}
			$this->html_string .= '<input type = "text" name = "'.$this->name.'" value = "'.parent::get_saved_value().'"';

			parent::add_arguments_to_html_string();

			$this->html_string .= $this->description ? "></br><em>".$this->description."</em></p>" : "></p>";
		}

	}
?>