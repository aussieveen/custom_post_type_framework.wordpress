<?php

	abstract class HTMLElement{

		protected $args;

		public function __construct($args = null){
			$this->args = $args;
		}

		abstract function output_html();

		protected function add_arguments(){
			if($this->args){
				foreach($this->args as $key => $value){
					$string .= ' '.$key.'= "'.$value.'"';
				}
				return $string;
			}
			return "";
		}

		protected function filter_content($content){
			if(is_object($content)){
				return $content->output_html();
			}
			if(is_string($content)){
				return $content;
			}
			if(is_array($content)){
				foreach($content as $item){
					$this->filter_content($item);
				}
			}
		}
	}

?>