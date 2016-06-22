<?php 
	
	class Authenticator{

		private $ID;
		private $password;
		private $db;

		const SES_NAME  = "SELAMAWI_ID";
	

		function __construct($ID, $password) {
			
			global $db;

			$this->db = $db;
			$this->ID   = $ID;
			$this->password   = $password;
			$this->encrypt_password();
		}

		//encrypt password
		public static function encryptAlgo($data) 
		{
			return md5($data);
		}

		//encrypt password using md5
		public function encrypt_password(){
			
			$this->password = self::encryptAlgo($this->password);
			
		}

		//starts the session
		public static function start_session() {
			session_name(Authenticator::SES_NAME);
			@session_start();

		}

		
		public function store_session($name, $value) {
			$_SESSION[$name] = $value;

		}

		//retrieve session with the specified name
		public static function get_session($name){
			
			if(!isset($_SESSION[$name]))
				return null;
			

			return $_SESSION[$name];

		}

		//authetnicates the user
		//stores id in session
		//stores loggedIn flag to check if the user is authenticated
		public function authenticate(){

			$query = "SELECT * FROM " . TABLE_STUD . " WHERE ID = '$this->ID' and password = '$this->password' and status=" . STUD_REG . " LIMIT 1";

			$types = "ss";
			$params = array($types, $this->ID, $this->password);
			$this->db->prp_stmt($query);
			
			

			if($this->db->get_num_rows()!=1)
				return false;	


			$rslt = $this->db->get_resultset();
			
			$this->store_session('studID' , (int)$rslt[0]->studID);
			$this->store_session('loggedIn' , true);

			return true;

		}

		//check if the user is authenticated
		public static function authenticated() {

			if( self::get_session('loggedIn') !=null )
				return self::get_session('loggedIn');

			return false;

		}

		//remove all session stored for a user
		public function del_session() {
			
			$_SESSION = array();
			session_destroy();
			setcookie(self::SES_NAME,'',time()-1000);

		}

	
		//check if the old password is valid
		public static function checkOldPassword($oldPassword)
		{
			$query = "SELECT * FROM " . TABLE_STUD ." WHERE studID = " . self::get_session(STUDID) . " and password='$oldPassword'";

			$this->db->prp_stmt($query);

			return $this->db->get_num_rows();

		}


	}
	

?>