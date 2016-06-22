<?php 
	
	require 'PHPMailer/PHPMailerAutoload.php';
	class Email
	{
		private $mail;
		const USRNAME = 'readertabor@gmail.com';
		const PASSWD  = 'lovereading';

		function __construct() 
		{
			$this->mail = new PHPMailer;

			$this->mail->isSMTP();                
			$this->mail->Host = 'smtp.gmail.com'; 
			$this->mail->SMTPAuth = true;         
			$this->mail->Username = self::USRNAME;
			$this->mail->Password = self::PASSWD; 
			$this->mail->SMTPSecure = 'tls';      
			$this->mail->Port = 587;              

			$this->mail->setFrom('noreply@selamawi.com', 'selamawi');
			$this->mail->addReplyTo('noreply@selamawi.com', 'Selamawi');
		}

		public function sendMail($to,$bodyContent) 
		{


			$this->mail->addAddress($to);   // Add a recipient


			$this->mail->isHTML(true);  // Set email format to HTML

		
			$this->mail->Subject = 'selamawi - support';
			$this->mail->Body    = $bodyContent;

			return $this->mail->send();

		}

		
	}

	$sendemail = new Email;
?>