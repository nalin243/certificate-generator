<?php
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

        function displayText($img,$textstring,$fontsize,$fontname,$xcoordinate,$ycoordinate,$hexcolor,$boundingrectwidth,$fontwidth=0){
            //takes in the gd image object and other parameters and displays text
            while( (getTextWidth($textstring,$fontsize,$fontname,$xcoordinate,$ycoordinate,$boundingrectwidth)) > $boundingrectwidth ){
                $fontsize = $fontsize - 2;
            }
            $text_width = getTextWidth($textstring,$fontsize,$fontname,$xcoordinate,$ycoordinate);
            list($xoffset,$yoffset) = getOffset($xcoordinate,$ycoordinate,$text_width);
                
            $color = getRGBColor($img,$hexcolor);

            imagettftext($img,$fontsize,0,$xoffset,$yoffset,$color,$fontname,$textstring);
        }
?>
