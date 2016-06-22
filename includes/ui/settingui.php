<?php 
	include 'accountui.php';

	class Settingui extends AccountUI{

		private function changeName($f,$m,$l) 
		{
			echo "<div class='aSetting'>";

			echo "<span class='title'> Change Name </span>";
				echo "<form method='post' action=" . PAGE_SETTING . ">";

					echo "
					
					<input type='text' name='set_fname' placeholder='First name' value='$f'>
					<input type='text' name='set_mname' placeholder='Middle name' value='$m'>
					<input type='text' name='set_lname' placeholder='Last name' value='$l'>
					";
					echo "<input type='submit' class='btn-sub' value='change' name='set_cname'>";
				echo "</form>";

			echo "</div>";
		}

		private function changePassword() 
		{
			echo "<div class='aSetting'>";

			echo "<span class='title'> Change Password </span>";

			echo "<form method='post' action=" . PAGE_SETTING . ">";

				echo "
					
					<input clss='inpt' type='password' placeholder='Old password' name='set_opassword'>
					<input type='password' placeholder='new password' name='set_npassword'>
					<input type='password' placeholder='Confirm password' name='set_cpassword'>
					<input type='hidden' name='set_token'>
				";
				echo "<input type='submit' class='btn-sub' value='change' name='set_cpass'> ";

			echo "</form>";

			echo "</div>";
		}

		private function changeYear()
		{
			$sems = Scdep::$semMap;
			echo "<div class='aSetting'>";

			echo "<span class='title'> Change Year </span>";

			echo "<form method='post' action=" . PAGE_SETTING . ">";

				echo "<select name='set_year'>";

				foreach ($sems as $num=>$name) {
					echo "<option value='$num'> $name </option>";
				}
				echo "</select> ";
				echo "<input type='submit' class='btn-sub' value='change' name='set_cyr'> ";
			echo "</form>";

			echo "</div>";
		}

		private function changeSec()
		{
			$secs = Scdep::$semMap;

			echo "<div class='aSetting'>";

			echo "<span class='title'> Change Section </span>";

			echo "<form method='post' action=" . PAGE_SETTING . ">";
				
				echo "Section : ";
				echo "<select name='set_sec'>";
				foreach ($secs as $num=>$name) {
					echo "<option value='$num'> $name </option>";
				}
				echo "</select> ";

				echo "<input type='submit' class='btn-sub' value='change' name='set_csec'> ";

			echo "</form>";

			echo "</div>";
		}

		function changeForm($f,$m,$l)
		{	
			
			echo "<div class='setting'>";
				$this->feedback(MSG_SETTING);
				$this->changeName($f,$m,$l);
				$this->changePassword();
				$this->changeYear();
				$this->changeSec();
				
			echo "</form>
				</div>";
		}



	}

	$setUi = new Settingui;
	$setUi->menuBar();
?>