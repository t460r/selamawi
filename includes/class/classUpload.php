<?php 

	class Upload{
		
		private $saveTO = UP_DIR;
		private $tmp_name;
		private $type;
		private $name;
		private $error;
		private $uploaded = false;

		function __construct($fileInfo,$name) {
			$this->tmp_name = $fileInfo[$name]['tmp_name'];
			$this->type     = $fileInfo[$name]['type'];
			$this->name     = $fileInfo[$name]['name'];
			$this->error     = $fileInfo[$name]['error'];

		}

		public function succeed()
		{
			return $this->uploaded;
		}

		public function getName() 
		{
			return $this->name;
		}

		public function upload() {

			if( !isset($fileInfo[$name]))
				return true;
			if ( count($fileInfo[$name])==0)
				return true;
			
			//check if the extension of the image is valid
			if( !$this->imageValid() ) 
				return false;
			
			//create unique name for the image
			$this->generateRandomName();

			//check if no error is found
			if ($this->error!=0) {

				$this->uploaded = false;
				return false;
			}


			//check if the file is uploaded to the temporary directory
			if( !is_uploaded_file($this->tmp_name) )
				return false;
			
			$moved = move_uploaded_file($this->tmp_name, $this->saveTO.$this->name );

			//if moved to its final destination successfully
			if ($moved) 
				$this->uploaded = true;

			return $this->uploaded;

		}


		private function extract_extension(){

			$lastDot = strrpos($this->name, '.');
			$ext      = strtolower(substr($this->name, $lastDot+1));
			return $ext;
		}


		//check if the image extension is valid
		private function imageValid(){
			
			$ext = $this->extract_extension();
			
			switch ($ext) {
				case 'jpg':
				case 'jpeg':
				case 'png':
					return true;
				default:
					return false;
			}

		}

		//generate random name for the image
		private function generateRandomName(){

			$name = mt_rand(10000,100000000);
			$name = time();
			$name .= md5($name);
			$name = $name . "." . $this->extract_extension();

			$this->name = $name;
		}




	}

?>