<?php 
	
	class Comment{

		private $commentID;
		private $message;
		private $date;
		private $petitionID;
		private $seen;
		private $commenter;
		private $db;


		public function __construct($info) {
			global $db;
			
			$this->db = $db;

			if(gettypei($info)=='obj')
				$this->intitialize_this($info);
			if(gettypei($info)=='arr') 
				$this->intitialize_this_arr($info);

		}

		public function getMessage() {
			return $this->message;
		}
		public function getDate() {
			return $this->date;
		}

		public function getCommenter() {
			
			return new Student((int)$this->commenter);
		}
		
		public function getPetition() {}

		//intitiliaze the attribute of the comment from object
		public function intitialize_this($info) {

			$this->commentID 	= $info->petitionID;
			$this->message 		= $info->message;
			$this->date    		= $info->date;
			$this->petitionID   = $info->petitionID;
			$this->seen 		= $info->seen;
			$this->commenter	= $info->commenter;
 		}

		//intitiliaze the attribute of the comment from array
		public function intitialize_this_arr($info) {

			$this->commentID 	= $info['petitionID'];
			$this->message 		= $info['message'];
			$this->date    		= $info['date'];
			$this->petitionID   = $info['petitionID'];
			$this->seen 		= $info['seen'];
			$this->commenter    = $info['commenter'];
		}

		//save this comment
		public function create($resolved = PET_UNRESOLVED) 
		{
			
			$query = "UPDATE " . TABLE_PET . " SET num_comments=num_comments+1 WHERE petID = $this->petitionID";
			
			$this->db->insert_stmt($query);

			if ($this->db->get_affected_rows()<1)
				return;

			$query = "INSERT INTO " . TABLE_COM . " (message,commenter,petitionID,date,seen) 
													VALUES
			('$this->message', $this->commenter, $this->petitionID, '$this->date',$resolved )";

			$this->db->insert_stmt($query);

			if($this->db->get_num_rows() > 0)
				return true;

			return false;
		}



	}

?>