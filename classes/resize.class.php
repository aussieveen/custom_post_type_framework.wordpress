<?php
	class Resize{

		private $image_width;
		private $image_height;
		private $image;
		private $canvas;
		private $canvas_width;
		private $canvas_height;

		public function __construct($image,$canvas_width,$canvas_height,$hex_string = "FFFFFF"){
			if (exif_imagetype($image) == IMAGETYPE_JPEG){
				$this->image = @imagecreatefromjpeg($image);
			}else if (exif_imagetype($image) == IMAGETYPE_PNG){
				$this->image = @imagecreatefrompng($image);
			}else if (exif_imagetype($image) == IMAGETYPE_GIF){
				$this->image = @imagecreatefromgif($image);
			}

		    $this->image_width = imagesx($this->image);
		    $this->image_height = imagesy($this->image);

			$this->canvas = imagecreatetruecolor($canvas_width, $canvas_height);
			$this->canvas_width = $canvas_width;
			$this->canvas_height = $canvas_height;
		    $hexlength = strlen($hex_string);
		    
		    if($hexlength % 3 != 0){
		    	$hex_string = "FFFFFF";
		    }		    
		    if($hexlength == 3){
		    	$temp_string[0] = $temp_string[1] = $hex_string[0];
		    	$temp_string[2] = $temp_string[3] = $hex_string[1];
		    	$temp_string[4] = $temp_string[5] = $hex_string[2];
		    	$hex_string = $temp_string;
		    }
			$r = hexdec($hex_string[0].$hex_string[1]);
			$g = hexdec($hex_string[2].$hex_string[3]);
			$b = hexdec($hex_string[4].$hex_string[5]);

		    $bgcolor = imagecolorallocate($this->canvas, $r,$g,$b);
		    imagefill($this->canvas,0,0,$bgcolor);	
		}

		public function resize(){
		    $image_aspect_ratio = $this->image_width / $this->image_height;
		    $canvas_aspect_ratio = $this->canvas_width / $this->canvas_height;

		    // scale by height
		    if ($image_aspect_ratio < $canvas_aspect_ratio) {
		        $new_height = $this->canvas_height;
		        $new_width = ($this->canvas_height/$this->image_height) * $this->image_width;
		    } 
		    // scale by width
		    else {
		        $new_width = $this->canvas_width;
		        $new_height = ($this->canvas_width/$this->image_width) * $this->image_height;
		    }
		    
		    # offset values (ie. center the resized image to canvas)
		    $xoffset = ($this->canvas_width - $new_width) / 2;
		    $yoffset = ($this->canvas_height - $new_height) / 2;
		    
		    imagecopyresampled($this->canvas,$this->image,$xoffset,$yoffset,0,0,$new_width,$new_height,$this->image_width,$this->image_height);
		    imagedestroy($this->image);
		    return $this->canvas;
		}

		public function crop($x,$y,$width,$height){
			imagecopyresampled($this->canvas, $this->image, 0, 0, $x, $y, $this->canvas_width, $this->canvas_height, $width, $height);
			imagedestroy($this->image);
			return $this->canvas;
		}

		public function manipulate($x,$y,$width,$height){
		    imagecopyresampled($this->canvas, $this->image, $x, $y, 0, 0, $width, $height, $this->image_width, $this->image_height);
		    imagedestroy($this->image);
		    return $this->canvas;			
		}
	}
?>