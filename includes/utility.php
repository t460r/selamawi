<?php 
	
	function redirect($url) {
		Header("location:$url");
		exit();
	}

	function valid_form($info,$elems) {
		foreach($elems as $elem) {
			
			if(!isset($info[$elem]))
				return false;

		}

		return true;
	}

	//get type of the specified variable

	function gettypei($var) {

		$type = gettype($var);
		
		switch ($type) {
			case 'object':
				$type = 'obj';
				break;
			case 'integer':
				$type = 'int';
				break;
			case 'array':
				$type = 'arr';
				break;
			default:
				return null;
				break;
		}

		return $type;

	}

	//prevent cross site scripting
	function crossSS(&$data){
		$data = htmlspecialchars($data);
	}

	
	function formatForQuery($tags,$int=true)
	{

			if ( gettypei($tags) !='arr')
				return "";

			$formated = "";
			
			//format the tag for query
			foreach($tags as $tagID)
			{
				$tagID = ($int) ? (int)$tagID : "'$tagID'";
				$formated .= $tagID .",";	

			}

			$formated = "(". substr($formated,0,strlen($formated)-1) .")";

			return $formated;
	}


	function createToken() {
		$curtime = time();
		$rand = mt_rand(1000,1000000);

		$token = $curtime.$rand;

		return md5($token);
	}

	function storeFeedback($name,$feedback, $type)
	{
		$_SESSION['feedback'] = array(
										'name' => $name,
										'message' => $feedback,
										'type'  => $type
									);

	}

	function retrieveFeedbackInfo($name)
	{
		if(!isset($_SESSION['feedback']))
			return false;
		$feedback = $_SESSION['feedback'];
		
		if ( isset($feedback['name']) and $feedback['name'] == $name ){
			
			$msg = array(
							'message' => $feedback['message'],
							'type' => $feedback['type']
				);
			$_SESSION['feedback'] = array();
			unset($_SESSION['feedback']);
			return $msg;
		}
	

		return false;
	}

	function percSim($first, $second)
	{		

		similar_text($first, $second,$percent);
		return $percent;
	}

?>