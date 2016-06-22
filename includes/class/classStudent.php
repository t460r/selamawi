<?php 
	
	class Student{
		
		public $firstName;
		private $middleName;
		private $lastName;
		private $role;
		private $studentID;
		private $email;
		private $department;
		//private $school;
		private $status;
		private $year;
		private $semester;
		private $section;
		private $exist = true;

		private $db;

		public function __construct($info) 
		{
			
			global $db;

			

			$this->db = $db;
			
			if(gettypei($info)=='int') {
				
				$this->studentID = (int)$info;

				$info = $this->retrieve_info();
				
				$this->intiliaze_this($info[0]);

			}

			else if(gettypei($info)=='obj') 
				$this->intiliaze_this($info);

			else if( gettypei($info) == 'arr')
				$this->int_this_arr($info);
			

		}


		//retrieve every information about the student
		private function retrieve_info() 
		{

			$query = "SELECT * FROM " . TABLE_STUD . " WHERE studID = $this->studentID LIMIT 1";

			$this->db->prp_stmt($query);
			
			

			if ( $this->db->get_num_rows() != 1){
				
				$this->exist = false;
				return null;
			}

			$resultSet = $this->db->get_resultset();
			
			return $resultSet;
		}
		
		public function getFName(){
			return $this->firstName;
		}

		public function getMName(){
			return $this->middleName;
		}
		
		public function getLName(){
			return $this->lastName;
		}
		//get the full name of the user
		public function getFullName() 
		{
			
			if($this->studentID == PET_ANONY)
				return PET_ANONY_HANDLER;
			
			$fullName = ucwords($this->firstName . " " . $this->middleName . " ". $this->lastName);

			return $fullName;

		}

		//get the role of the user
		public function getRole() {

			return $this->role;
		}


		public function getDepartment() {
			return $this->department;
		}

		//get the users ID
		public function getstudentID(){

			return $this->studentID;
		}

		public function getSchool() {
		//	return $this->school;
		}

		public function getYear(){
			return $this->year;
		}

		public function getSemester(){
			return $this->semester;
		}

		public function getSection(){
			return $this->section;
		}
		//check if the specified student exist
		public function exist() {
			return $this->exist;
		}

		public function blocked()
		{
			return $this->status == STUD_BLOCK;
		}

		//check if the user is logged in
		public function loggedIn(){

		}

		private function intiliaze_this($info) 
		{
			
			if(gettypei($info)!='obj')
				return false;

			$this->firstName  = $info->fname;
			$this->middleName = $info->mname;
			$this->lastName   = $info->lname;
			
			$this->studentID  = $info->studID;
			$this->ID 		  = $info->ID;
			$this->email 	  = $info->email;
			$this->department = $info->department;
			//$this->school     = $info->school;
			$this->year       = $info->year;
			$this->semester   = $info->semester;
			$this->section    = $info->section;
			$this->status 	  = $info->status;


		}

		private function int_this_arr($info) 
		{
			
			if( gettypei($info) != 'arr' )
				return false;

			$this->firstName  = $info['fname'];
			$this->middleName = $info['mname'];
			$this->lastName   = $info['lname'];
			
			$this->ID 		  = $info['ID'];
			$this->email 	  = $info['email'];
			$this->department = $info['department'];
			//$this->school     = $info['school'];
			$this->year       = $info['year'];
			$this->semester   = $info['semester'];



		}

		public function storeConfMsg($studID, $token)
		{
			
			$query = "INSERT INTO " . TABLE_REGCONF . " (studID,token) VALUES ($studID, '$token')";
			$this->db->insert_stmt($query);

			return $this->get_affected_rows() > 0;
		}

		public function register($email, $password) 
		{
			$password = Authenticator::encryptAlgo($password);
			global $sendemail;

			if ( !($studID = $this->checkEmailExist($email) ) )
				return RTRN_UE;//unknown email
			
			$token = createToken();

			$query = "UPDATE " . TABLE_STUD . " SET password = '$password' WHERE studID = $studID and status =" .  STUD_UNREG;
			
			$this->db->insert_stmt($query);

			if($this->db->get_affected_rows() < 1 ) 
				return RTRN_AR;//already registered 

			if (!self::storeConfMsg($studID,$token))
				return false;

			$message = "Please click <a href='" . PAGE_LOGIN . "?activate=$token&sid=$studID'> here </a> to activate your account";

			if ($sendemail->sendMail($this->to,$bodyContent))
				return true;

			return false;

		}

		public function activateAccount($token, $studID) 
		{
			$studID = (int)$studID;;
			$query = "UPDATE " . TABLE_STUD . " set status=" . STUD_REG . " WHERE status=" . STUD_UNREG . " and studID=$studID";
			if (!$this->db->insert_stmt($query))
				return false;

			$query =  "DELETE FROM " . TABLE_REGCONF . " WHERE token='$token' and studID=$studID";
			
			$this->db->insert_stmt($query);
			
			if ($this->get_affected_rows() < 1)
				return false;

			
			return true;

		}

		//creates petition
		public function create_petition($title, $description, $to, $tags,$audc, $fileName=null, $anony=false) {
			
			$petition = array(
									'title' 	 	=> $title,
									'description' 	=> $description,
									'date'			=> date('Y-m-d h:i:s'),
									'to'			=> $to,
									'sent'			=> PET_UNRESOLVED,
									'imageurl'		=> $fileName,
									'audience'		=> $audc

							);
			
			

			$petition['owner'] = ($anony) ? PET_ANONY : $this->studentID;

			$pet = new petition($petition);
			$pet->create();
			$pet->addTag($tags); 

		}

		//list all petitions created by this student
		public function myPetitions($num,$audc) {
		
			//audiences
			$audiences = formatForQuery($audc,false);

			$num = (int)$num;

			$num = ($num<1) ? 0 : $num-1;

			$query = "SELECT * FROM " . TABLE_PET . " WHERE owner=$this->studentID and audience IN $audiences ORDER by post_date DESC LIMIT " . $num*PETPERP .",".PETPERP;
			$this->db->prp_stmt($query);
			
			$results = array();

			foreach($this->db->get_resultset() as $result) 
				$results[] = new Petition($result);

			return $results;
			
		}
		
		//list all notifications
		public function myNotifications() 
		{
			$query = "(SELECT petitionID as onID,'v' as ntype,count(1) as num,date as dt FROM " . TABLE_VOTE . " WHERE seen=" . PET_UNRESOLVED . " and   petitionID IN (SELECT petID FROM " . TABLE_PET . " WHERE owner=$this->studentID) GROUP by petitionID) UNION ALL (SELECT petitionID as onID,'c' as ntype,count(1) as num,date as dt FROM " . TABLE_COM . " WHERE seen=" . PET_UNRESOLVED . " and petitionID IN (SELECT petID FROM " . TABLE_PET ." WHERE owner=$this->studentID and getNotifcation=" .PET_NOTF_ON . ") GROUP BY petitionID) ORDER BY dt DESC" ;

			

			$this->db->prp_stmt($query);
			

			$result = $this->db->get_resultset();

			$notfs = array();
			foreach($result as $notf) {
				
				$notfs[] = new Notification($notf);
			}

			return $notfs;
		}


		//list 5 recent activities
		public function recentActivities() 
		{

			$query = "SELECT commentID as actID,petitionID as onID,date,'c' as type FROM " . TABLE_COM. " WHERE commenter=$this->studentID UNION SELECT voteID,petitionID,date,'v' as type FROM " . TABLE_VOTE . "  WHERE voter=$this->studentID UNION SELECT petID,petID,post_date,'p' as type FROM " . TABLE_PET . " WHERE owner = $this->studentID ORDER by date DESC LIMIT " . RECACT;

			$this->db->prp_stmt($query);

			$results = array();

			foreach($this->db->get_resultset() as $result) 
				$results[] = new Activity($result);

			return $results;
		}

		//comment on petition
		public function comment($petitionID, $message) {

			$comment = array(
								'message' 		=> $message,
								'commenter'		=> $this->studentID,
								'petitionID'	=> $petitionID,
								'date'			=> date('Y-m-d h:i:s'),
								'seen'			=> COMMENT_UNSEEN
							);
			
			$petition = new Petition($petitionID);
			$cmt = new Comment($comment);

			$petition->addComment($cmt);

		}

		//vote on petition
		public function vote($petitionID , $type) {

			$vote = array(
								'voter'			=> $this->studentID,
								'type'			=> $type,
								'date'			=> date('Y-m-d h:i:s'),
								'petitionID'	=> $petitionID
							);

			$vt = new Vote($vote);
			$petition = new Petition($petitionID);
			$petition->castVote($vt);

		}



		public function searchPetition($keyword,$audc) 
		{
			$audiences = formatForQuery($audc,false);

			$query = "SELECT * FROM " . TABLE_PET . " WHERE title LIKE '%$keyword%' and audience IN $audiences order by post_date desc";
			
			$this->db->prp_stmt($query);
			
			$resultSet = $this->db->get_resultset();
			
			$petitions = array();

			foreach($resultSet as $result) 
				$petitions[] = new Petition($result);

			return $petitions;


		}



		public function changePassword($oldPass, $newPass)
		{
			$password = Authenticator::encryptAlgo($newPass);

			//check if the old password is valid
			if ( !Authenticator::checkOldPassword($oldPass))
				return false;

			$query = "UPDATE " . TABLE_STUD . " SET password='$password'";

			$this->db->insert_stmt($query); 

			if ($this->db->get_affected_rows()>0)
				return true;

			return false;

		}

		//last changed information
		private function lastChanged($infoType)
		{
			$studID   = $this->studentID;
			$infoType = (int)$infoType;
			$now = date('Y-m-d');
			
			$query = "SELECT DATEDIFF('$now',last_changed) as date_int FROM "  . TABLE_LAST_MODF . " WHERE studID = $this->studentID and infoType = $infoType LIMIT 1";
			
			$this->db->prp_stmt($query);

			if ($this->db->get_num_rows()==0)
				return null;
			
			

			return ($this->db->get_resultset()[0]->date_int);
			

		}

		private function chLastChanged($infoType)
		{

			$infoType = (int)$infoType;

			$query = "INSERT INTO " . TABLE_LAST_MODF . " (studID,infoType,last_changed) VALUES ($this->studentID,$infoType,NOW()) ON DUPLICATE KEY UPDATE last_changed = NOW()";
			
			$this->db->insert_stmt($query);

			return $this->db->get_affected_rows()>0;

		}
		
		public function changeName($fname,$mname,$lname)
		{
			
			$fullName = $fname . " " . $mname. " " . $lname;

			if( percSim($fullName,$this->getFullName()) <= 80)
				return SET_BPOSC;

			$lastModf = $this->lastChanged(SET_NAME);

			//check if it is semester old
			if ($lastModf!==null and $lastModf<NUM_DAY_SEM)
				return SET_NOLDEN;
			
			if (!$this->chLastChanged(SET_NAME))
				return false;

			$query = "UPDATE " . TABLE_STUD . " set fname='$fname',mname='$mname',lname='$lname' WHERE studID = $this->studentID";

			$this->db->insert_stmt($query);

			return $this->db->get_affected_rows() > 0;


		}

		//change either name,section or year
		public function change($type,$data)
		{
			

			switch ($type) 
			{
				case SET_SEC:
					$type = SET_SEC;
					$column = "section";
					break;
				case SET_YEAR:
					$type = SET_YEAR;
					$column = "year";
					break;
				case SET_NAME:
					$type = SET_NAME;
					break;
				default:
					return false;
					break;
			}

			$lastModf = $this->lastChanged($type);

			//check if it is semester old
			if ($lastModf!==null and $lastModf<NUM_DAY_SEM)
				return SET_NOLDEN;
			
			
			if (!$this->chLastChanged($type))
				return false;
			

			$query = "UPDATE " . TABLE_STUD . " SET $column=$data WHERE studID=$this->studentID";
			$this->db->insert_stmt($query);


			return $this->db->get_affected_rows() > 0;


		}

		public function forgotPass()
		{
			if (!$this->exist())
				return false;
			
			$token = createToken();

			$query = "INSERT INTO " . TABLE_FORGOTPASS . " (studID,token) VALUES ($this->studentID, '$token')
			ON DUPLICATE KEY UPDATE token= '$token'";
			
			if (!$this->db->insert_stmt($query))
				return false;
			
			return $token;
		}

		public function deleteToken($token)
		{
			$query = "DELETE FROM " . TABLE_FORGOTPASS . " WHERE token ='$token'";

			if(!$this->db->insert_stmt($query))
				return false;

			if ($this->db->get_num_rows() < 1)
				return false;

			return $this->db->get_resultset()[0]->studID;

		}

		public static function getStudToken($token) 
		{
			global $db;

			$query = "SELECT studID FROM " . TABLE_FORGOTPASS . " WHERE token='$token'"; 

			if(!$db->prp_stmt($query))
				return false;

			if ($db->get_num_rows() < 1)
				return false;

			return $db->get_resultset()[0]->studID;
			
		}
		

		//change password. Asks for old password
		public function changePassOld($newPass,$oldPass) 
		{

			$newPass = Authenticator::encryptAlgo($newPass);
			$oldPass = Authenticator::encryptAlgo($oldPass);
			
			$query = "UPDATE "  . TABLE_STUD . " SET password = '$newPass' WHERE studID = $this->studentID and password='$oldPass'";
			
			if(!$this->db->insert_stmt($query))
				return false;


			return $this->db->get_affected_rows() > 0;

		}


		//change password : forgot password
		public function changePass($newPass,$token) 
		{

			$newPass = Authenticator::encryptAlgo($newPass);
			
			$query = "UPDATE "  . TABLE_STUD . " SET password = '$newPass' WHERE studID = $this->studentID";
			
			$this->deleteToken($token);
			
			if(!$this->db->insert_stmt($query))
				return false;

			return $this->db->get_affected_rows() > 0;

		}
		

		//check if the provided email exist
		public static function checkEmailExist($email) 
		{
			global $db;

			$query = "SELECT studID FROM " . TABLE_STUD . " WHERE email='$email'";
			
			if(!$db->prp_stmt($query))
				return false;

			if($db->get_num_rows()<1)
				return false;

			return $db->get_resultset()[0]->studID;

		}

		public function __call($method,$args)
		{
			$posMethods = "/^change(Sec|Year){1}$/";

			if ( preg_match($posMethods, $method,$result) )
			{
				return $this->change(strtolower($type),$args[0]);
			}
		}	

	}


?>