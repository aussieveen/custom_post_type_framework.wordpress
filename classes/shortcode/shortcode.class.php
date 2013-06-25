<?php

	class Shortcode{

		private $tag;
		private $post_type_slug;
		private $shortcode_button;
		
		public function __construct($tag,$func = null,$post_type_slug = null){
			if(!$func){
				$func = array(&$this,'default_shortcode_function');
			}
			$this->tag = $tag;
			add_shortcode( $tag, $func );
			if($post_type_slug){
				$this->post_type_slug = $post_type_slug;
			}
		}

		public function default_shortcode_function(){
			$posts = get_posts(  array(
				'numberposts'		=>	-1,
				'offset'			=>	0,
				'orderby'			=>	'post_date',
				'order'				=>	'DESC',
				'post_type'			=>	$this->post_type_slug,
				'post_status'		=>	'publish' )
			);
			if($posts){
				echo '<ul class = "list_of_'.$this->post_type_slug.'_posts">';
				foreach ($posts as $post) {
					echo '<li><a href = "'.get_permalink( $post->ID).'" title = "'.$post->post_title.'">'.$post->post_title.'</a></li>';
				}
				echo '</ul>';
			}
		}

		public function enable_shortcode_button($text = null,$icon = null){
			require_once('shortcodebutton.class.php');
			if($text == null){
				$text = "List";
				if($this->post_type_slug){$text .= " ".$this->post_type_slug."s";}
			}
			if($icon){$this->icon = $icon;}
			$this->shortcode_button = new ShortcodeButton($this->tag,$text,$this->post_type_slug,$icon);
		}

	}

?>