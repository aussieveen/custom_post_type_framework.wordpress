<?php 
	require_once('imagemanipulation.class.php');

	class ResizeToCanvas extends ImageManipulation{

		public function __construct($name,$args){
			parent::__construct($name,$args);
		}

		public function image_manipulation_step_2(){
			$tmp_name = $_FILES[$this->input_file_name]['tmp_name'];
			$this->valid_image_or_die($tmp_name);
			$resize = new Resize($tmp_name,$this->canvas_width,$this->canvas_height,$this->canvas_bg_color);
			$resized = $resize->resize();
			$filename = $this->get_filename($_FILES[$this->input_file_name]['name']);

			$filename = str_replace(" ","_",$filename."-".$this->canvas_width."x".$this->canvas_height."-".time().".jpg");
			$destination = $this->wp_upload_dir['basedir']."/".$filename;

			$this->save_new_image($resized,$destination);

			$imgurl = $this->wp_upload_dir['baseurl']."/".$filename;
			?>
			<p>Your image has been resized</p>
			<img src = "<?php echo $imgurl;?>"/>
			<?php $this->include_close_window_js($filename);?>
			<p class = "submit"><a style = "cursor:pointer" class="button-primary" onclick="close_upload_window()" >Close</a></p>
			<?php
			die();
		}
		public function image_manipulation_step_3(){}

	}
?>