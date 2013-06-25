<?php
	require_once(plugin_dir_path( __FILE__ ).'../forms/form.class.php');
	require_once(plugin_dir_path( __FILE__ ).'../fileuploader.class.php');

	class ConfigurationPage{

		private $post_type_slug;
		private $option_group;
		private $form;

		public function __construct($post_type_slug,$form,$option_group_name = NULL){
			$this->post_type_slug = $post_type_slug;
			$this->form = $form;
			$this->option_group = $option_group_name ? $option_group_name : $post_type_slug."_settings";
			if($this->form->has_fields()){
				add_action('admin_menu', array($this,'add_menu_page'));
			}else{
				return 'No fields on form';
			}
		}

		public function get_option_group(){
			return $this->option_group;
		}

		public function add_menu_page(){
			add_action('admin_init',array($this,'register_my_settings'));
			add_submenu_page('edit.php?post_type='.$this->post_type_slug, 'Configuration', 'Configuration', 'manage_options', $this->post_type_slug.'_configuration',array($this,'configuration_page_layout'));
		}

		public function register_my_settings(){
			foreach($this->form->get_fields() as $field){
				register_setting($this->option_group,$field->get_name());
			}
		}

		public function configuration_page_layout(){
			?>
			<div class = "wrap">
				<h2>Configuration Page</h2>
				<form method = "post" action = "options.php" enctype="multipart/form-data">
					<?php
					settings_fields( $this->option_group );
					$this->form->set_field_value_db_location("option");
					$this->form->print_layout();
					submit_button( $text = "Save Changes", $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null );
					?>
				</form>	
			</div>
			<?php
		}
	}

?>