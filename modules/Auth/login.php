<?php

	
	require $_SERVER['DOCUMENT_ROOT']."/certificate-generator/" . 'config/db_config.php';
	require $_SERVER['DOCUMENT_ROOT']."/certificate-generator/" . 'config/session_config.php' ;

	$path = "/certificate-generator/";

	if(!empty($_POST)){

	$username = $_POST['username'];
	$password = $_POST['password'];

	$results = $mysqli->query("select * from users where username='$username' ");
	$results = $results->fetch_all();

	if(!count($results)==0){

		if(password_verify($password,$results[0][1])){
			//password matches so login successful

			$_SESSION['user_username'] = $username;
			header("Location: ".$path."src/config_page.php"); 
			die();
		}
		else {
			//incorrect password error code
			$_SESSION['errors']['wrong_password'] = true; 
			header("Location: ".$path."src/login_page.php");
			die();			
		}
	}
	else{
		//user does not exist error code
		$_SESSION['errors']['user_does_not_exist'] = true;
		session_write_close();
		header("Location: ".$path."src/login_page.php");
		die();
	}


	}
	else {
		header("Location: ". $path ."login_page.php");
		die();
	}

?>