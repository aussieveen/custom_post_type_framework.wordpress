<?php

	require_once('input.class.php');
	
	class Radio extends input{

		public function __construct($name,$args){
			parent::__construct($name,$args);
		}

		public function generate_html(){
			$this->html_string = '<p>';
			if($this->title){
				$this->html_string .= '<label>'.$this->title.': </label><br>';
			}
			foreach ($this->options as $option => $value) {
				$this->html_string .= '<input type = "radio" name = "'.$this->name.'" value = "'.$value.'"';

				parent::add_arguments_to_html_string();
				
				if(parent::get_saved_value() == $value){
					$this->html_string .= ' checked="'.$value.'"';
				}
				$this->html_string .= '>'.$option.'<br>';
			}

			$this->html_string .= $this->description ? '<br><em>'.$this->description.'</em></p>' : '</p>';			
		}

	}
?>