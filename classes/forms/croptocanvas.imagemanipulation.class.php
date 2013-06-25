<?php 
	require_once('imagemanipulation.class.php');

	class CropToCanvas extends ImageManipulation{
		public function __construct($name,$args){
			parent::__construct($name,$args);
		}

		public function image_manipulation_step_2(){
			$tmp_name = $_FILES[$this->input_file_name]['tmp_name'];
			$this->valid_image_or_die($tmp_name);
			$uploaded_filename = str_replace(" ","_",basename($_FILES[$this->input_file_name]['name']));
			$destination = $this->wp_upload_dir['basedir']."/".$uploaded_filename;
			
			$transientid = rand();
			set_transient($transientid, $destination, 60 * 60 * 5 );	

			if(move_uploaded_file($tmp_name, $destination)){
				$moved_file_url = $this->wp_upload_dir['baseurl']."/".$uploaded_filename;
			}else{
				echo "<p>There was an error uploading the image.Please try again</p>";
				$this->image_manipulation_step_1();
				die();
			}
			list($width, $height, $type, $attr) = getimagesize( $moved_file_url );
			?>
			<p>The cropping process is done through click and drag. Click on the uploaded image and drag to create the area you wish to keep. Click on the cropped area and drag to tweak the positioning.</p>
			<div><img src="<?php echo $moved_file_url;?>" id="original_image"></div>
			<div style = "width:100%,height:1px;float:left;clear:both"></div>
			<div id="coordsform">
				<form method = "POST" action="<?php echo admin_url('admin-ajax.php'); ?>?action=image_manipulation_<?php echo $this->name;?>&step=3">
					<input type="hidden" id = "x" name = "x">
					<input type="hidden" id = "y" name = "y">
					<input type="hidden" id = "w" name = "w">
					<input type="hidden" id = "h" name = "h">
					<input type="hidden" id = "transientid" name = "transientid" value = "<?php echo $transientid;?>">
					<p class = "submit"><input type="submit" value="Save"/></p>
				</form>
			</div>
			<?php
			$this->include_jquery_ui();
			$css = str_replace(" ","%20",plugins_url( 'croptocanvas.imagemanipulation.css' , __FILE__ ));
			$js = str_replace(" ","%20",plugins_url( 'croptocanvas.imagemanipulation.js' , __FILE__ ));
			?>
			<script>
				var image_width = <?php echo $width;?>;
				var image_height = <?php echo $height;?>;
				var canvas_width = <?php echo $this->canvas_width;?>;
				var canvas_height = <?php echo $this->canvas_height;?>;
			</script>
			<script type = "text/javascript" src = "<?php echo $js;?>"></script>
			<link rel = "stylesheet" type = "text/css" href = "<?php echo $css;?>"></style>
			<?php		
			die();
		}
		public function image_manipulation_step_3(){
			$original_image = get_transient($_POST['transientid']);
			delete_transient($_POST['transientid']);

			$resize = new Resize($original_image,$this->canvas_width,$this->canvas_height,$this->canvas_bg_color);
			$cropped = $resize->crop($_POST["x"],$_POST["y"],$_POST["w"],$_POST["h"]);

			$filename = explode("/", $original_image);
			$filename = $filename[count($filename)-1];
			$filename = str_replace(" ","_",$this->get_filename($filename)."-".$this->canvas_width."x".$this->canvas_height."-".time().".jpg");
			
			$destination = $this->wp_upload_dir['basedir']."/".$filename;
			
			$this->save_new_image($cropped,$destination);

			$imgurl = $this->wp_upload_dir['baseurl']."/".$filename;
			?>
			<p>Your image has been cropped</p>
			<img src = "<?php echo $imgurl;?>"/>
			<?php $this->include_close_window_js($filename);?>
			<p class = "submit"><a style = "cursor:pointer" class="button-primary" onclick="close_upload_window()" >Close</a></p>
			<?php
			die();
		}
	}
?>