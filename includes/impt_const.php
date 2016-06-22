<?php 
	
	//pages
	define("PAGE_LOGIN",'./');
	define("PAGE_ACCT","account.php");
	define("PAGE_STUD","student.php");
	define("PAGE_LOGOUT","logout.php");
	define("PAGE_REG","register.php");
	define("PAGE_SETTING","setting.php");
	define("PAGE_FORGOTPASS","forgotPass.php");


	//tablse
	define("TABLE_STUD","student");
	define("TABLE_PET","petition");
	define("TABLE_COM","comment");
	define("TABLE_VOTE","vote");
	define("TABLE_TAG", "tag");
	define("TABLE_PET_TAG","pet_tag");
	define("TABLE_LAST_MODF",'last_modf');
	define("TABLE_AUDIENCE","audience");
	define("TABLE_FORGOTPASS","forgotPass");
	define("TABLE_REGCONF","regconf");

	//vote
	define("VOTE_NONE",0);
	define("VOTE_UP",1);
	define("VOTE_DOWN",2);

	//comment
	define("COMMENT_SEEN",1);
	define("COMMENT_UNSEEN",2);

	//session keys
	define("STUDID","studID");

	//petition
	define("PET_UNRESOLVED",1);
	define("PET_RESOLVED",2);
	define("PET_ANONY",0);
	define("PET_ANONY_HANDLER","Anonymous");
	define("PET_NOTF_OFF",1);
	define("PET_NOTF_ON",2);
	define("AUD_PUB",md5('public'));
	define("PET_REL_LIM",10);
	define("PET_ST_RPT",2);
	define("PET_ST_DEL",3);
	define("PET_NUM_CV",30);
	define("PET_NUM_DV",100);
	define("PET_NUM_PV",100);
	define("PET_NUM_AV",200);

	//student 
	define("STUD_UNREG",1);
	define("STUD_REG",2);
	define("STUD_BLOCK",3);

	//return values 
	define("RTRN_UE",1); //unknown email
	define("RTRN_AR",2); //student already registred

	//acount
	define("PETPERP",15);//number of petitions per page
	define("RECACT",10);

	//popularity algorithm
	define("FACT_COM",0.1);
	define("FACT_DOWN",0.3);
	define("FACT_UP",0.6);

	//setting
	define("NUM_DAY_SEM",90);
	define("SET_YEAR",1);
	define("SET_SEC",2);
	define("SET_NAME",3);
	define("SET_NOLDEN",1);
	define("SET_BPOSC",2);

	//common
	define("UNDEF","Undefined");
	define("UP_DIR","uploaded/");

	//messages
	define("MES_ERR","Failed to commit the requested action. Please Try again later");
	define("MES_SUC","successfully commited the requested action");
	define("MES_EDIT","You have successfully edited the value");
	define("MES_FCHANGE","Unable to make change to this field");
	define("MES_SUC_CHANGE","Value successfully changed");
	define("MES_ERR_POST_PET","Unable to create petition. Be sure you provided the value of all fields properly");
	define("MES_SUC_POST_PET","Petition created successfully");
	define("MES_ERR_NAME_NOE","You made changes to your name recently. Before committing any another change, You have to wait at least a month.");
	define("MES_ERR_NAME_MANCH","Changing only small portion of your name is allowed. ");
	define("MES_SUC_NAME_CH","You have successfully changed your name");

	define("MES_ERR_NOE","You have made change to this field recently. You cant make changes now");

	define("MES_ERR_PAS_NM","Password doesnt match");
	define("MES_SUC_PAS","You have successfully changed your password");

	define("MES_ERR_INV_EMAIL","Invalid email");
	define("MES_ERR_DESC_LEN","The length of the petition description must be atleast 120 characters long");
	define("MES_ERR_TIT_LEN","The length of the petition title must be atleast 20 and at most 100 characters long");
	define("MES_ERR_UPLOAD","Failed to upload image");
	define("MES_SUC_UPLOAD","Failed to upload image");
	define("MES_SUC_RPTD","Petition reported as inappropriate.");

	//message names and constatns
	define("MSG_SETTING","setting");
	define("MSG_ACCOUNT","account");
	define("MSG_REG","register");

	define("MSG_ERR",1);
	define("MSG_SUC",2);
	


	
?>