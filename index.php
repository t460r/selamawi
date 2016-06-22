<?php 
	
	require_once 'includes/required.php';
	require_once 'includes/ui/loginui.php';
	require_once 'includes/class/classStudent.php';


	$loginui = new Loginui();
		Authenticator::start_session();
	
	$feedback = "Please login";
	$feedback = "Please register";

	//list of parameters required to register
	$reg_params = array('reg_email','reg_pass','reg_pass_conf'); 
	$act_params = array('act', 'sid');

	$activate = valid_form($_GET,$act_params);
	$register   = valid_form($_POST, $reg_params);


	if(  valid_form( $_POST,array('log_username','log_password') ) ) 
	{

		$auth   = new Authenticator($_POST['log_username'], $_POST['log_password']);

		if( $auth->authenticate() )
			redirect(PAGE_ACCT);

		else
			redirect(PAGE_LOGIN);
	}

	else if($register)
	{

		if ( $_POST['reg_pass'] != $_POST['reg_pass_conf'])
			$feedback="Password doesnt match";
		else
		{
			$pass = Authenticator::encryptAlgo($_POST['reg_pass']);

			$student = new Student(0);
			
			$reg = $student->register($_POST['reg_email'], $_POST['reg_pass']); 

			switch ($reg) 
			{
				case RTRN_UE:
					$feedback =  "Unknow email";
					break;
				case RTRN_AR:
					$feedback = "A student has already registered with email. Only one email per account is allowed.";
					break;
				
				default:
					$feedback = "Failed to register";
					break;
			}

		}

		storeFeedback(MSG_REG,$feedback, MSG_ERR);
		redirect(PAGE_LOGIN);


	}

	else if( $activate)
	{
		$student = new Student(0);
		if($student->activateAccount($_GET['act'], $_GET['sid']))
			storeFeedback(MSG_REG,"You have succesfully activated your accoutn. Plese login to your account", MSG_SUC);
		else
			storeFeedback(MSG_REG,"Unable to activate your account", MSG_ERR);
	}

	$loginui->loginPage($feedback);

	

?>