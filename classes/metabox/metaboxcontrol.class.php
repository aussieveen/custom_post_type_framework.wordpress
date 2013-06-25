<?php
	class MetaboxControl{

		private $metaboxes;

		private function __construct(){

		}

		public static function Instance(){
			static $metaboxcontrol_inst = null;
			if($metaboxcontrol_inst === null){
				$metaboxcontrol_inst = new MetaboxControl();
			}
			return $metaboxcontrol_inst;			
		}

		public function add_metabox($metabox,$post_type_slug = null){
			$this->metaboxes[$post_type_slug][] = $metabox;
		}

		public function has_metaboxes(){
			return empty($this->metaboxes) ? false : true;
		}

		public function activate_metaboxes(){
			foreach ($this->metaboxes as $post_type_slug => $post_type_metaboxes) {
				if($post_type_metaboxes){
					foreach ($post_type_metaboxes as $metabox) {
						$metabox->activate();
					}
				}
			}
		}

		public function get_metaboxes($post_type_slug = null){
			return $post_type_slug ? $this->metaboxes[$post_type_slug] : $this->metaboxes;
		}

		

	}

?>