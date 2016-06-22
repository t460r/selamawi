<?php 
	
	include 'includes/required.php';
	include 'includes/ui/profileui.php';
	include 'includes/class/classStudent.php';
	include 'includes/class/classPetition.php';
	include 'includes/class/classActivity.php';
	include 'includes/class/classNotification.php';
	include 'includes/class/classTag.php';

	Authenticator::start_session();

	if (!Authenticator::authenticated() ) 
			redirect(PAGE_LOGIN);
			
	//retrieve id from session
	$studID = Authenticator::get_session('studID');

	//if other student information requested
	if ( valid_form($_GET, array('studID') ) )
		$studID = (int)$_GET['studID'];


	$student = new Student($studID);
	
	//if student with such id not found
	if( !$student->exist() )
		redirect(PAGE_ACCT);
	
	$authnctd = $student->getstudentID()==Authenticator::get_session('studID');

	//publicity information
	$sec = "s-" . $student->getSection();
	$year = "y-" . $student->getYear();
	$dept = "d-" . $student->getDepartment();
	$all = AUD_PUB;
	$class = md5($dept . "-" . $sec . "-" . $year);
	$year  = md5($year);
	$dept = md5($dept);

	$audc = array(
					'class' => $class,
					'year'  => $year,
					'dept'  => $dept,
					'all'   => AUD_PUB
				);


	//recent activities
	$recActs = ($authnctd) ? $student->recentActivities() : null;
	$profui->voteDisp = false;
	
	

	$profui->profile($student,$recActs,$authnctd);

	$page = ( valid_form( $_GET, array('page') )) ? $_GET['page'] : 0;

	if ( valid_form($_GET, array('notf') ) ){

		$notfs = $student->myNotifications();
		
		$profui->listNotf($notfs);
	}
	else{

		$petitions = $student->myPetitions($page,$audc);
		$profui->audc = $audc;
		$profui->listAllPetitions($petitions,'','myPosts');
		$profui->loadMore($page+1,count($petitions), PAGE_STUD);
	}

	

	
?>