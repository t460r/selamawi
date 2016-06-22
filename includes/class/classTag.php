<?php 
	
	class Tag{
		private $tagName;
		private $tagID;
		private $exist = true;
		
		public function __construct($info)
		{

			if ( gettypei($info) == 'int')
			{
				$this->tagID = $id;
				$this->int_this();
			}

			else if( gettypei($info) == 'obj'){
				$this->int_this_obj($info);
			}

			
		}

		public function int_this_obj($info)
		{

			$this->tagName = $info->tagName;
			$this->tagID   = $info->tagID;
		}

		public function int_this()
		{
			$query = "SELECT * FROM ". TABLE_TAG ." WHERE tagID = $this->tagID LIMIT 1";
			$this->db->prp_stmt($query);

			if($this->db->get_num_rows()!=1)
			{
				$this->exist = false;
				return;
			}

			$this->tagName = $this->dg->get_resutlset()[0]->tagName;

		}

		public function getName()
		{
			return $this->tagName;
		}

		public function getID()
		{
			return $this->tagID;
		}

		//check if this tag exist
		public function existTag(){
			return $this->exist;
		}

		//check if multiple tags exist
		public static function tagsExist($tags) 
		{
			global $db;

			$formated = formatForQuery($tags);
			
			$query = "SELECT count(1) as num FROM " . TABLE_TAG . " WHERE tagID IN $formated";

			$db->prp_stmt($query);

			if ($db->get_num_rows() !=1)
				return false;

			return $db->get_resultset()[0]->num == count($tags);

		}




	}

?>