<?php 

	
	class DB {
		
		private $db;
		private $connected = true;
		private $resultset = array();
		private $num_rows  = 0;
		private $affected_rows  = -1;
		private $lastID = -1;
		public $suc = true;

		const HOST       = "localhost";
		const DBNAME     = "selamawi_db";
		const USRNAME    = "root";
		const PSSWRD     = "";

		function __construct() {
			
			$this->db = new mysqli(DB::HOST, DB::USRNAME, DB::PSSWRD, DB::DBNAME);

			//connection to the database failed
			if(mysqli_connect_errno())
			{
				
				$this->connected = false;
				die();
			}

		}

		//query : string, $params: array
		function prp_stmt($query, $params='') {

		//	$query = $this->db->real_escape_string($query);
			$stmt = $this->db->query($query);

			if(!$stmt)
			{
				$this->suc = false;
				return false;
			
			}


			//$stmt->bind_param("s",$tabor);
			//call_user_func_array( array($stmt,'bind_param'), self::refValues($params) );
			//$stmt->execute();
			//$this->resultSet = $stmt->get_result();

			
			
			$this->resultset = array();
			$this->num_rows = $stmt->num_rows;
			$this->affected_rows = $this->db->affected_rows;


			for($i=0;$i<$this->num_rows;$i++) 

				$this->resultset[$i] = $stmt->fetch_object();


			return true;

		}

		function prepare($query){
			$stmt = $this->db->prepare($query);
			
			return $stmt;
		}

		function insert_stmt($query) {

			$stmt = $this->db->query($query);

			if(!$stmt)
				return false;


			//$stmt->bind_param("s",$tabor);
			//call_user_func_array( array($stmt,'bind_param'), self::refValues($params) );
			//$stmt->execute();
			//$this->resultSet = $stmt->get_result();

			
			
			$this->resultset = array();
			$this->num_rows = -1;
			$this->affected_rows = $this->db->affected_rows;
			$this->lastID = $this->db->insert_id;

			for($i=0;$i<$this->num_rows;$i++) 

				$this->resultset[$i] = $stmt->fetch_object();


			return true;

		}


		public static function refValues($arr){

		    if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
		    {
		        $refs = array();

		        foreach($arr as $key => $value)
		            $refs[$key] = &$arr[$key];

		        return $refs;
		    }

		    return $arr;

		}

		function get_resultset(){

			return $this->resultset;
		}

		function get_num_rows(){
			return $this->num_rows;
		}

		function get_affected_rows(){
			return $this->affected_rows;
		}

		function get_lastID(){
			return $this->lastID;
		}

	}

	$db = new DB();
?>