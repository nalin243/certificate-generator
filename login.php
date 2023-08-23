<?php

	require 'session_config.php' ;
	require 'db_config.php';

	if(!empty($_POST)){

	$username = $_POST['username'];
	$password = $_POST['password'];

	$results = $mysqli->query("select * from users where username='$username' ");
	$results = $results->fetch_all();

	if(!count($results)==0){

		if(password_verify($password,$results[0][1])){
			//password matches so login successful

			$_SESSION['user_username'] = $username;
			header("Location: ./config.php"); 
			die();
		}
		else {
			//password is wrong error code
		}
	}
	else{
		//user does not exist error code
		header("Location: ./login_view.php");
		die();
	}


	}
	else {
		header("Location: ./login_view.php");
		die();
	}

?>