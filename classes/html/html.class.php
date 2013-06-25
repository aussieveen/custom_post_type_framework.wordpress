<?php

	class HTML{

		private $elements;
		private $html_string;


		public function __construct($elements){
			$this->elements = $elements;
			$this->html_string = $this->filter_elements($elements);
		}

		private function filter_elements($elements){
			$string = "";
			if($this->is_assoc($elements)){
				$string .= $this->filter_element($elements);
			}else{
				foreach ($elements as $element) {
					$string .= $this->filter_element($element);
				}
			}
			return $string;
			
		}

		private function filter_element($element){
			if(is_object($element)){
				return $element->output_html();
			}
			$return = NULL;
			$type = $element['type'];
			if($element['args']){
				$args = $element['args'];
			}
			if($args){
				$content = $args['content'];
				if(is_array($content)){
					$content = $this->filter_elements($content);
				}
				unset($args['content']);
			}
			switch ($type) {
				case 'a':
					require_once('a.class.php');
					$element = new a($content,$args);
					break;
				case 'div':
					require_once('div.class.php');
					$element = new div($content,$args);
					break;
				case 'h':
					$scale = 1;
					if($args['scale']){
						$scale = $args['scale'];
						unset($args['scale']);
					}
					require_once('h.class.php');
					$element = new h($content,$scale,$args);
					break;
				case 'img':
					require_once('img.class.php');
					$element = new img($args);
					break;
				case 'li':
					require_once('li.class.php');
					$element = new li($content,$args);
					break;
				case 'ol':
					require_once('ol.class.php');
					$element = new ol($content,$args);
					break;
				case 'p':
					require_once('p.class.php');
					$element = new p($content,$args);
					break;
				case 'ul':
					require_once('ul.class.php');
					$element = new ul($content,$args);
					break;
			}
			return $element->output_html();		
		}

		public function output_html(){
			echo $this->html_string;
		}

		private function is_assoc($array) {
			return (bool)count(array_filter(array_keys($array), 'is_string'));
		}		

	}

?>