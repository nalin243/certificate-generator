<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        
        require 'env_config.php';
        require 'db_config.php';
        require 'mail_config.php';
        
        session_start();

        $pname = "";
        $error_message = "";
        $peventname = "";
        $certImage = "";
        $imgData = "";

        if( count( ($mysqli->query("select * from templates"))->fetch_all() ) == 0  ){ //checking if templates exist or not
            $_SESSION['errors']['template_error'] = "Whoops! &nbsp; No templates found! &nbsp; :( ";
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

                        $_SESSION['errors']['email_error'] = "Whoops! &nbsp; Invalid email address provided. &nbsp; :(  Contact Mayank Mehra!";
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
                            $peventname = htmlspecialchars($results[0][3]);
                            $date = $results[0][2];
                            $formId = htmlspecialchars($results[0][4]);
                            $class = $results[0][6];
                            $template = $mysqli->query("select * from templates where formId='$formId' ");
                            $data = $template->fetch_all();

                            $user = (($mysqli->query("select username from users where formId='$formId' "))->fetch_all())[0][0];

                            if(count($data)==0){
                                $_SESSION['errors']['participant_error'] = "Whoops! No template found for $pname using formid $formId that belongs to $user";
                                header("Location: ./error_view.php");
                            }

                            $certImage = $data[0][1];

                            $img = imagecreatefromstring(base64_decode($certImage));
                            $black = imagecolorexact($img, 0, 0, 0);

                            imagettftext($img,70,0,$data[0][2],$data[0][3],$black,'./fonts/OpenSans-Regular.ttf',$pname);
                            imagettftext($img,40,0,$data[0][4],$data[0][5],$black,'./fonts/OpenSans-Regular.ttf',$date);
                            imagettftext($img,40,0,$data[0][6],$data[0][7],$black,'./fonts/OpenSans-Regular.ttf',$class);

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

        $client = new Google\Client;
        $client->setAuthConfig("client_secret.json");
        $client->setApplicationName("Certficate-generator");
        $client->setScopes(['https://www.googleapis.com/auth/forms','https://www.googleapis.com/auth/drive']);
        $service = new Google\Service\Forms($client);

        $formIds = (($mysqli->query("select formId from users"))->fetch_all());

        foreach($formIds as $formId){

            try{
                $form = $service->forms->get($formId[0]); 
                $questionIds = [];


               foreach($form['items'] as $item){
                    if(str_contains(strtolower($item['title']),"name"))
                        $questionIds["name"] = $item['questionItem']['question']['questionId'];
                    if(str_contains(strtolower($item['title']),"date"))
                        $questionIds["date"] = $item['questionItem']['question']['questionId'];
                    if(str_contains(strtolower($item['title']),"event"))
                        $questionIds["eventname"] = $item['questionItem']['question']['questionId'];
                    if(str_contains(strtolower($item['title']),"class"))
                        $questionIds["class"] = $item['questionItem']['question']['questionId'];
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
                    $classId = $questionIds['class'];

                    $phoneNo = (int)$response['answers']["$phoneId"]['textAnswers'][0]['value'];
                    $name = $response['answers']["$nameId"]['textAnswers'][0]['value'];
                    $eventName = $response['answers']["$eventNameId"]['textAnswers'][0]['value'];
                    $date = $response['answers']["$dateId"]['textAnswers'][0]['value'];
                    $mail = $response['answers']["$mailId"]['textAnswers'][0]['value'];
                    $class = $response['answers']["$classId"]['textAnswers'][0]['value'];

                    $result = $mysqli->query("select * from participants where pnumber=$phoneNo");

                    if(count($result->fetch_all())){
                        //already exists so just go to the next response
                        continue;
                    }
                    else {
                        //does not exist so insert into db
                        $mysqli->query("insert into participants(pnumber,name,date,eventname,formId,email,class) values($phoneNo,'$name','$date','$eventName','$formId[0]','$mail','$class')");
                    }
                }
                $questionIds = [];

            }catch(Exception $e){
                if(gettype(strpos($e->getMessage(),"Requested entity was not found"))==="integer"){
                    $_SESSION['errors']['form_error'] = "Whoops! &nbsp; Incorrect Form ID! &nbsp; :(";
                    header("Location: ./error_view.php");
                }
            }
       }

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
                            <button name="submit" value="submit" class="otp-btn ml-5 hover:scale-90 px-3">Send OTP</button>
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
                                <?php $_SESSION['errors']['number_not_found'] = true; ?>
                                <?php $certImage = ""; ?>
                            }
                            else{
                                <?php $_SESSION['errors']['number_not_found'] = null; ?>
                            }
                        }
                    }


                    httpRequest.open("GET",`verify_number.php?number=${number}`,true)
                    httpRequest.send()
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
