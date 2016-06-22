<?php 
	
	include 'includes/ui/accountui.php';

	class Profileui extends Accountui{
		public function profile($info,$recent,$authnctd){
			echo "<div class='stud_profile'>";
				
				echo "<div class='stud_info'>";
					echo "<img class='profilepic' src='includes/images/user.png'>";
					$this->information($info);
				echo "</div>";

				if($authnctd){
					
					echo "<div class='recentActv'>";
						echo "<h3> Recent Activities </h3>";
						$this->recentActv($recent);
					echo "</div>";
				}

			echo "</div>";
			


		}
		public function information($info) {
			
			if(gettypei($info)!='obj')
				return;


			echo "<div class='information'>";
					
				echo "<dl>
							<dt>Full Name  </dt> <dd> " . $info->getFullName() . "</dd></dl>";

				echo "<dl>
							<dt>Department </dt>
							<dd>" . Scdep::getDepName($info->getDepartment()) . "</dd></dl>";
				echo "<dl>
						<dt>Section </dt> 
						<dd>" . $info->getSection() . "</dd></dl>"; 
				echo "<dl>
					<dt>School </dt>
					<dd>" . Scdep::mapDepSch($info->getDepartment()) ."</dd></dl>";
				echo "<dl>
						<dt>Year </dt>
						<dd>" . $info->getYear() . "</dd></dl>";
				echo "<dl>
				<dt>Semester </dt>
				<dd>" . Scdep::getSem($info->getSemester()) ."</dd</dl>";

				
			
			echo "</div>";

		}

		public function recentActv($info) {
			if (gettypei($info) !='arr')
				return;

			foreach($info as $actv) {
				echo "<a href='" . PAGE_ACCT . "?petID=" . $actv->getOnID() . "'> <span class='actv'>You " . $actv->getVerb() . " on  this petition </span></a>";
			}

		}

		public function listNotf($notfs) 
		{
			if (gettypei($notfs)!=='arr'){
				echo "No notification Found";
				return;
			}
			echo "<div class='myPosts'>";
				
					foreach($notfs as $notf) {
						
						echo "<a class='notf' href='" . PAGE_ACCT . "?petID=" . $notf->getOnID(). "&seen=" . $notf->getType() . "'>" . $notf->getNum() . " new " . $notf->getVerb() . " on your petition </a>";

					}
				echo "</div>";
			echo "</div>";
		}

	}

	$profui = new Profileui;
	$profui->menubar();



?>