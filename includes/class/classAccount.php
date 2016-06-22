<?php 
	
	class Account{
		
		private $db;
		
		private $student;
		
		private $audc;

		const TOP = 3;

		public function __construct($userID) 
		{
			
			global $db;

			$this->db = $db;

			$this->student = new Student((int)$userID);
			
			if(!$this->student->exist() or $this->student->blocked()){
				Authenticator::del_session();
				redirect(PAGE_LOGIN);
			}
			$this->popAudInfo();			

		}

		public function popAudInfo()
		{

			$sec = "s-" . $this->getSection();
			$year = "y-" . $this->getYear();
			$dept = "d-" . $this->getDepartment();

			$class = md5($dept . "-" . $sec . "-" . $year);
			$year  = md5($year);
			$dept = md5($dept);

			$this->audc = array(
							'class' => $class,
							'year'  => $year,
							'dept'  => $dept,
							'all'   => AUD_PUB
						);
			
		}
		//list all petitions
		public function listAllPetitions($num=0 , $type = PET_UNRESOLVED) 
		{

			$num = (int)$num;

			$num = ($num<1) ? 0 : $num-1;
			
			$results = array();

			$formated = formatForQuery($this->audc,false);
			
			$query = "SELECT * FROM " . TABLE_PET . " WHERE status<> " . PET_ST_DEL ." and sent=" . $type  . " and audience IN $formated ORDER by post_date DESC LIMIT " . $num*PETPERP .",".PETPERP;
			
			If (!$this->db->prp_stmt($query))
				return;
			
			

			foreach($this->db->get_resultset() as $result) 
			{

				
				$results[] = new Petition($result);
			
			}

			return $results;
			
		}

		//sorts based SENAYT algorithm
		public function popularPetitions()
		{

			$query = "SELECT *, (num_comments * " . FACT_COM . " + num_up_votes*" . FACT_UP ." - num_down_votes*" . FACT_DOWN . ") as score FROM " . TABLE_PET . " WHERE sent=" . PET_UNRESOLVED  . " ORDER BY score DESC LIMIT " . self::TOP;

			$this->db->prp_stmt($query);

			$results = array();

			foreach($this->db->get_resultset() as $result) 
				$results[] = new Petition($result);

			return $results;

		}

		//number of petitions
		public function numOfPetitions() {
			$query = "SELECT count(1) as numPetitions FROM " . TABLE_PET;

			$this->db->prp_stmt($query);

			return ($this->db->get_resultset()[0]->numPetitions);
		}

		public function aPetition($petID) {
			$petID = (int)$petID;

			return new Petition($petID);
		}
		
		public function myInterest($tags) 
		{
			$formated = formatForQuery($tags);
			if (!$formated)
				return;
			$audiences = formatForQuery($this->audc,false);
			$query = "SELECT * FROM " . TABLE_PET ." WHERE petID IN (SELECT petID from " . TABLE_PET_TAG ." WHERE tagID IN $formated and audience IN $audiences group by petID) and sent=" . PET_UNRESOLVED ;

			$this->db->prp_stmt($query);

			$result = $this->db->get_resultset();

			$petitions = array();
			
			foreach($result as $pet) 
				$petitions[] = new Petition($pet);
			
			return $petitions;
		}

		public function __call($method, $arguments) 
		{
			
			if (method_exists($this->student, $method)) {
			
				return call_user_func_array( 
												array($this->student, $method), $arguments
											);

			}

		}



		


	}



?>