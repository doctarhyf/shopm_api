<?php


	$dbuser = "root";
	$dbpass = "";
	$dbname = "sosachat";
	$dbhost = 'localhost';
	
	
	
	$dsn = "mysql:host=" . $dbhost . ";dbname=" . $dbname;
	$pdo = new PDO($dsn,$dbuser,$dbpass);

		 


$username = $_REQUEST["uname"];
$password = $_REQUEST["pwd"];




		$resCode = RESULT_CODE_USER_DONT_EXIST;

		if(userExists($username, $pdo)){
			
			

		}else{
			echo 'user dont exists!';
		}



function user

function userExists($emailOrMobile, $pdo){

	$username = $emailOrMobile;
	$data = array($username, $username);//, $username, $password);

		$sql = 'SELECT COUNT(*) FROM users WHERE user_email = ? 
		OR user_mobile = ? ';
		//echo $sql;
		$pds = $pdo->prepare($sql);
		$pds->execute($data);

		$count = $pds->fetchAll();
		$count = $count[0][0];

		if($count == 1){
			return true;
		}else{
			return false;
		}

}

		return;


		$data = array($username, $username, $password);

		$sql ='SELECT * FROM users WHERE ( user_email= ? OR user_mobile=? ) AND user_password=?';
		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);
		

		print_r($res);
		

/*
		if(count($res) == 1){
			//$this->updateFingerPrint($res[0]);
			//$_SESSION['user'] = $res[0];
			return $res[0];
		}else{
			return false;
		}*/

	


/*DATES

 date_default_timezone_set('Africa/Cairo');
$d1 = new DateTime(date('F-d-Y H:i:s'));

//echo $d1 . '<br/>';

date_default_timezone_set('Africa/Cairo');
$d2 = new DateTime(date('F-d-Y H:i:s'));
//echo $d2 . '<br/>';

$int = date_diff($d1, $d2);

echo $int->format('%h:%i:%s');*/





	?>