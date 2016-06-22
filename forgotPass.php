<?php 
	include 'includes/required.php';
	include 'includes/ui/loginui.php';
	include 'includes/class/classStudent.php';
	include 'includes/sendEmail.php';

	$feedback = "";

	if ( valid_form($_POST, array('email') ) )
	{

		if(! ($studID = student::checkEmailExist( addslashes( $_POST['email'] ) ) ) )
			echo $feedback = "No email found";
		else{
			
			$student = new Student((int)$studID);
			
			if($token = $student->forgotPass()) 
			{
				$body = "Please click <a href='http://localhost/selamawi/" . PAGE_FORGOTPASS . "?unToken=$token'> here </a> to gain access to your account";

				if($sendemail->sendMail( $_POST['email'] , $body ) )
					redirect("./" . PAGE_LOGIN);
				else
					redirect(PAGE_FORGOTPASS);
			}

		}


	}
	else if( valid_form ($_GET, array('unToken') )) 
	{
		
		//invalid token	
		if(!Student::getStudToken($_GET['unToken']))
			redirect(PAGE_LOGIN);
		
		$lgui->forgetPage($feedback,false,$_GET['unToken']);

	}

	else if( valid_form($_POST, array('forgt_password','forgt_confPassword','forgt_token') )) {
		$studID = Student::getStudToken($_POST['forgt_token']);

		if(!$studID or $_POST['forgt_password'] != $_POST['forgt_confPassword'])
			redirect(PAGE_FORGOTPASS."?unToken". $_POST['forgt_token']);

		$student = new Student((int)$studID);
		if ($student->changePass($_POST['forgt_password'], $_POST['forgt_token']) )
			redirect(PAGE_LOGIN);
		else
			redirect(PAGE_LOGIN);

	}
	
	else
		$lgui->forgetPage($feedback);



?>