<?php
	abstract class input{

		protected $html_string;
		protected $title;
		protected $description;
		
		protected $name;
		protected $options;
		protected $default_value;
		
		protected $args;

		protected $value_db_location;
		protected $context;

		public function __construct($name,$args = null){
			if($args){
				foreach ($args as $key => $value) {
					switch ($key) {
						case 'title':
						case 'description':
						case 'default_value':
						case 'options':
						case 'form':
							$this->$key = $value;
							unset($args[$key]);
							break;
					}
				}
			}
			$this->args = $args ? $args : NULL;
			$this->name = $name;
		}

		public function get_html(){
			$this->generate_html();
			return $this->html_string;
		}

		public function get_name(){
			return $this->name;
		}

		public function get_args(){
			return $this->args;
		}

		public function is_image_only_upload(){
			return $this->args['image_only'] ? true : false;
		}

		protected function add_arguments_to_html_string(){
			if($this->args){
				foreach ($this->args as $attr => $value) {
					$this->html_string .= ' '.$attr.' = "'.$value.'"';
				}
			}			
		}

		protected function get_saved_value(){
			switch ($this->value_db_location) {
				case 'meta':
					return get_post_meta($_GET['post'], $this->name, true );
					break;
				case 'option':
					return get_option($this->name, $this->default_value);
					break;
			}
		}

		public function set_value_db_location($string){
			$this->value_db_location = $string;
		}

		public function set_context($context){
			$this->context = $context;
		}

		abstract function generate_html();
	}
?>