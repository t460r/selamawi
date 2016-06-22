<?php 
	class Scdep{

		public static $departments = array(

									1 => 'Software Engineering',
									2 => 'Mechanical Enginnering',
									3 => 'Bio medical Engineering',
									4 => 'Civil Engineering',
									5 => 'Computer and Electrical Engineering',
									6 => 'Chemical Engineering',
									7 => 'Information Technology (IT)'

			);

		private static $schools = array(
							0 => 'Center of Information and Technology',
							1 => 'Electrical and Computer Engineering school',
							2 => 'Civil and Water Engineering school',
							3 => 'Mechanical and Industrial Engineering school'
			);


		private static $mapping = array(

										0 => array(1),
										1 => array(5),
										2 => array(4),
										3 => array(2)
			
									);

		public static $semMap = array(
											1 => 'First (I)',
											2 => 'Second (II)',
											3 => 'Third (III)',
											4 => 'Fourth (IV)',
											5 => 'Fifth (V)',
											6 => 'Sixth (VI)'
									);


		public static function getDepName($depID) 
		{
			$depID = (int)$depID;
			//no department found
			if ( !($depID < count(self::$departments) and $depID>0 ) )
				return UNDEF;

			return self::$departments[$depID];
		}

		//maps department with its corresponding school
		public static function mapDepSch($depID) 
		{
			$counter = 0;
			foreach(self::$mapping as $deps){
				
				if( (array_search($depID, $deps))!==false )
					return self::$schools[$counter];
				$counter++;

			}

			return UNDEF;

		}

		public static function mapDepSchID($depID) 
		{
			$counter = 0;
			foreach(self::$mapping as $deps){
				
				if( (array_search($depID, $deps))!==false )
					return $counter;
				$counter++;

			}

			return false;
		}
		public static function getSem($sem) 
		{
			$sem = (int)$sem;
			
			//no department found
			if ( !($sem < count(self::$semMap) and $sem>0 ) )
				return UNDEF;

			return self::$semMap[$sem];
		}

	}
	
?>