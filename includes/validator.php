<?php
	class Validator{

		//valid email
		const EMAIL = "/^[a-zA-Z0-9.]{1,}@(gmail|hotmail|yahoo).com$/";

		//non empty character only string
		const ALPH = "/^[a-zA-Z]+$/";

		//non empty alpha numeric string
		const ALPHNUM = "/^[a-zA-Z0-9]$/";

		//posative only integer
		const POSNUM = '/^[0-9]+$/';

		//all integers
		const INTNUM = '/^(-)?[0-9]+$/';

		//petition title
		const PETTIT = '/^[\s\w]{20,100}$/i';

		//petition description
		const PETDESC = '/^[\s;,.a-zA-Z0-9\'()]{120,}$/i';


		public static function validate($data,$pattern)
		{
			return preg_match($pattern, $data);
		}
	}



?>
