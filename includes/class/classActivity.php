<?php 
	
	class Activity {

		private $type;
		private $onID;
		private $actID;
		private $set = false;

		private $types = array(
								
								'c' => 'Comment',
								'v' => 'Vote',
								'p' => 'Petition'
							);

		private $verbs = array(
								'c' => 'Commented',
								'v' => 'voted',
								'p' => 'Created'
							);

		public function __construct($info) {
			
			if ( gettypei($info)!='obj')
				return;

			$this->int_this($info);

		}

		private function int_this($info) 
		{
			
			$this->set = true;

			$this->actID = $info->actID;
			$this->onID  = $info->onID;
			$this->type  = $info->type;
			$this->date  = $info->date;

		}

		public function getVerb() {
			return $this->verbs[$this->type];
		}

		public function getType() {
			return $this->types[$this->type];
		}

		public function getOnID(){
			return $this->onID;
		}

	}

?>