<?php 
	
	include 'includes/required.php';

	Authenticator::start_session();
	Authenticator::del_session();
	redirect(PAGE_LOGIN);

?>