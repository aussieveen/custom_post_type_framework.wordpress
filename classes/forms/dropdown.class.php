<?php

	require_once('input.class.php');
	
	class Dropdown extends input{

		public function __construct($name,$args){
			parent::__construct($name,$args);
		}

		public function generate_html(){
			if($this->options){
				$this->html_string = "<p>";
				if($this->title){
					$this->html_string .= $this->title.": ";
				}
				
				$this->html_string .= '<select name = "'.$this->name.'"';
				parent::add_arguments_to_html_string();
				$this->html_string .= ">";
				
				foreach ($this->options as $option => $value) {
					$this->html_string .= '<option';
					if($parent::get_saved_value() == $value){
						$this->html_string .= " selected";
					}
					$this->html_string .= ' value = "'.$value.'">'.$option.'</option>';
				}
				$this->html_string .= "</select>";
				$this->html_string .= $this->description ? "</br><em>".$this->description."</em></p>" : "></p>";
			}else{
				$this->html_string = "<p>No option values for dropdown - ".$this->name."</p>";
			}			
		}

	}
?>