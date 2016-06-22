<?php 
class Vote{
	
	private $voteID;
	private $type;
	private $voter;
	private $petitionID;
	private $date;
	private $db;
	public function __construct($info) {
		
		global $db;
		
		$this->db = $db;

		if ( gettypei($info) == 'arr' )
			$this->initiliaze_this_arr($info);
		else if (gettypei($info) == 'obj')
			$this->initiliaze_this($info);
	}

	//initlitiaze the attributes from object
	public function initiliaze_this($info)  {
		if ( gettypei($info) != 'obj')
			return false;


		$this->voteID      = $info->voteID;
		$this->type        = $info->type;
		$this->voter       = $info->voter;
		$this->petitionID  = $info->petitionID;
		$this->date 	   = $info->date;

		return true;

	}


	public function initiliaze_this_arr($info) {
		
		if ( gettypei($info) != 'arr') 
			return false;


		$this->type 		= $info['type'];
		$this->date 		= $info['date'];
		$this->voter 		= $info['voter'];
		$this->petitionID   = $info['petitionID'];
	}

	public function get_type() {
		return $this->type;
	}

	//check if already voted
	private function alreadyVoted() 
	{

		$query = "SELECT * FROM " . TABLE_VOTE . " WHERE voter = $this->voter and petitionID = $this->petitionID LIMIT 1";

		$this->db->prp_stmt($query);

		if( $this->db->get_num_rows() == 0 )
			return VOTE_NONE;

		//if student voted
		return $this->db->get_resultset()[0];
		
	}

	//create or update the vote
	public function create($resolved = PET_UNRESOLVED) {

		$alreadyVoted = $this->alreadyVoted();

		//if not voted already
		if ( gettypei($alreadyVoted)=='int' and $alreadyVoted == VOTE_NONE)
			$this->store($resolved);

		else if($alreadyVoted->type != $this->type)
			$this->update();
		else 
			return false;

		return true;

	}

	//store this vote
	public function store($resolved=PET_UNRESOLVED) {
		
		$column = ($this->type==VOTE_UP) ? "num_up_votes" : "num_down_votes";

		$query = "UPDATE " . TABLE_PET . " SET $column=$column+1 WHERE petID = $this->petitionID";
		
			
		$this->db->insert_stmt($query);

		$query = "INSERT INTO " . TABLE_VOTE . " (voter, petitionID, type, date,seen) 
													VALUES 
				($this->voter, $this->petitionID, $this->type, '$this->date',$resolved)";

				
		$this->db->insert_stmt($query);

		if(	$this->db->get_affected_rows() > 1)
			return true;

		return false;
	}



	//update info for this vote
	public function update() {

		$query = "UPDATE " . TABLE_VOTE . " SET type = $this->type WHERE petitionID = $this->petitionID and voter = $this->voter";

		
		$this->db->insert_stmt($query);

		if(	$this->db->get_affected_rows() > 1)
			return true;

		return false;


	}

	public function delete_me() {

	}

}

?>