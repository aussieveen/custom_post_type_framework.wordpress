<?php

/*
	Plugin Name: Custom Post Type Framework
	Plugin Author: Simon McWhinnie
	Version: 1.0
	Author URI: http://www.veen-online.com
*/
require_once('classes/configuration/configurationpage.class.php');
require_once('classes/taxonomy/taxonomy.class.php');
require_once('classes/metabox/metabox.class.php');
require_once('classes/metabox/metaboxcontrol.class.php');
require_once('classes/forms/form.class.php');
require_once('classes/shortcode/shortcode.class.php');
require_once('classes/html/html.class.php');

define ('MYPLUGINPATH',plugin_dir_path( __FILE__ ));

/**
 * GLOBAL VARIABLES
 */
global $metaboxes_to_post_action_set;
global $custom_post_types;

class CustomPostType{

	protected $name;
	protected $slug;
	protected $configuration_page;
	protected $post_icon_classic_file_path;
	protected $post_icon_fresh_file_path;
	protected $post_icon_hover_file_path;
	protected $shortcode;

	private $metaboxcontrol;

	public function __construct($name,$args = null){

		$this->slug = $this->name = $name;
		$this->slug = strtolower(str_replace(" ", "_", $this->slug));

		$args = $this->merge_arguments($args);
		if(!$args['labels']){
			$args['labels'] = $this->generate_labels();
		}

		register_post_type( $this->slug, $args );
		global $metaboxes_to_post_action_set;
		if(!$metaboxes_to_post_action_set){
			add_action('admin_init',array($this,'add_metaboxes_to_post'),10,1);
			$metaboxes_to_post_action_set = true;
		}
		global $custom_post_types;
		$custom_post_types[$this->slug] = &$this;

		$this->metaboxcontrol = MetaboxControl::Instance();
	}

	private function generate_labels(){
		$labels = array(
				'name' => $this->name.'s',
				'singlular_name' => $this->name,
				'add_new' => 'Add New',
				'add_new_item' => 'Add New '.strtolower($this->name),
				'edit_item' => 'Edit '.strtolower($this->name),
				'view_item' => 'View '.strtolower($this->name),
				'search_items' => 'Search '.strtolower($this->name).'s',
				'not_found' => 'No '.strtolower($this->name).'s found',
				'not_found_in_trash' => 'No '.strtolower($this->name).' found in trash'
			);
		return $labels;
	}

	private function merge_arguments($args){
		$default_args = array(
				'menu_position' => 20,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true, 
				'query_var' => true,
				'rewrite' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'supports' => array(
					'title',
					'editor'
				)				
			);
		$args = $args ? array_merge($default_args,$args) : $default_args;
		return $args;
	}

	protected function add_taxonomy($taxonomy_name){
		$taxonomies[] = new Taxonomy($taxonomy_name,$this->slug);
	}

	public function add_metaboxes_to_post(){
		if($this->metaboxcontrol->has_metaboxes()){
			$this->metaboxcontrol->activate_metaboxes();
		}
	}

	public function add_post_type_icon($post_icon_classic_file_path,$post_icon_hover_file_path = NULL,$post_icon_fresh_file_path = NULL){
		$this->post_icon_classic_file_path = $post_icon_classic_file_path;
		$this->post_icon_hover_file_path = $post_icon_hover_file_path ? $post_icon_hover_file_path : $post_icon_classic_file_path;
		$this->post_icon_fresh_file_path = $post_icon_fresh_file_path ? $post_icon_fresh_file_path : $post_icon_classic_file_path;
		add_action('admin_head', array(&$this,'set_post_type_icon' ));
	}

	public function set_post_type_icon(){
		?>
	    <style type="text/css" media="screen">
	        .admin-color-fresh #menu-posts-<?php echo $this->slug;?> div.wp-menu-image{
	            background: url(<?php echo $this->post_icon_fresh_file_path;?>) no-repeat 2px 0px !important;
	        }
	        .admin-color-classic #menu-posts-<?php echo $this->slug;?> div.wp-menu-image{
	            background: url(<?php echo $this->post_icon_classic_file_path;?>) no-repeat 2px 0px !important;
	        }
			.admin-color-fresh #menu-posts-<?php echo $this->slug;?>:hover .wp-menu-image, .admin-color-fresh #menu-posts-<?php echo $this->slug;?>.wp-has-current-submenu .wp-menu-image, .admin-color-classic #menu-posts-<?php echo $this->slug;?>:hover .wp-menu-image, .admin-color-classic #menu-posts-<?php echo $this->slug;?>.wp-has-current-submenu .wp-menu-image {
		            background: url(<?php echo $this->post_icon_hover_file_path; ?>) no-repeat 2px 0px !important;
	        }
	    </style>
		<?php
	}

	public function get_all_post_meta($post_id){
		$post_type = get_post_type($post_id);
		$metaboxcontrol = MetaboxControl::Instance();
		$metaboxes = $metaboxcontrol->get_metaboxes($post_type);
		foreach ($metaboxes as $metabox) {
			$metakeys = $metabox->get_form_field_names();
			foreach ($metakeys as $key) {
				$metadata = get_post_meta($post_id, $key, true);
				if($metadata){$post_meta[$key] = $metadata;}
			}			
		}
		return $post_meta;
	}

	public function add_post_shortcode( $tag, $func = null){
		$this->shortcode = new Shortcode($tag,$func,$this->slug);
	}

	public function enable_shortcode_button($button_text){
		if($this->post_icon_fresh_file_path){
			$icon = $this->post_icon_fresh_file_path;
		}
		$this->shortcode->enable_shortcode_button($button_text,$icon);
	}

}

?>