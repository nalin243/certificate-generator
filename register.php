<?php

	require_once 'session_config.php';
	require_once 'db_config.php';

	if(!empty($_POST)){

	$username = $_POST['username'];
	$password = $_POST['password'];
	$deptName = $_POST['deptname'];

	$results = $mysqli->query("select * from users where username='$username'");
	$results = $results->fetch_all();

	if(count($results)==0){
		//that means no user exists and create a new user
		$hash = password_hash($password,PASSWORD_DEFAULT);
		$mysqli->query("insert into users(username,password,deptname) values('$username','$hash','$deptName')");
		header("Location: ./login_view.php");
		die();
	} else {
		//meaning user already exists
		$_SESSION['errors']['user_exists'] = true;
		header("Location: ./register_view.php");
		die();
	}


	}
	else {
		header("Location: ./register_view.php");
		die();
	}

?>