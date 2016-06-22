<?php 
	class Loginui {

		function loginForm($feedback) {
			echo "
				
				<div class='wrap'>
					<form method='post' action='" . PAGE_LOGIN . "'>
						<input class='inpt' type='text' placeholder='School ID' name='log_username'>
						<input class='inpt' type='password' placeholder='Password' value='' name='log_password'> 
						<input class='btn-sub' type='submit'  value='log in'> 
						<br><label><a href='" . PAGE_FORGOTPASS . "'> Forgot pass </a></label>
					</form>

				</div>
				
			";
		}
		
		function feedback($name)
		{
			echo "<span class='feed'>";
			$feedMsg = retrieveFeedbackInfo($name);
			echo "</span>";
			if(!$feedMsg)
				return false;
			
			$class = ($feedMsg['type'] == MSG_ERR) ? 'feedb-fail' : "feedb-suc";

			echo "<div class='$class'>";
				echo $feedMsg['message'];
			echo "</div>";
		}

		function registerForm($feedback) {
			$years = array(
							1 => 'First',
							2 => 'Second',
							3 => 'Third',
							4 => 'Fourth',
							5 => 'Fifth',
							6 => 'Six'
						);

			?>
				<div class='log_form'>
				<h1> Create Account </h1>
				<?php $this->feedback(MSG_REG) ?>
				<form method='post' action='<?php echo PAGE_LOGIN ?>'>



				<input class='inpt'type='email' required="" name='reg_email' placeholder="don.joe@example.com"> 
				<input class='inpt' type='password' required="" name='reg_pass' placeholder="Your password"> 
				<input class='inpt' type='password' name='reg_pass_conf' placeholder="re enter password" required=""> 
				<input class='btn-sub' type='submit' value='Register'>
				</form>
				</div>
			<?php 
			
		}


		function loginPage($feedback){
			
			echo "<div class='log_main'>";
				
				echo "<div class='log_main_wrap'>";
					echo "<nav class='menubar'>";
					$this->loginForm($feedback);
					echo "</nav>";

					$this->quote();

					echo "<div class='sep'></div>";

					$this->registerForm($feedback);

				echo "</div>";


			echo "</div>";


		}

		function forgetPage($feedback,$fform=true,$token='')
		{
			echo "<div class='log_main'>";
				
				echo "<div class='log_main_wrap'>";
					echo "<nav class='menubar'>";
					$this->loginForm($feedback);
					echo "</nav>";
					echo "<div class='quote'>";
						if($fform)
						{
							echo "<i>Forgot password ?</i>";
							$this->forGotForm($feedback);
						}
						else{
							echo "<i>Change your password </i>";
							$this->changePassForm($token,"");
						}
					echo "</div>";
					

					echo "<div class='sep'></div>";

					$this->registerForm("");
					

				echo "</div>";


			echo "</div>";

		}

		function quote(){
			?>
			<div class='quote'>
			"I could either watch it happen or be a part of it"
			</div>

			<?php 
		}


		function forGotForm($feedback) 
		{
			?>
				<div class='forgotPass'>
					<form method='post' action='<?php print PAGE_FORGOTPASS ?>'>
					<input class='inpt' type='email' name='email' placeholder="Please enter your email address">
					<input class='btn-sub' type='submit' value='Confirm email'>
					</form>
				</div>

			<?php

				echo $feedback;


		}

		function changePassForm($token, $feedback="")
		{
			?>
				<div class='forgotPass'>
					<form method="post" action='<?php echo PAGE_FORGOTPASS ?>'>
						<input class='inpt' type='password' name='forgt_password' placeholder="new password"><br>
						<input class='inpt' type='password' name='forgt_confPassword' placeholder="Confirm password"><br>
						<input type='hidden' name='forgt_token' value ='<?php print $token ?>'>
						<input class='btn-sub' type='submit' value='change' name='forgt_changePass'>
					</form>
				</div>

			<?php 
		}
	}

	$lgui = new Loginui;

	echo "<link rel='stylesheet' href='includes/css/login.css'>";
?>