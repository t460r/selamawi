<?php 
	
	class Petition{

		private $to;
		private $petitionID;
		private $title;
		private $description;
		private $date;
		private $sent;
		private $owner;
		private $numComments = false;
		private $numVotes = false;
		private $imageUrl = null;	
		private $getNotf;
		private $audience;
		private $status;

		private $exist = true;

		//object to store all information about the owner of this petition
		private $owner_obj;

		private $db;

	
		function __construct($info)
		{

			global $db;

			$this->db = $db;

			if(gettypei($info)=='int') {
				
				$this->petitionID = (int)$info;
				$this->retrieve_info();

			}

			else if(gettypei($info)=='obj') 
				$this->intiliaze_this($info);

			else if(gettypei($info)=='arr')
				$this->intiliaze_this_arr($info);

		}

		//get all comments for this petition
		public function getComments(){

			$query = "SELECT * FROM " . TABLE_COM . " WHERE petitionID = $this->petitionID ORDER BY date DESC";


			$this->db->prp_stmt($query);

			$this->numComments = $this->db->get_num_rows();

			if( $this->db->get_num_rows()<1)
				return array();

			$result =  $this->db->get_resultset();
			$comments = array();
			foreach($result as $comment) {
				$comments[] = new Comment($comment);
			}

			return $comments;
		}	

		//return the number of comments
		public function getNumComments() {
			
			if($this->numComments != false) 
				return $this->numComments;

			$query = "SELECT count(commentID) as num  from " . TABLE_COM . " WHERE petitionID = $this->petitionID";

			$this->db->prp_stmt($query);

			return $this->db->get_resultset()[0]->num;
		}

		//get all votes for this petition
		public function getVotes(){

			$query = "SELECT * FROM " . TABL_VOTE . " WHERE petitionID = $this->petitionID";

			$this->db->prp_stmt($query);

			

			$vote_count = array(
								'UP' 		=> 0,
								'DOWN'		=> 0
							);
			$resultset = $this->dg->get_resultset();
			$votes = array();
			$i = 0;

			foreach($resultset as $result) {
				
				$votes[$i] = new Vote($result);
				
				if( $votes[$i]->get_type() == VOTE_UP )
					$vote_count['UP']   +=1;
				else
					$vote_count['DOWN'] +=1;

			}

			$this->numVotes = $vote_count;
			return $votes;

		}

		public function removeComNotf(){

			$query = "UPDATE " . TABLE_COM . " SET seen =" . PET_RESOLVED . " WHERE petitionID = " . $this->petitionID;
			
			$this->db->insert_stmt($query); 

		}

		public function removeVotNotf(){
			$query = "UPDATE " . TABLE_VOTE . " SET seen =" . PET_RESOLVED . " WHERE petitionID = " . $this->petitionID;
			
			$this->db->insert_stmt($query); 

		}

		function assignVote($votes,&$vote_count) {

			if(count($votes)==0)
				return $vote_count;

			if(gettypei($votes[0])!='obj')
				return $vote_count;

			

			if($votes[0]->type==VOTE_UP)
				$vote_count['UP'] = $votes[0]->num;
			else
				$vote_count['DOWN'] = $votes[0]->num;

			if(!isset($vote[1]))
				return $vote_count;

			if($votes[1]->type==VOTE_DOWN)
				$vote_count['DOWN'] = $votes[1]->num;
			else if($votes[1]->type==VOTE_UP)
				$vote_count['UP'] = $voes[1]->num;

			return $vote_count;

		}

		public function getOwner(){
			return $this->owner;
		}

		//return the number of votes
		public function getNumVotes() 
		{

			if( gettypei($this->numVotes)=='arr')
				return $this->numComments;

			$query = "SELECT count(voteID) as num,type FROM " . TABLE_VOTE . " WHERE petitionID = $this->petitionID group by type";

			$this->db->prp_stmt($query);

			
			$vote_count = array(
								'UP' 		=> 0,
								'DOWN'		=> 0
							);
			$rs = $this->db->get_resultset();
			$this->assignVote($rs,$vote_count);
			return $vote_count;

		}


		public function getTags()
		{
			$subQ = "SELECT tagName FROM " . TABLE_TAG . " WHERE tagID = " . TABLE_PET_TAG .".tagID";

			$query = "SELECT tagID,($subQ) as tagName FROM " . TABLE_PET_TAG . " WHERE petID = $this->petitionID";
			
			$this->db->prp_stmt($query);

			$tags = array();
			$result = $this->db->get_resultset();

			foreach($result as $tag)
				$tags[] = new Tag($tag);

			return $tags;


		}

		//add comment to the petition
		public function addComment($comment) 
		{
			
			
			$resolved = ($this->getNotf == PET_NOTF_ON ) ? PET_UNRESOLVED : PET_RESOLVED;
			
			return $comment->create($resolved);
			
		}

		

		//cast vote in this post
		public function castVote($vote) 
		{
			$resolved = ($this->getNotf == PET_NOTF_ON ) ? PET_UNRESOLVED : PET_RESOLVED;	
			return $vote->create($resolved);

		}

		//report petition 

		public function report()
		{
			$query = "UPDATE " . TABLE_PET . " SET status=" . PET_ST_RPT . " WHERE petID=" . $this->petitionID;
			$this->db->insert_stmt($query);
		}


		public function getTitle() 
		{
			return $this->title;
		}

		public function getStatus() 
		{
			return $this->status;
		}

		public function isReported()
		{

			return (int)$this->status === PET_ST_RPT;
		}

		public function isDeleted() 
		{
			return $this->status == PET_ST_DEL;
		}

		public function getDesc() 
		{
			return $this->description;
		}

		public function getDate() 
		{

			return $this->date;
		}

		public 	function getPetID()
		{
			return $this->petitionID;
		}

		//initiliaze the attributes
		public function intiliaze_this($info) 
		{
			
			if ( gettypei($info) !='obj')
				return false;

			$this->title = 			$info->title;
			$this->description =	$info->description;
			$this->date = 			$info->post_date;
			$this->sent = 			$info->sent;
			$this->to   = 			$info->send_to;
			$this->owner = 			$info->owner;
			$this->petitionID =     $info->petID;
			$this->imageUrl  =      $info->image_url;
			$this->getNotf   = 		$info->getNotifcation;
			$this->audience = 		$info->audience;
			$this->status = 		$info->status;

			$this->get_owner_info();
		}

		//intitialize the attributes  from array
		public function intiliaze_this_arr($info) {
			if ( gettypei($info) !='arr')
				return false;

			
			$this->title = 			$info['title'];
			$this->description =	$info['description'];
			$this->date = 			$info['date'];
			$this->sent = 			$info['sent'];
			$this->to   = 			$info['to'];
			$this->owner = 			$info['owner'];
			$this->imageUrl = 		(isset($info['imageurl'])) ? $info['imageurl'] : NULL;
			$this->audience  =     $info['audience'];

			$this->get_owner_info();


		}

		//check if notification recieve mode is on
		public function notfOn(){
			return $this->getNotf == PET_NOTF_ON;
		}

		//is sent
		public function isSent() 
		{
			return $this->sent;
		}

		//deliver this message
		public function deliverMessage($audc) 
		{
			global $sendemail;
			
			$mak = '';

			if( $audc['class'] == $this->audience)
				$mak = PET_NUM_CV;

			else if( $audc['dept'] == $this->audience )
				$mak = PET_NUM_DV;

			else if( $audc['year'] == $this->audience )
				$mak = PET_NUM_YV;

			else if( $audc['all'] == $this->audience )
				$mak = PET_NUM_AV;
			else
				$mak = 1500;


			if ($mak < $this->getVotes())
				return false;

			$bodyContent = $this->title;
			$bodyContent .= "<br><br>";
			$bodyContent .= $this->description;

			if ( $sendemail->sendMail($this->to,$bodyContent) )
				return true;

			$query = "UPDATE " . TABLE_PET . " SET sent=" . PET_RESOLVED . " WHERE petID=$this->petitionID";
			
			return $this->db->insert_stmt($query);


		}

		public function chNotfSt()
		{
			$notfSt = $this->getNotf;
			$notfSt = ($notfSt == PET_NOTF_ON) ? PET_NOTF_OFF : PET_NOTF_ON;

			$query = "UPDATE " . TABLE_PET . " SET getNotifcation =$notfSt WHERE petID = $this->petitionID";
			
			$this->db->insert_stmt($query);
		}
		//retrieve informatoin about the owner
		public function get_owner_info() {
			$this->owner_obj = new Student((int)$this->owner);
			
		}

		//get url for image
		public function getImageURL(){
			return $this->imageUrl;
		}

		//retrieve information about the owner
		public function __call($method, $arguments) {
			
			switch ($method) {
				case 'getFullName':
					return $this->owner_obj->getFullName();
					break;
				case 'getStudID':
					return $this->owner_obj->getstudentID();
					break;
				default:
					# code...
					break;
			}
			
			
		}

		//lists the top related petitions
		public function relatedPetitions() 
		{
			$query = "SELECT *  FROM " . TABLE_PET .  " JOIN (SELECT petID from " . TABLE_PET_TAG .  " where tagID IN (SELECT tagID FROM " . TABLE_PET_TAG .  " WHERE petID=$this->petitionID) group by petID HAVING petID<>$this->petitionID
ORDER BY count(1) DESC LIMIT 4) as matches ON " . TABLE_PET .  ".petID=matches.petID";

			$this->db->prp_stmt($query);

			$petitions = array();

			$results = $this->db->get_resultset();

			foreach($results as $petition) 
			
				$petitions[] = new Petition($petition);

			return $petitions;

		}

		//create this petition
		public function create() 
		{
			

			$query  = "INSERT INTO " . TABLE_PET . " (title, description, post_date, send_to, owner,image_url,audience,sent) 
													VALUES 
													('$this->title', '$this->description', '$this->date', '$this->to', $this->owner, '$this->imageUrl' ,'$this->audience', " .PET_UNRESOLVED . ")
			";

			print($query);
			$this->db->insert_stmt($query);

			$this->petitionID = $this->db->get_lastID();
			
			return $this->db->get_affected_rows() > 0;

		}

		//retrieve information about the petition 
		public function retrieve_info() 
		{

			$query = "SELECT * FROM " . TABLE_PET . " WHERE petID=$this->petitionID LIMIT 1";
			$stmt = $this->db->prp_stmt($query);
			
			if($this->db->get_num_rows() < 1)
				$this->exist = false;

			$rslt = $this->db->get_resultset()[0];

			if($rslt!=null and $stmt!=false)
				$this->intiliaze_this($rslt);

		}

		public function getAudience()
		{

			return $this->audience;

		}

		public function exist() {
			return $this->exist;
		}

		public function hasVoted($studID)
		{
			$query = "SELECT type FROM " . TABLE_VOTE . " WHERE voter=$studID and petitionID=$this->petitionID";

			$this->db->prp_stmt($query);


			if ($this->db->get_num_rows()<1)
				return VOTE_NONE;

			return $this->db->get_resultset()[0]->type;
		}

		public function addTag($tags) 
		{
			if (!Tag::tagsExist($tags,$this->db))
				return;
			
			$query = "INSERT INTO " . TABLE_PET_TAG . " (tagID,petID) VALUES (?,?)";
			$stmt = $this->db->prepare($query);
			
			
			if (!$stmt)
				return;

			$tagID = 0;
			$stmt->bind_param('dd',$tagID,$this->petitionID);
			
			foreach ($tags as $tagID)
				$stmt->execute();

			
			return $this->db->get_affected_rows() > 0;

		}

		//ist all possible tags		
		public static function posTags()
		{
			global $db;

			$query = "SELECT * FROM " . TABLE_TAG . " WHERE 1";

			$db->prp_stmt($query);

			$tags = array();

			if ($db->get_num_rows() <1)
				return $tags;

			$results = $db->get_resultset();

			foreach ($results as $tag)
				$tags[] = new Tag($tag);

			return $tags;

		}

	}

?>