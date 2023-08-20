<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        require 'vendor/autoload.php';

       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
       $dotenv->load();

       $client = new Google\Client;
       $client->setAuthConfig("client_secret.json");
       $client->setApplicationName("Certficate-generator");
       $client->setScopes(['https://www.googleapis.com/auth/forms','https://www.googleapis.com/auth/drive']);

     ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerification</title>

    <link rel="stylesheet" href="./public/index.css">
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="flex h-full w-full mt-28 justify-center">
        <img src="./src/assets/srmlogo.png" class="p-15 mr-10 h-4/6 w-4/12">
    </div>
    <div class ="flex flex-col  h-full w-full mt-24">
        <div class="flex h-full w-full  justify-center">
            <h1 class="first-text">SRM Institute of Science and Technology</h1>
        </div>
        <div class="flex h-full w-full justify-center">
            <h1 class="second-text">Department of Science and Humanities</h1>
        </div>
    </div>
    <div class="flex h-full w-full mt-28 justify-center">
            <input type="number" class="flex bg-white border-2 border-black w-4/12 p-3 pl-4 rounded-2xl placeholder-gray-400" placeholder="Enter Registered Phone Number..." required/>
            <button class="verify-btn ml-10 hover:scale-90 duration-500 rounded-xl px-8 ">VERIFY</button>
    </div>

</body>
</html>