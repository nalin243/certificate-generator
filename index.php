<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        require 'env_config.php';
        require 'db_config.php';

        $pname = "";
        $peventname = "";


        if(!count($_POST)==0){
            $number = (int) (strval($_POST['first']).strval($_POST['second']).strval($_POST['third']).strval($_POST['fourth']).strval($_POST['fifth']).strval($_POST['sixth']).strval($_POST['seventh']).strval($_POST['eighth']).strval($_POST['ninth']).strval($_POST['tenth']));

            $results = $mysqli->query("select * from participants where pnumber=$number ");
            $results = $results->fetch_all();
            
            if(!(count($results) == 0)){
                $pname = $results[0][1];
                $peventname = $results[0][3];
            }
            else {
                $peventname = "Not found";
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
           }

           $responses = $service->forms_responses->listFormsResponses($formId[0]);
           foreach($responses as $response){

                $phoneId = $questionIds['number'];
                $nameId = $questionIds['name'];
                $eventNameId = $questionIds['eventname'];
                $dateId = $questionIds['date'];

                $phoneNo = (int)$response['answers']["$phoneId"]['textAnswers'][0]['value'];
                $name = $response['answers']["$nameId"]['textAnswers'][0]['value'];
                $eventName = $response['answers']["$eventNameId"]['textAnswers'][0]['value'];
                $date = $response['answers']["$dateId"]['textAnswers'][0]['value'];

                $result = $mysqli->query("select * from participants where pnumber=$phoneNo");

                if(count($result->fetch_all())){
                    //already exists so just go to the next response
                    continue;
                }
                else {
                    //does not exist so insert into db
                    $mysqli->query("insert into participants(pnumber,name,date,eventname,formId) values($phoneNo,'$name','$date','$eventName','$formId[0]')");
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
    <div class="flex flex-col h-screen min-w-screen overflow-auto ">
        <div class="flex flex-col h-screen w-full shrink-0 ">
            <div class="flex h-3/6 w-full mt-24">
                <img src="./src/assets/srmlogo.png" class="p-15 m-auto h-full w-4/12">
            </div>
            <div class ="flex flex-col h-3/6 w-full">
                <div class="flex h-full w-full">
                    <h1 class="first-text m-auto">SRM Institute of Science and Technology</h1>
                </div>
                <div class="flex h-3/6 w-full">
                    <h1 class="second-text m-auto -mt-4">Department of Science and Humanities</h1>
                </div>
            </div>
            <div id="inputs" class="inputs flex h-1/6 mt-24 w-full -mt-18 justify-center">
                <form id="phone" name="phone" method="POST" action="index.php">
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
                    <button name="submit" value="submit" class="verify-btn ml-5 hover:scale-90 px-3">Verify</button>
                </form>
                
            </div>
            <script>
                const inputs = document.getElementById("inputs")
                const formElement = document.querySelector("#phone")

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
            <div class="flex h-1/6 w-full">
                <h1 class="text-black font-extrabold m-auto text-xl">Please Enter your Registered Phone Number.</h1>
            </div>
            <div class ="flex flex-col mt-20 h-full w-full">
                <div class="flex h-full w-full">
                    <h1 id="pname" class="text-black font-extrabold m-auto text-5xl"><?= $pname ?> </h1>
                </div>
                <div class="flex h-full w-full">
                    <h1 id="peventname" class="text-black font-bold m-auto -mt-4 text-3xl"><?= $peventname ?> </h1>
                </div>
            </div>
        </div>
        <div class="flex h-5/6 min-w-screen shrink-0 ">
            <div class="flex relative container h-5/6 w-7/12 m-auto my-0">
                <img src="./src/assets/testcert.png" class="cert-img"/>
                <div class="middle absolute .inset-0">
                    <button class="text">Click to Download</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
