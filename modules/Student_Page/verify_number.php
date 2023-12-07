<?php 

	session_start();
	require $_SERVER['DOCUMENT_ROOT']."/certificate-generator/" . 'config/db_config.php';

	$number = $_GET['number'];
    $results = $mysqli->query("select * from participants where pnumber=$number ");
    $results = $results->fetch_all();

    if(count($results)==0){
        session_unset();
        session_destroy();
        echo "Mobile number not found ☹️";
    }
    else
    	echo "Ok";


?>