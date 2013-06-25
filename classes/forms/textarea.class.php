<?php

	require_once('input.class.php');
	
	class Textarea extends input{

		public function __construct($name,$args){
			parent::__construct($name,$args);
		}

		public function generate_html(){
			$this->html_string = "<p>";
			if($this->title){
				$this->html_string .= $this->title.": </br>";
			}
			$default = $this->default_value ? $this->default_value : "";
			$this->html_string .= '<textarea name = "'.$this->name.'"';

			parent::add_arguments_to_html_string();

			$this->html_string .= '>'.parent::get_saved_value().'</textarea><br>';

			$this->html_string .= $this->description ? "<br><em>".$this->description."</em></p>" : "</p>";			
		}

	}
?>