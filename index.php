<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        
        require 'env_config.php';
        require 'db_config.php';
        require 'mail_config.php';
        
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


        $pname = "";
        $error_message = "";
        $peventname = "";
        $certImage = "";
        $imgData = "";

        if( count( ($mysqli->query("select * from templates"))->fetch_all() ) == 0  ){ //checking if templates exist or not
            $_SESSION['errors']['template_error'] = "Whoops! &nbsp; No templates found! &nbsp; ☹️ ";
            header("Location: ./error_view.php");
        }


        if(count($_POST)!=0){
            $unsanitizedNumber = (int) (strval($_POST['first']).strval($_POST['second']).strval($_POST['third']).strval($_POST['fourth']).strval($_POST['fifth']).strval($_POST['sixth']).strval($_POST['seventh']).strval($_POST['eighth']).strval($_POST['ninth']).strval($_POST['tenth'])) ;
            $cleanNumber = filter_var($unsanitizedNumber,FILTER_SANITIZE_NUMBER_INT);//sanitizing
            $cleanNumber = filter_var($cleanNumber,FILTER_VALIDATE_INT);//validating

            if($cleanNumber ){
                $_SESSION['number'] = $cleanNumber;
            }
        }

        if($_SESSION['number']!=""){

            $number = $_SESSION['number'];
            $results = $mysqli->query("select * from participants where pnumber=$number ");
            $results = $results->fetch_all();

            if(is_null($_SESSION['errors']['number_not_found'])){

                // //generating otp as this means user is in database
                if(!isset($_SESSION['generatedOTP']))
                    $_SESSION['generatedOTP'] = rand(1000,9999);

                $participantEmail = $results[0][5];
                $participantName = $results[0][1];

                $message = "Your OTP for getting participation certificate is ".$_SESSION['generatedOTP'];

                //sending mail to use

                $mail->Subject = 'OTP';
                $mail->setFrom('certgenbot1@gmail.com', 'Live Wires');

                try{
                    $mail->addAddress("$participantEmail", "$participantName"); 
                    $mail->Body = $message;

                    if(!$_SESSION['mailStatus']){
                        if($error_message !== "OTP Invalid")
                            $_SESSION['mailStatus'] = $mail->send();
                    }

                  } catch(Exception $e){
                    if(gettype(strpos($e->getMessage(),"Invalid address"))==="integer"){

                        $_SESSION['errors']['email_error'] = "Whoops! &nbsp; Invalid email address provided. ☹️ &nbsp;  Contact Mayank Mehra!";
                        header("Location: ./error_view.php");
                    }
                }

                $mail->smtpClose();


                if($_POST['otp']!=""){

                    if((int)$_POST['otp']==$_SESSION['generatedOTP']){

                        $number = $_SESSION['number'];
                        $results = $mysqli->query("select * from participants where pnumber=$number ");
                        $results = $results->fetch_all();
                
                        if(!(count($results) == 0)){
                            $pname = htmlspecialchars($results[0][1]);
                            $formId = htmlspecialchars($results[0][4]);
                            $class = $results[0][6];
                            $template = $mysqli->query("select * from templates where formId='$formId' ");
                            $data = ($template->fetch_all())[0];

                            $certImage = $data[1];

                            $xname =  $data[2];
                            $yname =  $data[3];
                            $xdate =  $data[4];
                            $ydate =  $data[5];
                            $xyear =  $data[6];
                            $yyear =  $data[7];
                            $xevent =  $data[8];
                            $yevent =  $data[9];

                            $nameFont =  $data[10];
                            $nameFontSize =  $data[11];
                            $nameColor =  $data[12];

                            $dateFont =  $data[13];
                            $dateFontSize =  $data[14];
                            $dateColor =  $data[15];

                            $yearFont =  $data[16];
                            $yearFontSize =  $data[17];
                            $yearColor =  $data[18];

                            $eventFont =  $data[19];
                            $eventFontSize =  $data[20];
                            $eventColor =  $data[21];

                            $date =  $data[22];
                            $eventname =  $data[23];

                            $img = imagecreatefromstring(base64_decode($certImage));
                            
                            displayText($img,$pname,$nameFontSize,"./fonts/$nameFont",$xname,$yname,$nameColor);
                            displayText($img,$class,$yearFontSize,"./fonts/$yearFont",$xyear,$yyear,$yearColor);
                            displayText($img,$date ? $date : "Error" ,$dateFontSize,"./fonts/$dateFont",$xdate,$ydate,$dateColor);
                            displayText($img,$eventname ? $eventname : "Error",$eventFontSize,"./fonts/$eventFont",$xevent,$yevent,$eventColor);

                            ob_start();
                            imagepng($img);
                            $imgData = base64_encode(ob_get_clean());
                            $certImage = "<img src='data:image/png;base64,$imgData' class='cert-img'/>";

                            session_unset();
                            session_destroy();
                                
                        }
                        else {
                            $error_message = "Mobile number not found";
                            session_unset();
                            session_destroy();
                        }
                    }
                    else {
                        $error_message = "OTP Invalid";
                        session_unset();
                        session_destroy();
                    }
                }
            }
        }

        require 'get_participants.php'; //

     ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerification</title>

    <link rel="stylesheet" href="./public/index.css">
    <link rel="stylesheet" href="./public/tailwind.css">
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">
  
</head>
<body OnLoad="document.phone.first.focus();">
    <div class="flex flex-col page h-screen min-w-screen overflow-auto">
        <div id="main-container" class="flex flex-col">
            <div class="flex h-auto w-full mt-14">
                <img src="./src/assets/srmlogo.png" class=" img p-15 m-auto h-full w-3/12">
            </div>
            <div class ="flex flex-col h-3/6 w-full mt-10">
                <div class="flex h-3/6 w-full">
                    <h1 class="first-text m-auto mt-2">SRM Institute of Science and Technology</h1>
                </div>
                <div class="flex h-3/6 w-full">
                    <h1 class="second-text m-auto mt-2">Faculty of Science and Humanities,</h1>
                </div>
                <div class="flex h-3/6 w-full">
                    <h1 class="second-text m-auto mt-2">Kattankulathur.</h1>
                </div>
            </div>
            <div id="inputs" class="inputs flex h-1/6  w-full justify-center">
                <form id="phone" name="phone" method="POST" action="index.php">
                    <div class="flex justify-center numberlaptop">
                        <div class="flex flex-col justify-center text-center">
                            <div id="number-container" class="flex">
                                <input class="input" name="first" type="text" inputmode="numeric" maxlength="1" />
                                <input class="input" name="second" type="text" inputmode="numeric" maxlength="1" />
                                <input class="input" name="third" type="text" inputmode="numeric" maxlength="1" />
                                <input class="input" name="fourth" type="text" inputmode="numeric" maxlength="1" />
                                <input class="input" name="fifth" type="text" inputmode="numeric" maxlength="1" />
                                <input class="input" name="sixth" type="text" inputmode="numeric" maxlength="1" />
                                <input class="input" name="seventh" type="text" inputmode="numeric" maxlength="1" />
                                <input class="input" name="eighth" type="text" inputmode="numeric" maxlength="1" />
                                <input class="input" name="ninth" type="text" inputmode="numeric" maxlength="1" />
                                <input class="input" name="tenth" type="text" inputmode="numeric" maxlength="1" />
                            </div>
                            <div id="otp-container" class="flex justify-center hidden">
                                <input name="otp" class="input m-auto w-full" type="text" placeholder="OTP"/>
                            </div>
                        </div>
                        <div class="m-auto">
                            <button name="submit" value="submit" class="hidden otp-btn ml-5 hover:scale-90 px-3">Send OTP</button>
                            <button name="submit" value="submit" class="hidden verify-btn ml-5 hover:scale-90 px-3">Verify</button>
                        </div>
                    </div>
                </form>
                
            </div>
            <div class="flex black-text h-1/6 w-full">
                <h1 id="enter-message" class="text-black font-extrabold m-auto text-xl mt-3">Please Enter your Registered Phone Number.</h1>
            </div>
             <div class="flex text-center justify-center">
                <h1 id="otp-message" class="text-black font-extrabold m-auto text-md mt-4 hidden">Check your registered email for OTP</h1>
            </div>
            <div class ="flex flex-col mt-2 h-full w-full">
            <div class="flex h-2/6 w-full black-text text-center">
                    <h1 id="error-message" class="text-red-800 font-bold m-auto mt-5"> <?= $error_message ?> </h1>
                </div>
            </div>
        </div>
        <div id="img-container-parent" class="flex h-5/6 min-w-screen shrink-0 hidden">
            <div id="img-container" class="flex relative container m-auto my-0">
                <?= $certImage ?>
                <div class="middle absolute .inset-0">
                    <a href="data:image/png;base64,<?= $imgData ?>" download="<?= $pname."-certificate" ?>"><button class="text">Click to Download</button></a>
                </div>
            </div>
        </div>
    </div>
    <script>
                const inputs = document.getElementById("inputs")
                const formElement = document.querySelector("#phone")

                const numberCont = document.getElementById("number-container")
                const otpCont = document.getElementById("otp-container")

                if( "<?= $certImage ?>" != ""){
                    document.getElementById("img-container-parent").classList.remove("hidden")
                    document.querySelector(".cert-img").scrollIntoView()

                }

                if("<?= $_SESSION['generatedOTP'] ?>" != ""){
                    document.querySelector("#enter-message").classList.add("hidden")
                }

                if( "<?= $_SESSION['number'] ?>" != "" ){

                    document.querySelector(".otp-btn").classList.add("hidden")
                    document.querySelector(".verify-btn").classList.remove("hidden")
                    document.getElementById("otp-message").classList.add("hidden")

                    if(otpCont.classList.contains("hidden")) {                        
                        numberCont.classList.add("hidden")
                        otpCont.classList.remove("hidden")
                        document.getElementById("otp-message").classList.remove("hidden")
                    }
                }

                inputs.addEventListener("click",(event)=>{
                    for(var i=0;i<event.target.length;i++){
                        if(!event.target[event.target.length-1].value=="")
                            event.target[event.target.length-1].focus()
                        if(event.target[i].value==""){
                            event.target[i].focus()
                            break
                        }
                    }
                })

                inputs.addEventListener("keydown",(event)=>{
                    const next = event.target.nextElementSibling
                    const prev = event.target.previousElementSibling

                    if(event.key=="ArrowRight"){
                        if(next)
                            next.focus()
                    }
                    if(event.key=="ArrowLeft"){
                        if(prev)
                            prev.focus()
                    }
                    
                        
                })
                inputs.addEventListener("input",(e)=>{

                const number = [...document.querySelector("#number-container").children].reduce((number,child)=>{
                    return number+child.value
                },"")
                if(number.length==10){

                    const httpRequest = new XMLHttpRequest()

                    httpRequest.onreadystatechange = ()=>{
                        if(httpRequest.readyState===4 && httpRequest.status==200){
                            if(httpRequest.responseText != "Ok"){
                                document.querySelector("#error-message").innerText = httpRequest.responseText
                                if(!document.querySelector(".otp-btn").classList.contains("hidden"))
                                    document.querySelector(".otp-btn").classList.add("hidden")
                                <?php $_SESSION['errors']['number_not_found'] = true; ?>
                                <?php $certImage = ""; ?>
                            }
                            else{
                                document.querySelector(".otp-btn").classList.remove("hidden");
                                <?php $_SESSION['errors']['number_not_found'] = null; ?>
                            }
                        }
                    }


                    httpRequest.open("GET",`verify_number.php?number=${number}`,true)
                    httpRequest.send()
                } else {
                    if(!document.querySelector(".otp-btn").classList.contains("hidden"))
                        document.querySelector(".otp-btn").classList.add("hidden")
                }
                document.querySelector("#error-message").innerText = ""
                document.querySelector("#img-container-parent").classList.add("hidden")



                const target = e.target;
                const val = target.value;
                
                if (isNaN(val)) {
                    target.value = "";
                    return;
                }
                
                if (val != "") {
                    const next = target.nextElementSibling;
                    if (next) {
                        next.focus();
                    }
                }
                });
                
                inputs.addEventListener("keyup", function (e) {
                const target = e.target;
                const key = e.key.toLowerCase();
                
                if (key == "backspace" || key == "delete") {
                    target.value = "";
                    const prev = target.previousElementSibling;
                    if (prev) {
                        prev.focus();
                        prev.value=""
                    }
                    return;
                }
                });
            </script>
</body>
</html>
