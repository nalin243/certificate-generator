<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
    //     require 'vendor/autoload.php';

    //    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    //    $dotenv->load();
     ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerification</title>

    <link rel="stylesheet" href="./public/index.css">
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="flex flex-col h-screen w-full border-4 border-black overflow-auto">
        <div class="flex flex-col h-full w-full border-4 border-black shrink-0">
            <div class="flex h-full w-full mt-28 justify-center">
                <img src="./src/assets/srmlogo.png" class="p-15 mr-10 h-4/6 w-3/12">
            </div>
            <div class ="flex flex-col  h-full w-full -mt-8">
                <div class="flex h-full w-full  justify-center">
                    <h1 class="first-text">SRM Institute of Science and Technology</h1>
                </div>
                <div class="flex h-full w-full -mt-24 justify-center">
                    <h1 class="second-text">Department of Science and Humanities</h1>
                </div>
            </div>
            <div class="flex h-4/6 w-full -mt-18 justify-center">
                <input type="number" class="flex bg-white h-2/6 border-2 border-black w-4/12 pl-3 rounded-2xl placeholder-gray-400" placeholder="Enter Registered Phone Number..." required/>
                <button class="verify-btn ml-5 hover:scale-90 h-2/6 px-3 ">VERIFY</button>
            </div>
            <div class ="flex flex-col h-full w-full">
                <div class="flex h-full w-full justify-center">
                    <h1 class="text-black font-extrabold text-5xl">example name</h1>
                </div>
                <div class="flex h-full w-full justify-center">
                    <h1 class="text-black font-bold text-3xl">example certificate name</h1>
                </div>
            </div>
        </div>
        <div class="flex h-screen border-4 border-black shrink-0">

        </div>
    </div>
</body>
</html>