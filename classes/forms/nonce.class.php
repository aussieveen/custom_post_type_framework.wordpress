<?php

	require_once('input.class.php');
	require_once(plugin_dir_path( __FILE__ ).'../noncecontrol.class.php');

	class Nonce extends Input{

		private $nonce;
		private $unique_value;
		private $noncecontrol;

		public function __construct($name,$args){
			if($args){
				foreach ($args as $key => $value) {
					switch ($key) {
						case 'unique_value':
							$this->$key = $value;
							break;
					}
					unset($args[$key]);
				}
			}
			$this->noncecontrol = NonceControl::Instance();
			$this->nonce = $this->noncecontrol->create_nonce($this->unique_value);
			parent::__construct($name,$args);
		}

		public function generate_html(){
			$this->html_string .= '<input type = "hidden" name = "'.$this->name.'" value = "'.$this->nonce.'"';
			parent::add_arguments_to_html_string();
			$this->html_string .= '>';
		}

		public function get_nonce(){
			return $this->nonce;
		}
	}
?>