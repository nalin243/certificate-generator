<?php

	$rootdir = $_SERVER['DOCUMENT_ROOT']; 
    $path = "/certificate-generator/";

    require $rootdir."/certificate-generator/" . 'modules/display_text.php';

	session_start();

	$xname = (double)$_POST['xname'];
	$yname = (double)$_POST['yname'];
	$xdate = (double)$_POST['xdate'];
	$ydate = (double)$_POST['ydate'];
	$xevent = (double)$_POST['xevent'];
	$yevent = (double)$_POST['yevent'];
	$xyear = (double)$_POST['xyear'];
	$yyear = (double)$_POST['yyear'];
	$eventname = $_POST['eventname'];
	$date = $_POST['date'];

	$nameFont = $_POST['nameFont'];
	$nameFontSize = (double)$_POST['nameFontSize'];
	$nameColor = $_POST['nameColor'];

	$dateFont = $_POST['dateFont'];
	$dateFontSize = (double)$_POST['dateFontSize'];
	$dateColor = $_POST['dateColor'];

	$yearFont = $_POST['yearFont'];
	$yearFontSize = (double)$_POST['yearFontSize'];
	$yearColor = $_POST['yearColor'];

	$eventFont = $_POST['eventFont'];
	$eventFontSize = (double)$_POST['eventFontSize'];
	$eventColor = $_POST['eventColor'];

	$imgWidth = $_POST['imgWidth'];
	$imgHeight = $_POST['imgHeight'];

	if(!is_null($_FILES)){
		$imgFile = $_FILES['img']['tmp_name'];
		if(!is_null($imgFile)){
			$imgstring = file_get_contents($imgFile);
			$img = imagecreatefromstring($imgstring);
			$img = imagescale($img,$imgWidth,$imgHeight);

			displayText($img,"John Doe",$nameFontSize,"../../fonts/$nameFont",$xname,$yname,$nameColor);
			displayText($img,"III Preview",$yearFontSize,"../../fonts/$yearFont",$xyear,$yyear,$yearColor);
			displayText($img,$date ? $date : "12-12-12" ,$dateFontSize,"../../fonts/$dateFont",$xdate,$ydate,$dateColor);
			displayText($img,$eventname ? $eventname : "Preview Event Name",$eventFontSize,"../../fonts/$eventFont",$xevent,$yevent,$eventColor);

		    ob_start();
		    imagepng($img);
		    $imgData = base64_encode(ob_get_clean());
		    
		    echo json_encode(array($imgData));
		    
		}
	}


?>