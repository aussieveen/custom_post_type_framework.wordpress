<?php
	
	require_once('input.class.php');
	require_once(plugin_dir_path( __FILE__ ).'../resize.class.php');
	require_once(plugin_dir_path( __FILE__ ).'../AJAX.class.php');


	abstract class ImageManipulation extends input{

		protected $canvas_width;
		protected $canvas_height;
		protected $canvas_bg_color = "FFFFFF";
		protected $link_title = "Click to upload and configure your image";
		protected $input_file_name = 'uploadedimage_';

		protected $wp_upload_dir;

		public function __construct($name,$args){
			if($args){
				foreach ($args as $key => $value) {
					switch ($key) {
						case 'canvas_width':
						case 'canvas_height':
						case 'canvas_bg_color':
						case 'context':
							$this->$key = $value;
							break;
						case 'link_text':
						case 'title':
						case 'link_title':
							$this->link_title = $args[$key];
							break;
					}
					unset($args[$key]);
				}
			}
			$this->input_file_name .= $name;
			$this->wp_upload_dir = wp_upload_dir();			
			parent::__construct($name,$args);

			add_action('wp_ajax_image_manipulation_'.$this->name, array(&$this,'image_manipulation'));
			add_action('wp_ajax_remove_image_'.$this->name,array('AJAX','remove_image'));
			add_action('admin_enqueue_scripts',array(&$this,'enqueue_thickbox'));
		}

		public function enqueue_thickbox(){
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
		}

		public function generate_html(){
			if($this->canvas_width && $this->canvas_height){
				$upload_url = $this->wp_upload_dir['baseurl'];
				$img_src_display_none = $this->get_saved_value() ? 'src="'.$upload_url."/".$this->get_saved_value().'"' : 'style = "display:none"';
			?>
				<script>
				var $ = jQuery;

				<?php echo $this->name;?>_image_check = setInterval(function(){
						var i,x,y,ARRcookies=document.cookie.split(";");
						for (i=0;i<ARRcookies.length;i++){
							x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
							y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
							x=x.replace(/^\s+|\s+$/g,"");
							if (x=="<?php echo $this->name;?>_imgsrc"){
								$('#<?php echo $this->name;?>_value_hidden_field').val(y);
								y = "<?php echo $upload_url;?>/"+y;
								$('.<?php echo $this->name;?>_manipulated_image').attr('src',y).css('display','block');
								$('.<?php echo $this->name;?>_manipulated_image').css({'display':'block'});
								document.cookie = "<?php echo $this->name;?>_imgsrc=;expires="+new Date(0).toUTCString();
							}
						}		
					},250);
				</script>
				<p><a id="image_manipulation_upload_link_<?php echo $this->name;?>" class="thickbox" href="<?php echo admin_url('admin-ajax.php'); ?>?action=image_manipulation_<?php echo $this->name;?>&step=1&TB_iframe=true&width=720&height=450" title="<?php echo $this->link_title;?>"><?php echo $this->link_title;?></a></p>
				<img style = "max-width:<?php if($this->context == 'normal' || $this->context == 'advanced'){echo 500;}else if($this->context == 'side'){echo 256;}else{echo 350;}?>px" class = "manipulated_image <?php echo $this->name;?>_manipulated_image" <?php echo $img_src_display_none;?>/>
				<?php if($this->get_saved_value()){?>
					<p><a id = "image_manipulation_remove_image_link_<?php echo $this->name;?>" href = "#" onclick = "remove_<?php echo $this->name;?>_image()" title = "Remove">Remove</a></p>
					<script>
					function remove_<?php echo $this->name;?>_image(){
						id = <?php echo $_GET['post'];?>;
						name = "<?php echo $this->name;?>";
						value_db_location = "<?php echo $this->value_db_location;?>";
						var data = {action:'remove_image_<?php echo $this->name;?>',id:id,name:name,value_db_location:value_db_location};
						$.post("<?php echo admin_url('admin-ajax.php');?>",data,function(response){
							if(response){
								$('.<?php echo $this->name;?>_manipulated_image').removeAttr('src').css({'display':'none'});
								$('#image_manipulation_remove_image_link_<?php echo $this->name;?>').css({'display':'none'});
							}
						});	
					}
					</script>
				<?php } ?>
				<input type = "hidden" name = "<?php echo $this->name;?>" id = "<?php echo $this->name;?>_value_hidden_field" value = "<?php echo $this->get_saved_value();?>"/>	
				<?php
			}else{
				?><p>A canvas height and width must be set in order to include an image manipulation field</p><?php
			}
		}

		public function image_manipulation(){
			$css = str_replace(" ","%20",plugins_url( 'imagemanipulation.css' , __FILE__ ));
			$js = str_replace(" ","%20",plugins_url( 'imagemanipulation.js' , __FILE__ ));
			?>
			<script>
				var fieldname = "<?php echo $this->name;?>";
			</script>
			<script type = "text/javascript" src = "<?php echo $js;?>"></script>
			<link rel = "stylesheet" type = "text/css" href = "<?php echo $css;?>"></style>
			<?php
			$function = "image_manipulation_step_".$_GET['step'];
			$this->$function();
		}

		protected function image_manipulation_step_1(){
			?>
			<form enctype="multipart/form-data" id="uploadForm" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>?action=image_manipulation_<?php echo $this->name;?>&step=2">
				<label for="upload">Choose an image from your computer (.gif | .jpeg |.png)</label><br /><input type="file" id="upload" name="<?php echo $this->input_file_name;?>" />
				<?php wp_nonce_field('image_manipulation') ?>
				<p class="submit"><input type="submit" class = "button-primary" value="<?php esc_attr_e('Upload'); ?>" /></p>
			</form>			
			<?php
			die();
		}

		abstract function image_manipulation_step_2();
		abstract function image_manipulation_step_3();

		protected function upload_image(){
			require_once(plugin_dir_path( __FILE__ ).'../fileuploader.class.php');
			$file = $_FILES[$this->input_file_name];
			$result = Fileuploader::upload_file($file,$image_only_upload = true);
		}

		protected function valid_image_or_die($file){
			if(!getimagesize($file)){
				echo "<p>Your upload was not a valid image file. Please try again";
				$this->image_manipulation_step_1();
				die();
			}			
		}

		protected function include_jquery_ui(){
			?>
			<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
			<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
			<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css">
			<script src="http://jcrop-cdn.tapmodo.com/latest/js/jquery.Jcrop.min.js"></script>
			<link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/latest/css/jquery.Jcrop.css" type="text/css" />
			<?php
		}

		protected function get_filename($filename){
			for($i = strlen($filename)-1; $i>=0; $i--){
				if($filename[$i] == "."){
					return substr($filename,0,$i - strlen($filename));
				}
			}
		}

		protected function include_close_window_js($filename){
			?>
			<script>
				function close_upload_window(){
					filename = "<?php echo $filename;?>";
					document.cookie="<?php echo $this->name;?>_imgsrc="+filename;
					self.parent.tb_remove();
				}
			</script>
			<?php
		}

		protected function save_new_image($image,$destination){
			ob_start();
			imagejpeg($image);
			$i = ob_get_contents();
			$fp = fopen($destination,'w');
			fwrite($fp,$i);
			fclose($fp);
			ob_end_clean();
		}

	}

?>