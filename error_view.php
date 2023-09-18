<!DOCTYPE html>
<html lang="en">

<?php

session_start();

// $error_message = $_SESSION['errors']['template_error'];
if(isset($_SESSION['errors']['template_error'])){
    $error_message = $_SESSION['errors']['template_error'];
} else if(isset($_SESSION['errors']['form_error'])){
    $error_message = $_SESSION['errors']['form_error'];
}else if(isset($_SESSION['errors']['email_error'])){
    $error_message = $_SESSION['errors']['email_error'];
}else if(isset($_SESSION['errors']['participant_error'])){
    $error_message = $_SESSION['errors']['participant_error'];
}

foreach($_SESSION as $sessionVar=>$value){
    if($sessionVar!=="errors")
        unset($_SESSION["$sessionVar"]);
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerification</title>

    <link rel="stylesheet" href="./public/index.css">
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>    
</head>
<body>
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
            <div class ="flex flex-col mt-2 h-full w-full">
            <div class="flex h-2/6 w-full justify-center text-center">
                    <h1 id="error-message" class="text-black  m-auto text-red-800 font-bold mt-20"><?= $error_message ?></h1>
                </div>
            </div>
        </div>
    </div>
</html>
