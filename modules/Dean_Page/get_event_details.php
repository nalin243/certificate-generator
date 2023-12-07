<?php
    
    $root = $_SERVER['DOCUMENT_ROOT'];
	require $root. "/certificate-generator/" . "config/db_config.php";
	$deptname = $_GET['deptname'];

    error_log("[**] Entering get_event_details.php");

    $result = $mysqli->query("select eventname,date from templates where deptname='$deptname'");
    $result = ($result->fetch_all())[0];

    $permission = $mysqli->query("select allowed from templates where eventname='$result[0]' and deptname='$deptname'");
    $permission = (bool)$permission->fetch_all()[0][0];

   echo json_encode((array("permission"=>$permission,"eventname"=>$result[0]?$result[0]:"None","eventdate"=>$result[1]?$result[1]:"None")));
?>

?>