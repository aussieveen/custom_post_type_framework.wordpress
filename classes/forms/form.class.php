<?php
	
	class Form{

		private $layout;
		private $field_value_db_location;
		private $context;

		public function __construct($layout=null){
			if(is_array($layout)){
				foreach ($layout as $item) {
					if(is_array($item)){
						if($item['sub_heading']){
							$this->add_sub_heading($item['sub_heading']);
						}
						if($item['text']){
							$this->add_text($item['text']);
						}
						if($item['field'] && $item['name']){
							$this->add_field($item['field'],$item['name'],$item['args']);
						}
					}else{
						$this->add_sub_heading($item);
					}
				}
			}
		}

		public function set_field_value_db_location($string){
			$this->field_value_db_location = $string;
			foreach($this->layout as $item){
				if(is_object($item)){
					$item->set_value_db_location($this->field_value_db_location);
				}
			}
		}

		public function set_field_context($context){
			$this->context = $context;
			foreach($this->layout as $item){
				if(is_object($item)){
					$item->set_context($this->context);
				}
			}
		}		

		public function add_field($type,$name,$args = null){
			$args['form'] = $this->field_value_db_location;
			if(is_string($type) && is_string($name)){
				switch ($type) {
					case 'nonce':
						require_once('nonce.class.php');
						$field = new Nonce($name,$args);
						break;
					case 'text':
						require_once('textfield.class.php');
						$field = new Textfield($name,$args);
						break;
					case 'radio':
						require_once('radio.class.php');
						$field = new Radio($name,$args);
						break;
					case 'checkbox':
						require_once('checkbox.class.php');
						$field = new Checkbox($name,$args);
						break;
					case 'dropdown':
						require_once('dropdown.class.php');
						$field = new Dropdown($name,$args);
						break;
					case 'textarea':
						require_once('textarea.class.php');
						$field = new Textarea($name,$args);
						break;
					case 'file':
						require_once('file.class.php');
						$field = new File($name,$args);
						break;
					case 'resizetocanvas':
						require_once('resizetocanvas.imagemanipulation.class.php');
						$field = new ResizeToCanvas($name,$args);
						break;
					case 'croptocanvas':
						require_once('croptocanvas.imagemanipulation.class.php');
						$field = new CropToCanvas($name,$args);
						break;
					case 'manipulationoncanvas':
						require_once('manipulationoncanvas.imagemanipulation.class.php');
						$field = new ManipulationOnCanvas($name,$args);
						break;
				}
				$this->layout[] = $field;
			}
		}

		public function add_sub_heading($sub_heading){
			$this->layout[] = "<h3>$sub_heading</h3>";
		}

		public function add_text($text){
			$this->layout[] = "<p>$text</p>";
		}

		public function has_fields(){
			return $this->get_fields() ? true : false;
		}

		public function get_fields(){
			foreach($this->layout as $item){
				if(is_object($item)){
					$temp[] = $item;
				}
			}
			return $temp ? $temp : false;
		}

		public function print_layout(){
			foreach ($this->layout as $item) {
				if(is_object($item)){
					if($this->field_value_db_location == "option" && get_class($item) == "File"){
						echo "<p>File inputs are not natively supported by the Wordpress Settings API</p>";
					}else{
						echo $item->get_html();	
					}
				}
				if(is_string($item)){
					echo $item;
				}
			}
		}

	}

?>