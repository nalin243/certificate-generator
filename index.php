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
<body OnLoad="document.phone.first.focus();">
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
            <div id="inputs" class="inputs flex h-4/6 w-full -mt-18 justify-center">
                <form id="phone" name="phone">
                    <input class="input" name="first" type="text" inputmode="numeric" maxlength="1" />
                    <input class="input" type="text" inputmode="numeric" maxlength="1" />
                    <input class="input" type="text" inputmode="numeric" maxlength="1" />
                    <input class="input" type="text" inputmode="numeric" maxlength="1" />
                    <input class="input" type="text" inputmode="numeric" maxlength="1" />
                    <input class="input" type="text" inputmode="numeric" maxlength="1" />
                    <input class="input" type="text" inputmode="numeric" maxlength="1" />
                    <input class="input" type="text" inputmode="numeric" maxlength="1" />
                    <input class="input" type="text" inputmode="numeric" maxlength="1" />
                    <input class="input" type="text" inputmode="numeric" maxlength="1" />
                </form>
                <button class="verify-btn ml-5 hover:scale-90 h-2/6 px-3 ">VERIFY</button>
            </div>
            <script>
                const inputs = document.getElementById("inputs");
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
            <div class="flex h-3/6 -mt-24 w-full justify-center">
                <h1 class="text-black font-extrabold text-xl">Please Enter your Registered Phone Number.</h1>
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