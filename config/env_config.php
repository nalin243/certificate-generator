<?php 
	require $_SERVER['DOCUMENT_ROOT']."/certificate-generator/" . 'vendor/autoload.php';

	$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__,1));
	$dotenv->load();
?>