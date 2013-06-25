<?php

	require_once(plugin_dir_path( __FILE__ ).'../fileuploader.class.php');
	require_once(plugin_dir_path( __FILE__ ).'../noncecontrol.class.php');

	class Metadata{

		private $metafields;
		private $noncecontrol;

		private function __construct(){
			$this->noncecontrol = NonceControl::Instance();
		}

		public static function Instance(){
			static $metadata_inst = null;
			if($metadata_inst === null){
				$metadata_inst = new Metadata();
				add_action('save_post',array($metadata_inst,'save_meta_data'));
			}
			return $metadata_inst;
		}

		public function add_meta_fields($fields,$post_type_slug){
			foreach ($fields as $field) {
				$this->metafields[$post_type_slug][] = $field;
			}
		}

		public function save_meta_data($post_id){
			if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
				return;
			}
			
			if(defined('DOING_AJAX') && DOING_AJAX){
				return;
			}
			
			global $post;
			if($post->post_type == 'revision') {
				return;
			}		
			if($this->metafields){
				foreach ($this->metafields as $post_type_slug => $fields){
					if($_POST[$fields[0]->get_name()]){
						if(get_class($fields[0]) == 'Nonce'){
							if(!$this->noncecontrol->verify_nonce($_POST[$fields[0]->get_name()],$post_type_slug)){return;}
						}
						foreach ($fields as $meta) {
							$meta_value = NULL;
							$class = get_class($meta);
							$field_name = $meta->get_name();
							switch ($class) {
								case 'File':
									$file = $_FILES[$field_name];
									if($file){
										$image_only_upload = $meta->is_image_only_upload();
										$result = Fileuploader::upload_file($file,$image_only_upload);
									}else{
										return false;
									}
									if(is_string($result)){
										$meta_value = $result;
									}
									break;
								case 'Nonce':
									break;
								default:
									$meta_value = $_POST[$field_name];
									break;
							}
							if($meta_value){
								$meta_id = update_post_meta( $post_id, $meta_key = $field_name, $meta_value, $prev_value = '' );
							}else{
								delete_post_meta( $post_id, $meta_key = $field_name, $meta_value = '' );
							}
						}
					}else{
						continue;
					}
				}
			}
		}
	}

?>