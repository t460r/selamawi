<?php 
	
	require_once 'includes/required.php';
	include 'includes/ui/accountUI.php';
	include 'includes/class/classAccount.php';
	include 'includes/class/classStudent.php';
	include 'includes/class/classPetition.php';
	include 'includes/class/classComment.php';
	include 'includes/class/classVote.php';
	include 'includes/class/classTag.php';
	include 'includes/class/classUpload.php';
	include 'includes/sendEmail.php';

	$acctUI->menuBar();
	
	Authenticator::start_session();

	if (!Authenticator::authenticated() ) 
			redirect(PAGE_LOGIN);
	$studID = Authenticator::get_session(STUDID);
	$acct = new Account($studID);
	

	//publicity information
	$sec = "s-" . $acct->getSection();
	$year = "y-" . $acct->getYear();
	$dept = "d-" . $acct->getDepartment();
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
	$acctUI->audc = $audc;
	$acctUI->studID = $studID;
		
	if ( valid_form($_GET,array('petID')) ){

		if( empty($_GET['petID']) )
			redirect(PAGE_ACCT);

		$petition = $acct->aPetition($_GET['petID']);
		
		if ( !$petition->exist())
			redirect(PAGE_ACCT);

		$mine = ($petition->getOwner() == $studID);

		$relatedPets = $petition->relatedPetitions();

		$acctUI->aPetition($petition,$relatedPets,$mine);

		if( valid_form($_GET, array('seen')) and ($petition->getOwner()==Authenticator::get_session(STUDID)) )
		{
			switch ($_GET['seen']) {
				case 'c':
					$petition->removeComNotf();
					break;
				case 'v':
					$petition->removeVotNotf();
					break;
				
			}
		}


	}

	else if ( valid_form($_GET, array('chNotf') )){
		$petition = new Petition((int)$_GET['chNotf']);
		
		if($petition->exist())
			$petition->chNotfSt();
		
		redirect(PAGE_ACCT . "?petID=" . $_GET['chNotf']);

	}
	else if( valid_form($_GET, array('likePET','likeType') ) ) {

		
		$acct->vote((int)$_GET['likePET'], $_GET['likeType']);
		redirect(PAGE_ACCT . "?petID=" . $_GET['likePET']);

	}

	else if( valid_form($_POST, array('petitionID','message','comSub') ) ) {

		$acct->comment((int)$_POST['petitionID'], $_POST['message']);
		redirect(PAGE_ACCT . "?petID=". $_POST['petitionID']);

	}

	else if( valid_form( $_GET, array('rpt') ) )
	{
		$petition = new Petition((int)$_GET['rpt']);
		$petition->report();
		redirect(PAGE_ACCT . "?petID=".$_GET['rpt']);
	}

	else if( valid_form($_POST, array('pet_title', 'pet_desc', 'pet_to','pet_token', 'pet_sub','pet_tags','pet_aud')))
	{
		
		
		//validate if the leng of the petition title is 20 characters long
		if ( strlen($_POST['pet_title']) < 20 )
		{
			storeFeedback(MSG_ACCOUNT,MES_ERR_TIT_LEN, MSG_ERR);
			redirect(PAGE_ACCT);
		}

		//validate if the lenght of the petition description is 120 characters long
		if ( strlen($_POST['pet_desc']) < 120 )
		{
			storeFeedback(MSG_ACCOUNT,MES_ERR_DESC_LEN, MSG_ERR);
			redirect(PAGE_ACCT);
		}

		//valid if valid email is given to the to field
		if ( !Validator::validate($_POST['pet_to'], Validator::EMAIL) )
		{
			storeFeedback(MSG_ACCOUNT,MES_ERR_INV_EMAIL, MSG_ERR);
			redirect(PAGE_ACCT);
		}

		$anony = isset($_POST['pet_anony']) ? true : false;

		$uploaded = valid_form($_FILES['pet_image'], array('name','type','tmp_name','error','size') );
		$fileName = false;

		if($uploaded)
		{
			$upload = new Upload($_FILES, 'pet_image');
			$uplded = $upload->upload();

			//failed to upload image
			if ( !$uplded)
			{
				storeFeedback(MSG_ACCOUNT,MES_ERR_UPLOAD, MSG_ERR);
				redirect(PAGE_ACCT);
			}

			$fileName = ($uplded) ? $upload->getName() : false;
		}
		
		
		$acct->create_petition($_POST['pet_title'], addslashes($_POST['pet_desc']), $_POST['pet_to'], $_POST['pet_tags'], $_POST['pet_aud'], $fileName, $anony);
		
		storeFeedback(MSG_ACCOUNT,MES_SUC_POST_PET, MSG_SUC);
		redirect(PAGE_ACCT);
	}


	else if( valid_form($_GET,array('popular'))) {
		$petitions = $acct->popularPetitions();
		$acctUI->listAllPetitions($petitions);
	}


	else if( valid_form($_POST, array('int_tags')))
	{
		
	}
	else{

		

		$page =  (valid_form($_GET, array('page') ) )  ? (int)$_GET['page'] : 0;
		
		$petitions = $acct->listAllPetitions($page);
		$allTags = Petition::posTags();
		

		
		
		$tags = Petition::posTags();
		
		$key = '';
		if ( valid_form($_GET, array('int_tags')) ){
			$int_tags = $_GET['int_tags'];
			$petitions = $acct->myInterest($int_tags);
			
		}
		else if( valid_form( $_GET, array('pet_search')) ) {
			
			$petitions = $acct->searchPetition($_GET['pet_search'],$audc);
			$key  = $_GET['pet_search'];
			
		}
		else{
			
			$acctUI->allTags = $allTags;		
			$acctUI->crtPet    = true;
			$acctUI->disp    = true;
		}
			
		
		
		$acctUI->tags = $tags;

		$acctUI->listAllPetitions($petitions,$key);

		$populars = $acct->popularPetitions();
		$acctUI->popularPetitions($populars);
		
		
	}
	
	
	
?>