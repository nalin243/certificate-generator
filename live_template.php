<?php

// $text_bound = imageftbbox(30, 0, "./fonts/certasans.ttf", "John Doe");

	session_start();

	function getTextWidth($textstring,$fontsize,$fontname){
		//returns text width and height to calculate offset
		$text_bound = imageftbbox($fontsize, 0, $fontname, $textstring);

		$lower_left_x =  $text_bound[0]; 
		$lower_left_y =  $text_bound[1];
		$lower_right_x = $text_bound[2];
		$lower_right_y = $text_bound[3];
		$upper_right_x = $text_bound[4];
		$upper_right_y = $text_bound[5];
		$upper_left_x =  $text_bound[6];
		$upper_left_y =  $text_bound[7];

		$text_width =  $lower_right_x - $lower_left_x;
		$text_height = $lower_right_y - $upper_right_y;

		return $text_width;
	}

	function getOffset($xcoordinate,$ycoordinate,$text_width){
		//calculates offset so text can be centered
		$textOffsetx = $xcoordinate - ($text_width/2);
		$textOffsety = $ycoordinate;

		return array($textOffsetx,$textOffsety);
	}

	function getRGBColor($img,$hexcolor){
		//takes in gd object image and hex color and returns rgb color that can be used in imagettftext
		list($r, $g, $b) = sscanf($hexcolor, "#%02x%02x%02x");
		$color = imagecolorexact($img, $r, $g, $b);

		return $color;
	}

	function displayText($img,$textstring,$fontsize,$fontname,$xcoordinate,$ycoordinate,$hexcolor){
		//takes in the gd image object and other parameters and displays text 
		$text_width = getTextWidth($textstring,$fontsize,$fontname,$xcoordinate,$ycoordinate);
		list($xoffset,$yoffset) = getOffset($xcoordinate,$ycoordinate,$text_width);
			
		$color = getRGBColor($img,$hexcolor);

		imagettftext($img,$fontsize,0,$xoffset,$yoffset,$color,$fontname,$textstring);
	}

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
	$font = $_POST['font'];
	$hexcolor = $_POST['color'];
	$fontsize = $_POST['fontsize'];

	$imgWidth = $_POST['imgWidth'];
	$imgHeight = $_POST['imgHeight'];

	if(!is_null($_FILES)){
		$imgFile = $_FILES['img']['tmp_name'];
		if(!is_null($imgFile)){
			$imgstring = file_get_contents($imgFile);
			$img = imagecreatefromstring($imgstring);
			$img = imagescale($img,$imgWidth,$imgHeight);

			displayText($img,"John Doe",$fontsize,"./fonts/$font",$xname,$yname,$hexcolor);
			displayText($img,"III Preview",$fontsize,"./fonts/$font",$xyear,$yyear,$hexcolor);
			displayText($img,$date ? $date : "12-12-12" ,$fontsize,"./fonts/$font",$xdate,$ydate,$hexcolor);
			displayText($img,$eventname ? $eventname : "Preview Event Name",$fontsize,"./fonts/$font",$xevent,$yevent,$hexcolor);

		    ob_start();
		    imagepng($img);
		    $imgData = base64_encode(ob_get_clean());
		    
		    echo json_encode(array($imgData));
		    
		}
	}


?>