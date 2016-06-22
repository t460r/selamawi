<?php 
	
	class Notification
	{

		private $type;
		private $num;
		private $onID;

		private  $verbs = array(
										"v" => 'votes',
										"c" => 'Commentes'
									);

		public function __construct($info) 
		{
			if( gettypei($info)!='obj')
				return;

			$this->type    = $info->ntype;
			$this->num  = $info->num;
			$this->onID    = $info->onID;
		}

		public function getType() 
		{
			return $this->type;
		}

		public function getVerb() 
		{
			return $this->verbs[$this->type];
		}

		public function getNum() 
		{
			return $this->num;
		}

		public function getOnID(){
			return $this->onID;
		}

	}

?>