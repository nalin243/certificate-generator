<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/certificate-generator/" . 'config/env_config.php';

	define("DBNAME",$_ENV['DB_NAME']);
	define("DBUSER",$_ENV['DB_USER']);
	define("DBPASSWORD",$_ENV['DB_PASSWORD']);
	define("DBURL",$_ENV['DB_URL']);
	
	$mysqli = mysqli_connect(DBURL,DBUSER,DBPASSWORD,DBNAME);
?>