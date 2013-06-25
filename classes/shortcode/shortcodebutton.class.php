<?php

	class ShortcodeButton{

		private $tag;
		private $text;
		private $icon;
		private $post_type_slug;

		public function __construct($tag,$text,$post_type_slug,$icon = null){
			$this->tag = $tag;
			$this->text = $text;
			$this->icon = $icon;
			$this->post_type_slug = $post_type_slug;
			add_filter('media_buttons_context', array($this,'add_shortcode_button'));
		}

		public function add_shortcode_button($context){
			$out = '<a href="#" class="button" style = "padding-left:0.4em;" onclick="win = window.dialogArguments || opener || parent || top;
				win.send_to_editor(\'['.$this->tag.']\');return false;">';
			if($this->icon){
				$out .= '<span class="custom_post_type_media_icon" style = "background:url('.$this->icon.');display: inline-block;height: 16px;margin: 0 2px 0 0;vertical-align: text-top;width: 16px;background-position-x: -7px;background-position-y: -7px;background-repeat: no-repeat;"></span>';
			}
			$out .= "$this->text</a>";
		    return $context . $out;			
		}		

	}

?>