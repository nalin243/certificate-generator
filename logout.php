<?php
	require 'session_config.php';

	session_unset();
	session_destroy();

	header("Location: ./login_view.php");
	die();

?>