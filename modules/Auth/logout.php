<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/certificate-generator/" . 'config/session_config.php';

	$path = "/certificate-generator/";

	session_unset();
	session_destroy();

	header("Location: ".$path."src/login_page.php");
	die();

?>