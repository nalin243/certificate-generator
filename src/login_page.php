<?php

    require $_SERVER['DOCUMENT_ROOT']."/certificate-generator/" . 'config/session_config.php';

    if($_SESSION['user_username']){
        header("Location: ../src/config_page.php");
        die();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>

    <link rel="stylesheet" href="../public/index.css">
    <link rel="stylesheet" href="../public/tailwind.css">

      <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
    <div class="flex page h-screen w-screen">
        <div class="flex flex-row login-card h-4/6 w-8/12 m-auto ">
            <div class="flex flex-col h-full w-10/12 ">
                <div class="flex h-5/6 w-full ">
                    <img src="../public/assets/srmlogo.png" class="p-15 mr-auto mb-38 mt-auto ml-10 h-3/6 w-9/12">
                </div>
                <div class="h-full w-full">
                    <p class="font-bold text-xl card-info ml-14 mt-8">Welcome to FACULTY login,</p>
                    <p class="font-semibold text-lg card-info ml-14 mt-4 ">Please Enter your registered ID and Password to access the page.</p>
                </div>
            </div>
            <div class="flex flex-col h-full w-full">
                <div class="flex flex-col login h-4/6 w-9/12 m-auto">
                    <div class="flex login-top h-1/6 w-full">
                        <h1 class="m-auto text-md text-white font-bold">Faculty Log-In</h1>
                    </div>
                    <div class="flex flex-col mt-10 h-full w-full">

                        <form method="POST" action="../modules/Auth/login.php">
                            <div class="flex mx-auto text-field h-2/6 w-10/12">
                                <input name="username" class="login-page m-auto px-3 h-full w-full" type="text" required>
                                <label class="login-page-label -mt-1">Username</label>
                            </div>
                            <div class="flex mx-auto text-field h-2/6 w-10/12 ">
                                <input name="password" class="login-page m-auto px-3 h-full w-full" type="password" required>
                                <label class="login-page-label -mt-1">Password</label>
                            </div>
                            <div class="flex h-full w-full ">
                                <button name="submit" value="submit" class="faculty-btn hover:scale-90 active:translate-y-3 duration-500 px-3 ">Login</button>
                            </div>
                        </form>
                        <p class="text-center text-red-600 "><?php echo $_SESSION['errors']['wrong_password'] ? 'Incorrect Password' : '';
                                                                   echo $_SESSION['errors']['user_does_not_exist'] ? 'User not found' : '';
                                                                   $_SESSION['errors']['user_does_not_exist'] = false;
                                                                   $_SESSION['errors']['wrong_password'] = false; ?></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>
