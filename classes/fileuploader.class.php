<?php

	class Fileuploader{

		public static function upload_file(Array $file, $image_only_upload = false){
			$valid_file = false;
			$valid_image_list = array("image/gif","image/jpeg","image/png","image/pjpeg","image/x-png");
			$valid_file_list = array("application/pdf","application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.ms-powerpoint","application/vnd.openxmlformats-officedocument.presentationml.presentation","application/vnd.ms-excel","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
			if($image_only_upload){
				$valid_file = self::is_valid_file($valid_image_list,$file['type']);
			}else{
				$valid_file = self::is_valid_file(array_merge($valid_file_list,$valid_image_list),$file['type']);
			}
			if($valid_file){
				$result =  wp_handle_upload($file, $overrides = array( 'test_form' => false ), $time = null );
				if($result['url']){
					$wp_upload_dir = wp_upload_dir();
					$value = str_replace($wp_upload_dir['baseurl'], "" , $result['url']);
					return $value;
				}
				return $result;				
			}else{
				return false;
			}
			
		}

		private function is_valid_file(Array $valid_files,$type){
			return in_array($type, $valid_files) ? true : false;
		}		

	}
	
?>