<?php

	require_once('input.class.php');
	
	class Checkbox extends input{

		public function __construct($name,$args){
			parent::__construct($name,$args);
		}

		public function generate_html(){
			$this->html_string = "<p>";
			if($this->title){
				$this->html_string .= $this->title.": <br><br>";
			}

			foreach ($this->options as $option => $value) {
				$this->html_string .= '<input type = "checkbox" name = "'.$this->name.'['.$value.']"';
				$result = parent::get_saved_value();
				if(is_array($result)){
					if(in_array($value, $result) || $result[$value]){
						$this->html_string .= ' checked="checked"';
					}
				}else{
					if($result == $value){
						$this->html_string .= ' checked="checked"';
					}
				}
				$this->html_string .= ' value = "true"';

				parent::add_arguments_to_html_string();
				
				$this->html_string .= '>'.$option.'<br>';
			}

			$this->html_string .= $this->description ? '<br><em>'.$this->description.'</em></p>' : '</p>';
		}

	}
?>