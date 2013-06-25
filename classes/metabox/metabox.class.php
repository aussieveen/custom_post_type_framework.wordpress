<?php
	require_once(plugin_dir_path( __FILE__ ).'../forms/form.class.php');
	require_once(plugin_dir_path( __FILE__ ).'../noncecontrol.class.php');
	require_once('metadata.class.php');

	class Metabox{

		private $post_type_slug;
		private $id;
		private $title;
		private $context;
		private $priority;

		private $form;

		private $metadata;
		private $noncecontrol;
		private $metaboxcontrol;

		public function __construct($post_type_slug,$id,$title,$form = null,$context = "normal",$priority = "high"){
			$this->post_type_slug = $post_type_slug;
			$this->id = $id;
			$this->title = $title;
			$this->context = $context;
			$this->priority = $priority;
			$this->form = $form ? $form : new Form();
			$this->metadata = Metadata::Instance();
			$this->noncecontrol = NonceControl::Instance();
			if(!$this->noncecontrol->nonce_exists($this->post_type_slug)){
				$this->add_field('nonce',$this->post_type_slug."_nonce",array('unique_value'=>$this->post_type_slug));
			}
			$this->metaboxcontrol = MetaboxControl::Instance();
			$this->metaboxcontrol->add_metabox(&$this,$post_type_slug);			
		}

		public function add_sub_heading($sub_heading){
			$this->form->add_sub_heading($sub_heading);
		}

		public function add_field($type,$name,$args = null){
			$this->form->add_field($type,$name,$args);
		}

		public function render_layout(){
			$this->form->set_field_value_db_location("meta");
			$this->form->set_field_context($this->context);
			$this->form->print_layout();
		}

		public function activate(){
			add_meta_box($this->id, $this->title, array($this,'render_layout'), $this->post_type_slug, $this->context, $this->priority);
			$this->metadata->add_meta_fields($this->form->get_fields(),$this->post_type_slug);
		}

		public function get_form_field_names(){
			$fields = $this->form->get_fields();
			foreach ($fields as $field) {
				if(get_class($field) != "Nonce"){
					$names[] = $field->get_name();
				}
			}
			return $names;
		}
	}

?>