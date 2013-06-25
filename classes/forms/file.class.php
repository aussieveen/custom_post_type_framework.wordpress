<?php
	
	require_once('input.class.php');

	class File extends input{

		public function __construct($name,$args){
			parent::__construct($name,$args);
		}

		public function generate_html(){
			$this->html_string = "<p>";
			if($this->title){
				$this->html_string .= $this->title.": ";
			}
			$default = $this->default_value ? $this->default_value : "";
			$this->html_string .= '<input type = "file" name = "'.$this->name.'"';

			parent::add_arguments_to_html_string();

			$this->html_string .= $this->description ? "></br><em>".$this->description."</em></p>" : "></p>";

			$previous_file = parent::get_saved_value();
			if($previous_file){
				$wp_upload_dir = wp_upload_dir();
				$previous_file_path = $wp_upload_dir['basedir'].$previous_file;
				$previous_file_url = $wp_upload_dir['baseurl'].$previous_file;
				if(in_array("fileinfo", get_loaded_extensions())){
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					$mime = finfo_file($finfo,$previous_file_path);
				}
				$this->html_string .= '<p><strong>Previous file uploaded</strong></br><a href = "'.$previous_file_url.'" title = "Current uploaded file">'.$previous_file_url.'</a></p>';
				$this->html_string .= '<input type = "hidden" name = "'.$this->name.'" value = "'.$previous_file.'">';
			}
		}

	}

?>