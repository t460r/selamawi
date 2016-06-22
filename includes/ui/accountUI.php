<?php 
	
	class AccountUI{
		const DESCLEN = 450;
		public $allTags = null;
		
		public $disp    = false;
		public $tags    = null;
		public $voteDisp = true;
		public $crtPet = false;
		public $studID = -1;
		public $audc = array(
								'class' => '',
								'dept' => '',
								'year' => '',
								'all' => ''
						);
		public function feedback($name)
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

		public function menuBar() 
		{
			$menuElems = array(
								'home'	  => array(PAGE_ACCT,'home.svg'),
								
								'Profile'  => array(PAGE_STUD,'user.png'),
								'notification' => array(PAGE_STUD."?notf",'notification.svg'),
								'setting' => array(PAGE_SETTING,'setting.svg'),
								'logout' => array(PAGE_LOGOUT,'logout.svg')

							);

			?>

				<nav class='menubar'>

					<form method='get' class='search' action='<?php print(PAGE_ACCT) ?>'>
						<input class='pet_search' placeholder="Search petitions ... " type='text' name='pet_search'>
						
					</form>
					
					<ul id='elems' class='menu-elems'>
						<?php 

							foreach($menuElems as $name=>$url)
							{
								echo "<li> <a href='". $url[0] . "'><img class='icns' title='" . $name . "' src='includes/images/" . $url[1] . "'></a>";
							}

						?>
					</ul>
					


				</nav>
				<br><br><br><br>
				

			<?php 
		}

		public function createPetition($tags,$audc) {

			if ( !$this->crtPet)
				return;
			?>

				<div class='post'>
				<form id='pet_create' method='post' action='<?php PAGE_ACCT ?>' enctype='multipart/form-data'>
					<input required type='text' class='inpt' 	name='pet_title' placeholder="Title"> <br><br>
					Select tags : <br>
					<select required class='mult' name='pet_tags[]' multiple>
						<?php 
							foreach($tags as $tag)
								echo "<option value ='" . $tag->getID() . "'>" . $tag->getName() . "</option>";
						?>
						
					</select> <br><br>
					
					<textarea required class='post' placeholder="petition description" clas='form_desc' name='pet_desc' ></textarea> <br><br>
					
					<input type='email' required="" class='inpt' name='pet_to' placeholder="Reciever"><br>

					<input type='checkbox' name='pet_anony' value=1> Anonymous <br><br>
					<input type='file' name='pet_image' placeholder="Attach image"> <br><br>
					Visibility : 
					<input type='radio' name='pet_aud' value="<?php echo $audc['all'] ?>"> Public 
					<input type='radio' name='pet_aud' value="<?php echo $audc['class'] ?>"> Class
					<input type='radio' name='pet_aud' value="<?php echo $audc['dept'] ?>"> Department
					<input type='radio' name='pet_aud' value="<?php echo $audc['year'] ?>"> Year <br><br>
					
					<input type='hidden' name='pet_token' value=''>
					<input type='submit' class='btn-sub' value='post' name='pet_sub'>

				</form>
				</div>

			<?php 
		}

		public function descShort($desc, $len=self::DESCLEN) {

			if(strlen($desc) <= $len)
				return $desc;
			
			return substr($desc, 0,$len) . "...";
		}

		public function myInterest($tags)
		{	
			if (gettypei($tags) !='arr')
				return;
			print "<div class='post '>";
				print "<h2> Select interst </h2>";
				print "<form method='get' action='" . PAGE_ACCT . "'>";
				print "<input type='hidden' name='interest'>";
				
				print "<div class='tagList'>";	
					print "<table  cellspacing=10>";
					
					$count = 1;
					foreach($tags as $tag) {
						echo "<tr><td><input type='checkbox' name='int_tags[]' value=" . $tag->getID() . "'>" . $tag->getName() . "</td></tr>";

						
						$count++;

					}
					

					print "</table>";
				print "</div>";

				print "<input type='submit' class='btn-sub' value='list'>";
				print "</form>";
			print "</div>";
		}
		public function popularPetitions($petitions)
		{
			if ( gettypei($petitions)!='arr')
				die("<h3> No result found for $key </h3>");
			echo "<div class='area-last'>";

			($this->tags!==null) ? $this->myInterest($this->tags) : '';

			echo "<h2> Popular Petitions </h2>";
			foreach($petitions as $petition)
			{
				$title = $petition->getTitle();
				$desc  = $petition->getDesc();
				$owner = $petition->getFullName();
				$date  = $petition->getDate();

				
					echo "<div class='post'>";
						
						$this->petitionMin($petition);
					echo "</div>";
				
			}
			echo "</div>";
		}

		public function petitionMin($petition)
		{
			if( !$petition instanceof Petition)
				return false;
			echo "<span class='pet_title'>" .  ucfirst($petition->getTitle()) ."</span>";
			echo "<span class='pet_by'> <img class='glyph' src='includes/images/user.png'> <a href='" . PAGE_STUD . "?studID=" . $petition->getStudID() . "'>". $petition->getFullName() ."</a></span> <span class='pet_date'>  on " . $petition->getDate() ."</span> <br>";
			echo "<div class='pet_description'>". $this->descShort($petition->getDesc(),150) ."


			<a href='" . PAGE_ACCT . "?petID=" . $petition->getPetID() . "'>View More </a>
			</div> ";

		}

		public function listAllPetitions($petitions,$key='',$class='area_mid') {
			

			 $display = ($this->disp) and ($this->allTags!==null and $this->audc!==null);

			crossSS($key);

			echo "<div class='$class'>";
				$this->feedback(MSG_ACCOUNT);
				if ( gettypei($petitions)!='arr')
					die("<div class='post'><h3> No petition found for $key </h3></div>");
			

				
				if($key!=='')
					echo "<h3>Search Results for " . $key . "</h3>";

				if ($display)
					$this->createPetition($this->allTags,$this->audc);

				if(count($petitions)==0){
					echo ("<div class='post'><h3> No petition to display</h3></div>");
					
				}


				foreach($petitions as $petition) {
					
					$tags = $petition->getTags();
					$imgURL = $petition->getImageURL();

					echo "<div class='post'>";
						
						echo "<div class='post_main'>";

							echo "<span class='pet_title'>" .  ucfirst($petition->getTitle()) ."</span>";
							echo "<span class='pet_by'> <img class='glyph' src='includes/images/user.png'> <a href='" . PAGE_STUD . "?studID=" . $petition->getStudID() . "'>". $petition->getFullName() ."</a></span> <span class='pet_date'>  on " . $petition->getDate() ."</span> <br>";

							($imgURL) ? $this->attachedImage($imgURL) : "";
							

							echo "<div class='pet_description'>". $this->descShort($petition->getDesc()) ."


								<a href='" . PAGE_ACCT . "?petID=" . $petition->getPetID() . "'>View More </a>
								</div> ";

							$this->tagList($tags);
							//num of votes and comments

							$audience =  $petition->getAudience();
							$audience = $this->audMap($audience);

							$this->petition_info($petition);
							echo "<hr>";
							echo "<b>Visibility</b> " . $this->audMap($petition->getAudience());
							echo "<hr>";
							//up and down vote links
							if($this->voteDisp)
							 $this->voteForm($petition,$petition->getPetID());

							

						echo "</div>";

					echo "</div>";
				}

			echo "</div>";

		}

		function audMap($audience){
			
			switch ($audience) 
			{
								case $this->audc['class']:
									return 'class';
									break;
								case $this->audc['dept']:
									return 'Department';
									break;
								case $this->audc['year']:
									return 'Year';
									break;
								case $this->audc['all']:
									return 'Public';
									break;
								default:
									return 'Undefined';
									break;
			}

		}

		public function attachedImage($url)
		{
			echo "<img src='	" . UP_DIR . "$url' style='width:90%;border:solid 1px silver'><hr>";
		}
		public function aPetition($petition, $relatedPetitions, $mine){
			if( ! ($petition instanceof Petition ) )
				return;


			$tags = $petition->getTags();
			$imgURL = $petition->getImageURL();
			
			$petOn = ($petition->notfOn()) ? "Stop notification" : "Get notification";
			$notf = ($mine) ? "<a class='petTogle' href='" . PAGE_ACCT . "?chNotf=" . $petition->getPetID() . "'> $petOn </a> <br>":"";

			echo "<div class='petOne'>";

				echo "<div class='post'>";

						echo "<span class='pet_title'>" .  $petition->getTitle() ."</span>" . $notf;
						echo "<span class='pet_by'> <img class='glyph' src='includes/images/user.png'> <a href='" . PAGE_STUD . "?studID=" . $petition->getStudID() . "'>". $petition->getFullName() ."</a> </span>  on <span class='pet_date'>" . $petition->getDate() ."</span>";

						echo "<div class='pet_description'>". $petition->getDesc() ."</div> ";
						($imgURL) ? $this->attachedImage($imgURL) : "";


						$this->tagList($tags);
						
						$this->petition_info($petition);

						echo "<hr>";
							echo "<b>Visibility</b> " . $this->audMap($petition->getAudience());
						echo "<hr>";

						$this->voteForm($petition,$petition->getPetID());					

					echo "</div>";

					

					echo "<div class='allComments'>";

						$this->commentForm($petition->getPetID());

						
							
							foreach($petition->getComments() as $comment) 
							{
								$commenter = $comment->getCommenter();
								echo "<div class='comment'>";
								echo "<div class='commenter'><img class='glyph' src='includes/images/user-icon.png'> <a href='" . PAGE_STUD	 ."?studID=" . $commenter->getStudentID() . "'>" . $commenter->getFullName() ."</a></div>";
								echo "<div class='message'>" . $comment->getMessage();
								echo  "</div> ";
								echo "<span class='date'>" . $comment->getDate() . "</span>";

								
								echo "</div>";


							}

						
					
					echo "</div>";

				
			echo "</div>";

				echo "<div class='related'>";

					echo "<span class='title'> Petitions you might like </span>";
					
					$this->relatedPetitions($relatedPetitions);

				echo "</div>";


		}

		public function relatedPetitions($petitions)
		{
			foreach($petitions as $petition)
			{
				echo "<div class='post'>
					 <div class='post_main'>";

					echo "<span class='pet_title'>" . $petition->getTitle() . "</span>";
					echo "<span class='pet_by'> <img class='glyph' src='includes/images/user.png'> <a href='" . PAGE_STUD . "?studID=" . $petition->getStudID() . "'>". $petition->getFullName() ."</a></span>";

					echo "<div class='pet_description'>" . $this->descShort($petition->getDesc(),150);

					echo "<a href='" . PAGE_ACCT . "?petID=" . $petition->getPetID() . "'>View More </a>

					</div>";

				echo "</div>
						</div>";
			}
		}

		private function commentForm($petID) {
			echo "<form method='post' action='" . PAGE_ACCT . "'>";
				echo "<textarea class='commentBox' placeholder='Leave your comment here..' name='message'></textarea>";
				echo "<input type='hidden' name='petitionID' value=$petID>";
				echo "<input type='submit' name='comSub' class='btn-sub' vaule='comment'>";
			echo "</form>";
		}

		private function voteForm($petition,$petID) {
			$hasV = $petition->hasVoted($this->studID);
			$rpted = $petition->isReported();
			$up = '';
			$down = '';

			
			$rpt = ($rpted) ? 'down' : '';
			$rptLnk = (!$rpted) ? PAGE_ACCT . "?rpt=" . $petID : "#";
			switch ($hasV) {
				
				case VOTE_DOWN:
					$down = 'down';
					break;
				case VOTE_UP:
					$up = 'up';
					break;
				default:

					break;
				}

			echo "<div class='pet_intr'> <a class='$up' title='up vote' href='" . PAGE_ACCT . "?likePET=" . $petID . "&likeType=" . VOTE_UP . "'> + 1 </a> 
						<a class='$down' title='down vote' href='" . PAGE_ACCT . "?likePET=" . $petID . "&likeType=" . VOTE_DOWN . "'> - 1 </a>
						<a class='$rpt' title='report' href='$rptLnk'> R  </a>  <br>

			</div>";
		}


		public function petition_info($petition) 
		{
			

			$numVotes = $petition->getNumVotes();
			echo "<div class='pet_info'>" . $numVotes['UP'] . " Supporters " . $numVotes['DOWN'] . " down votes " . $petition->getNumComments() . " comments </div>";
		}

		public function tagList($tags)
		{
			if ( gettypei($tags) !='arr')
				return;

			echo "<div class='pet_tags'>";
				foreach($tags as $tag)
					print "<a class='tag' href='#" . $tag->getID() . "'>" . $tag->getName() ."</a> ";
			echo "</div>";
		}

		public function loadMore($page,$count,$url=PAGE_ACCT) {
				if ($count<PETPERP)
					return;
				echo "<a href='$url?page=$page'> Load more</a>";
		}
	}

	$acctUI = new AccountUI;
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">';

	echo "<link rel='stylesheet' href='includes/css/style.css' />"

?>