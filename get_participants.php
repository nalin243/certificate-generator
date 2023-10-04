<?php
    session_start();
    
    require_once 'db_config.php';
    require_once 'env_config.php';

    define("DEV_MODE",$_ENV['DEV_MODE']);

    $client = new Google\Client;
    $client->setAuthConfig("client_secret.json");
    $client->setApplicationName("Certficate-generator");
    $client->setScopes(['https://www.googleapis.com/auth/forms','https://www.googleapis.com/auth/drive']);
    $service = new Google\Service\Forms($client);

    $formIds = (($mysqli->query("select formId from templates"))->fetch_all());

    foreach($formIds as $formId){

        try{

            $permission = $mysqli->query("select allowed from templates where formId='$formId[0]' ");
            $permission = (bool)($permission->fetch_all()[0][0]);

            if($permission){

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

                if(DEV_MODE!="true"){
                    $result = $mysqli->query("select * from participants");
                    $result = $result->fetch_all();

                    $dbParticipantList = $result;

                    foreach($dbParticipantList as $dbParticipant){
                        $exists = false;
                        foreach($response as $responses){
                            if($dbParticipant[0]==$response['answers']["$phoneId"]['textAnswers'][0]['value']){
                                $exists = true;
                                break;
                            }
                        }
                        if($exists==false){
                            $mysqli->query("delete from participants where pnumber='$dbParticipant[0]' ");
                        }
                        
                    }
                }

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

                    $mail = "";
                    if(!is_null($response->getRespondentEmail()))
                        $mail = $response->getRespondentEmail();
                    else
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
            } else {
                //meaning that the event does not have permission to disseminate certificates yet
                //just do nothing
                    $result = $mysqli->query("select * from participants");
                    $result = $result->fetch_all();

                    $dbParticipantList = $result;

                    foreach($dbParticipantList as $dbParticipant){
                        $permission = $mysqli->query("select allowed from templates where formId='$dbParticipant[4]'");//4 is formid
                        $permission = (bool)($permission->fetch_all()[0][0]);

                        if($permission==false){
                            $mysqli->query("delete from participants where pnumber='$dbParticipant[0]' ");
                        }
                        
                    } 
            }

        }catch(Exception $e){
            if(gettype(strpos($e->getMessage(),"Requested entity was not found"))==="integer"){
                $_SESSION['errors']['form_error'] = "Whoops! ☹️ &nbsp; Incorrect Form ID! &nbsp; :(";
                header("Location: ./error_view.php");
            }
        }
    }

?>