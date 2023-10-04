<?php

    require 'db_config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty</title>

    <link rel="stylesheet" href="./public/index.css">
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./public/tailwind.css">
    
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
    <div class="flex flex-col page h-screen w-screen">
            <div class="flex flex-row h-0.5/6 w-full">
                <div class="flex header h-5/6 w-2/12">
                    <img src="./src/assets/srmist.png" class="h-full w-full scale-75 mr-auto mb-auto mt-auto ">
                </div>
                <div class="flex header h-5/6 w-full">
                    <h1 class="header-text mr-auto mb-auto mt-auto ml-10">Dean Dashboard,  Faculty of Science & Humanities. </h1>
                </div>
                <div class="flex header h-5/6 w-1/12">
                    <div class="px-3 flex m-auto">
                        <form method="POST" action="logout.php" class="m-auto">
                            
                            <button type="submit" name="submit" value="submit" class="text-center font-bold text-lg ">
                                <img class="h-5/6 w-3/12 m-auto" src="src/assets/logout.png" />
                                Logout
                            </button>
                        </form>
                    </div>     
                </div>
            </div>

            <div class="flex flex-col h-full w-full justify-center">
                <div class="flex flex-col h-5/6 w-4/6  m-auto">
                    <div class="flex flex-row h-1/6 w-full mb-3 m-auto">

                         <div class="flex h-full w-1/2 border-2 border-black rounded-md ">

                                <select id="dept" name="dept" class=" mx-5 w-full h-full bg-transparent cursor-pointer">

                                    <?php 
                                        $results = $mysqli->query("select * from users");
                                        $results = ($results->fetch_all());
                                        foreach($results as $result){
                                            $deptname = htmlspecialchars($result[2]);
                                            echo "<option value='$deptname'>$deptname</option>";
                                        }
                                     ?>
                                </select>

                            </div>

                             <div class='flex h-full w-1/2 bg-red-500 rounded-md ml-3 cursor-pointer grant-btn hover:bg-red-600 active:translate-y-2 justify-center'>
                                <h1 class='text-center text-3xl m-auto grant-btn-text'>Grant Permission</h1>
                             </div>
    

                           

                    </div>
                    <div class="flex flex-col h-full w-full m-auto">

                        <div class="flex h-2/6 w-full justify-start mt-10 border-2 border-black rounded-md">
                            <div class="flex h-full w-1/6">
                                <img class="h-4/6 w-4/6 m-auto cursor-pointer" src="src/assets/ename.png" />
                            </div>
                            <h1 id="eventnamedis" class="my-auto mr-auto ml-4 text-4xl"></h1>
                        </div>

                        <div class="flex h-2/6 w-full mt-5 border-2 border-black rounded-md">
                            <div class="flex h-full w-1/6">
                                <img class="h-4/6 w-4/6 m-auto cursor-pointer" src="src/assets/edate.png" />
                            </div>
                             <h1 id="eventdatedis" class="my-auto mr-auto ml-4 text-4xl"></h1>
                        </div>

                    </div>
                </div>
            </div>
    </div>
    <script>

        let permission = ""
        let eventdate = ""
        let eventname = ""

        let eventDetailCallback = function(){
            let dept = document.querySelector("#dept").selectedOptions[0].value

            const httpRequest = new XMLHttpRequest()

            httpRequest.onreadystatechange = ()=>{
                if(httpRequest.readyState===4 && httpRequest.status==200){
                    let res = JSON.parse((httpRequest.responseText).split("?")[0])

                    permission = res["permission"]
                    eventdate = res["eventdate"]
                    eventname = res["eventname"]

                    document.querySelector("#eventnamedis").innerText = `Event Name:  ${eventname}`
                    document.querySelector("#eventdatedis").innerText = `Event Date:  ${eventdate}`

                    if(permission){
                        document.querySelector(".grant-btn").classList.remove("bg-red-500")
                        document.querySelector(".grant-btn").classList.remove("hover:bg-red-600")

                        document.querySelector(".grant-btn").classList.add("bg-green-500")
                        document.querySelector(".grant-btn").classList.add("hover:bg-green-600")
                        document.querySelector(".grant-btn-text").innerText = "Revoke Permission"
                    } else {
                        if(!document.querySelector(".grant-btn").classList.contains("bg-red-500")){
                            document.querySelector(".grant-btn").classList.add("bg-red-500")
                            document.querySelector(".grant-btn").classList.add("hover:bg-red-600")
                        }
                        document.querySelector(".grant-btn").classList.remove("bg-green-500")
                        document.querySelector(".grant-btn").classList.remove("hover:bg-green-600")
                        document.querySelector(".grant-btn-text").innerText = "Grant Permission"
                    }

                }
            }

            httpRequest.open("GET",`get_event_details.php?deptname=${dept}`,true)
            httpRequest.send()
        }

        document.querySelector("#dept").addEventListener("input",eventDetailCallback)
        window.addEventListener("load",eventDetailCallback)

        document.querySelector(".grant-btn").addEventListener("click",(event)=>{

            const httpRequest = new XMLHttpRequest()

            if(permission)
                httpRequest.open("GET",`grant_permission.php?permission=${0}&eventname=${eventname}`,true)
            else
                httpRequest.open("GET",`grant_permission.php?permission=${1}&eventname=${eventname}`,true)

            httpRequest.send()

            eventDetailCallback()
        })
    </script>
</body>
</html>