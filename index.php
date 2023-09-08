<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        
        require 'env_config.php';
        require 'db_config.php';
        require 'mail_config.php';
        
        session_start();

        $pname = "";
        $peventname = "";
        $certImage = "";
        $imgData = "";

        if(count($_POST)!=0){
                if(! (int) (strval($_POST['first']).strval($_POST['second']).strval($_POST['third']).strval($_POST['fourth']).strval($_POST['fifth']).strval($_POST['sixth']).strval($_POST['seventh']).strval($_POST['eighth']).strval($_POST['ninth']).strval($_POST['tenth'])) == 0 ){
                    $_SESSION['number'] = (int) (strval($_POST['first']).strval($_POST['second']).strval($_POST['third']).strval($_POST['fourth']).strval($_POST['fifth']).strval($_POST['sixth']).strval($_POST['seventh']).strval($_POST['eighth']).strval($_POST['ninth']).strval($_POST['tenth']));
                }
        }

        if($_SESSION['number']!=""){

            $number = $_SESSION['number'];
            $results = $mysqli->query("select * from participants where pnumber=$number ");
            $results = $results->fetch_all();

            if(count($results)==0){
                $pname = "Mobile number not found";
                session_unset();
                session_destroy();
            }
            else {

            //generating otp as this means user is in database
            if(!isset($_SESSION['generatedOTP'])){
                $_SESSION['generatedOTP'] = rand(1000,9999);

            }

            $participantEmail = $results[0][5];
            $participantName = $results[0][1];

            $message = "Your OTP for getting participation certificate is ".$_SESSION['generatedOTP'];

            //sending mail to use

            $mail->Subject = 'OTP';
            $mail->setFrom('da3798@srmist.edu.in', 'Live Wires');
            $mail->addAddress("$participantEmail", "$participantName"); 
            $mail->Body = $message;

            if(!$_SESSION['mailStatus'])
                $_SESSION['mailStatus'] = $mail->send();

            $mail->smtpClose();


            if($_POST['otp']!=""){

                if((int)$_POST['otp']==$_SESSION['generatedOTP']){

                    $number = $_SESSION['number'];
                    $results = $mysqli->query("select * from participants where pnumber=$number ");
                    $results = $results->fetch_all();
            
                    if(!(count($results) == 0)){
                        $pname = $results[0][1];
                        $peventname = $results[0][3];
                        $date = $results[0][2];
                        $formId = $results[0][4];
                        $template = $mysqli->query("select * from templates where formId='$formId' ");
                        $data = $template->fetch_all();

                        $certImage = $data[0][1];

                        $img = imagecreatefromstring(base64_decode($certImage));
                        $black = imagecolorexact($img, 0, 0, 0);

                        imagettftext($img,100,0,$data[0][2],$data[0][3],$black,'./shortbaby.ttf',$pname);
                        imagettftext($img,40,0,$data[0][4],$data[0][5],$black,'./shortbaby.ttf',$date);

                        ob_start();
                        imagepng($img);
                        $imgData = base64_encode(ob_get_clean());
                        $certImage = "<img src='data:image/png;base64,$imgData' class='cert-img'/>";

                        session_unset();
                        session_destroy();
                            
                    }
                    else {
                        $pname = "Mobile number not found";
                        session_unset();
                        session_destroy();
                    }
                }
                else {
                    $pname = "OTP Invalid";
                    session_unset();
                    session_destroy();
                }
            }
        }

        }

           $client = new Google\Client;
           $client->setAuthConfig("client_secret.json");
           $client->setApplicationName("Certficate-generator");
           $client->setScopes(['https://www.googleapis.com/auth/forms','https://www.googleapis.com/auth/drive']);

           $service = new Google\Service\Forms($client);

        $formIds = (($mysqli->query("select formId from users"))->fetch_all());

        foreach($formIds as $formId){

            $form = $service->forms->get($formId[0]);   
            $questionIds = [];

           foreach($form['items'] as $item){
                if(str_contains(strtolower($item['title']),"name"))
                    $questionIds["name"] = $item['questionItem']['question']['questionId'];
                if(str_contains(strtolower($item['title']),"date"))
                    $questionIds["date"] = $item['questionItem']['question']['questionId'];
                if(str_contains(strtolower($item['title']),"event"))
                    $questionIds["eventname"] = $item['questionItem']['question']['questionId'];
                if(str_contains(strtolower($item['title']),"year"))
                    $questionIds["year"] = $item['questionItem']['question']['questionId'];
                if(str_contains(strtolower($item['title']),"semester"))
                    $questionIds["semester"] = $item['questionItem']['question']['questionId'];
                if(str_contains(strtolower($item['title']),"number"))
                    $questionIds["number"] = $item['questionItem']['question']['questionId'];
                if(str_contains(strtolower($item['title']),"mail"))
                    $questionIds["mail"] = $item['questionItem']['question']['questionId'];
           }

           $responses = $service->forms_responses->listFormsResponses($formId[0]);
           foreach($responses as $response){

                $phoneId = $questionIds['number'];
                $nameId = $questionIds['name'];
                $eventNameId = $questionIds['eventname'];
                $dateId = $questionIds['date'];
                $mailId = $questionIds['mail'];

                $phoneNo = (int)$response['answers']["$phoneId"]['textAnswers'][0]['value'];
                $name = $response['answers']["$nameId"]['textAnswers'][0]['value'];
                $eventName = $response['answers']["$eventNameId"]['textAnswers'][0]['value'];
                $date = $response['answers']["$dateId"]['textAnswers'][0]['value'];
                $mail = $response['answers']["$mailId"]['textAnswers'][0]['value'];


                $result = $mysqli->query("select * from participants where pnumber=$phoneNo");

                if(count($result->fetch_all())){
                    //already exists so just go to the next response
                    continue;
                }
                else {
                    //does not exist so insert into db
                    $mysqli->query("insert into participants(pnumber,name,date,eventname,formId,email) values($phoneNo,'$name','$date','$eventName','$formId[0]','$mail')");
                }
            }
            $questionIds = [];
       }

     ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerification</title>

    <link rel="stylesheet" href="./public/index.css">
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>    
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
                            <button name="submit" value="submit" class="otp-btn ml-5 hover:scale-90 px-3">Send OTP</button>
                            <button name="submit" value="submit" class="hidden verify-btn ml-5 hover:scale-90 px-3">Verify</button>
                        </div>
                    </div>
                </form>
                
            </div>
          <!--   <div class="flex black-text h-1/6 w-full">
                <h1 class="text-black font-extrabold m-auto text-xl">Please Enter your Registered Phone Number.</h1>
            </div> -->
             <div class="flex text-center justify-center">
                <h1 id="otp-message" class="text-black font-extrabold m-auto text-md mt-4 hidden">Check your registered email for OTP</h1>
            </div>
            <div class ="flex flex-col mt-2 h-full w-full">
         <!--     <div class="flex h-2/6 w-full black-text">
                    <h1 id="pname" class="text-black font-extrabold m-auto mt-5"><?= $pname ?></h1>
                </div>
                <div class="flex h-1/6 w-full black-text">
                    <h1 id="peventname" class="text-black font-bold m-auto mt-10"><?= $peventname ?></h1>
                </div> -->
            </div>
        </div>
        <div id="img-container-parent" class="flex h-5/6 min-w-screen shrink-0 ">
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
                    document.getElementById("img-container").scrollIntoView()
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
                inputs.addEventListener("input", function (e) {
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
