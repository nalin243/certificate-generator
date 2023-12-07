<?php

	require $_SERVER['DOCUMENT_ROOT']."/certificate-generator/" . 'config/db_config.php';

	$eventname = $_GET['eventname'];
	$permission = ((bool)$_GET['permission'])?1:0;

	$mysqli->query("update templates set allowed=$permission where eventname='$eventname' ");
?>