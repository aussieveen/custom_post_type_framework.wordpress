<?php
	class NonceControl{

		private $nonces;

		private function __construct(){

		}

		public static function Instance(){
			static $noncecontrol_inst = null;
			if($noncecontrol_inst === null){
				$noncecontrol_inst = new NonceControl();
			}
			return $noncecontrol_inst;			
		}

		public function create_nonce($unique_value){
			$nonce = wp_create_nonce($unique_value);
			$this->nonces[$unique_value] = $nonce;
			return $nonce;
		}

		public function nonce_exists($unique_value){
			return $this->nonces[$unique_value] ? true : false;
		}

		public function verify_nonce($nonce,$key = null){
			if($key){
				return $this->nonces[$key] == $nonce ? true : false;
			}else{
				return in_array($nonce, $this->nonces);
			}
		}



	}

?>