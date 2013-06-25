<?php
	class Taxonomy{

		private $name;
		private $slug;

		public function __construct($name,$post_type = 'post',$args = null){
			
			$this->slug = $this->name = $name;
			$this->slug = strtolower(str_replace(" ", "_", $this->slug));
			$args = $this->merge_arguments($args);
			if(!$args['labels']){
				$args['labels'] = $this->generate_labels();
			}
			register_taxonomy( $this->slug, $post_type, $args);
		}

		private function generate_labels(){
			$labels = array(
			'name' => _x( $this->name.'s', 'taxonomy general name' ),
			'singular_name' => _x( $this->name, 'taxonomy singular name' ),
			'search_items' =>  __( 'Search '.$this->slug.'s' ),
			'all_items' => __( 'All '.$this->slug ),
			'edit_item' => __( 'Edit '.$this->slug ), 
			'update_item' => __( 'Update '.$this->slug ),
			'add_new_item' => __( 'Add New '.$this->slug ),
			'new_item_name' => __( 'New '.$this->slug ),
			'menu_name' => __( $this->name ),
			);
			return $labels;			
		}

		private function merge_arguments($args){
			$default_args = array(
					'hierarchical' => true,
					'show_ui' => true,
					'query_var' => true,
					'rewrite' => array( 'slug' => $this->slug )
					);				
			$args = $args ? array_merge($default_args,$args) : $default_args;
			return $args;			
		}

	}
?>