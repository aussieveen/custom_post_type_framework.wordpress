<?php	

	class AJAX{
		public static function remove_image(){
			switch ($_POST['value_db_location']) {
				case 'meta':
					$image_name = get_post_meta( $_POST['id'], $_POST['name'], $single = true );
					$result = delete_post_meta( $_POST['id'], $_POST['name']);
					break;
				case 'option':
					$image_name = get_option( $_POST['name'] );
					$result = delete_option( $_POST['name'] );
					break;
			}

			if($result){
				$wp_upload_dir = wp_upload_dir();
				unlink($wp_upload_dir['basedir']."/".$image_name);
			}
			echo $result;
			die();
		}
	}

?>