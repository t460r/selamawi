<?php 
	include 'includes/required.php';
	include 'includes/ui/settingui.php';
	include 'includes/class/classStudent.php';
	
	Authenticator::start_session();

	if ( !Authenticator::authenticated() )
		redirect(PAGE_LOGIN);


	$student = new Student(Authenticator::get_session(STUDID));

	if (!$student->exist())
		redirect(PAGE_LOGIN);
	
	$fname = $student->getFName();
	$mname =$student->getMName();
	$lname =$student->getLName();

	//form elements
	$set_name = array('set_fname','set_mname','set_lname','set_cname');
	$set_pass = array('set_opassword','set_npassword','set_cpassword','set_cpass');
	$set_yr   = array('set_year','set_cyr');
	$set_sec  = array('set_sec','set_csec');

	//change name informatoin
	if ( valid_form($_POST, $set_name)) {
		
		$fname = trim($_POST['set_fname']);
		$mname = trim($_POST['set_mname']);
		$lname = trim($_POST['set_lname']);

		$changed = $student->changeName($fname,$mname,$lname);

		//if it is not old enough
		if ($changed === SET_NOLDEN){
			storeFeedback(MSG_SETTING,MES_ERR_NAME_NOE,MSG_ERR);
			redirect(PAGE_SETTING);
		}
		else if ($changed === SET_BPOSC)
		{
			storeFeedback(MSG_SETTING,MES_ERR_NAME_MANCH,MSG_ERR);
			redirect(PAGE_SETTING);
		}
		else if ($changed === true)
		{
			storeFeedback(MSG_SETTING,MES_SUC_NAME_CH,MSG_SUC);
			redirect(PAGE_SETTING);
		}
		else
		{
			storeFeedback(MSG_SETTING,MES_FCHANGE,MSG_ERR);
			redirect(PAGE_SETTING);
		}


	}
		
	//change password
	else if ( valid_form($_POST, $set_pass) ){

		if($_POST['set_npassword'] !== $_POST['set_cpassword']){
			storeFeedback(MSG_SETTING,MES_ERR_PAS_NM,MSG_ERR);
			redirect(PAGE_SETTING);
		}

		$changed = $student->changePassOld($_POST['set_npassword'], $_POST['set_opassword']);

		if (!$changed)
			storeFeedback(MSG_SETTING,MES_FCHANGE,MSG_ERR);
		else
			storeFeedback(MSG_SETTING,MES_SUC_PAS,MSG_SUC);
	}
		
	//change year
	else if ( valid_form($_POST, $set_yr)){
		$changed = $student->change(SET_YEAR,(int)$_POST['set_year']);
		
		//if it is not old enough
		if ($changed === SET_NOLDEN){
			storeFeedback(MSG_SETTING,MES_ERR_NOE,MSG_ERR);
			redirect(PAGE_SETTING);
		}
		else{
			storeFeedback(MSG_SETTING,MES_SUC_CHANGE,MSG_SUC);
			redirect(PAGE_SETTING);
		}

	}
	
	//change semester	
	else if ( valid_form($_POST, $set_sec)) {
		$changed = $student->change(SET_SEC,(int)$_POST['set_sec']);
		
		//if it is not old enough
		if($changed===SET_NOLDEN){
			storeFeedback(MSG_SETTING,MES_ERR_NOE,MSG_ERR);
			redirect(PAGE_SETTING);
		}
		else{
			storeFeedback(MSG_SETTING,MES_SUC_CHANGE,MSG_SUC);
			redirect(PAGE_SETTING);
			
		}
	}



	
	$setUi->changeForm($fname,$mname,$lname);

	

?>