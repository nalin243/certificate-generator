<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty</title>

    <link rel="stylesheet" href="./public/index.css">
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

    <?php

        require 'session_config.php';
        require 'env_config.php';

        if($_SESSION['user_username']){

            //this means there was a successful login
            require 'db_config.php';

           $message = "";

           $xname = (double)$_POST['xname'];
           $yname = (double)$_POST['yname'];
           $xdate = (double)$_POST['xdate'];
           $ydate = (double)$_POST['ydate'];
           $xyear = (double)$_POST['xyear'];
           $yyear = (double)$_POST['yyear'];
           $formid = $_POST['formid'];

            if(!count($_POST)==0){

                if($_FILES['template']['error']==0 && $_FILES['template']['size']>0){
                    //meaning that file has been uploaded without error

                    $results = $mysqli->query("select * from templates where formId='$formid' ");
                    $results = $results->fetch_all();

                    if(count($results)==0){//making sure not to accidentally add duplicate entries
                        $templateFile = addslashes(file_get_contents($_FILES['template']['tmp_name']));
                        $mysqli->query("insert into templates(formId,certTemplate,xname,yname,xdate,ydate,xyear,yyear) values('$formid','$templateFile',$xname,$yname,$xdate,$ydate,$xyear,$yyear)");
                    }
                }
            }
        }
        else {
            header("Location: ./login_view.php");
            die();
        }

    ?>

    <div class="flex flex-col h-screen min-w-screen overflow-auto ">
        <div class="flex flex-col h-screen w-full shrink-0  ">
            <div class="flex h-1/6 w-full ">
                <img src="./src/assets/srmlogo.png" class="p-15 mt-4 m-auto h-full w-3/12">
            </div>
            <div class="flex flex-row h-full w-full ">
                <div class="flex flex-col h-4/6 w-10/12 m-auto p-14">
                    <form id="configform" method="POST" action="config.php" enctype="multipart/form-data">

                        <input id="xname" name="xname" type="text" value="" class="hidden">
                        <input id="yname" name="yname" type="text" value=""  class="hidden">
                        <input id="xdate" name="xdate" type="text" value=""  class="hidden">
                        <input id="ydate" name="ydate" type="text" value=""  class="hidden">
                        <input id="xyear" name="xyear" type="text" value=""  class="hidden">
                        <input id="yyear" name="yyear" type="text" value=""  class="hidden">

                        <div class="flex h-full w-full justify-center ">
                            <h1><?php echo $message ?></h1>
                        <input type="text" class="form-id p-4 pl-4 rounded-lg placeholder-gray-400" placeholder="Enter the form id..." name="formid"/>
                        </div>
                        <div class="flex flex-row h-full w-full  ">
                            <div class="flex h-full w-full  ">
                                <div name="name" id="name"  class="cursor-pointer faculty-btn text-center py-2 hover:scale-90 px-3">Name</div>
                            </div>
                            <div class="flex h-full w-full ">
                                <div name="date" id="date" class="cursor-pointer faculty-btn text-center py-2 hover:scale-90 px-3">Date</div>
                            </div>
                            <div class="flex h-full w-full ">
                                <div name="year" id="year" class="cursor-pointer faculty-btn text-center py-2 hover:scale-90 px-3">Year</div>
                            </div>
                        </div>
                        <div class="flex h-full w-full ">
                            <div class="faculty-btn hover:scale-90 px-3 flex justify-center">
                                <button type="submit" name="submit" value="submit" class="text-center">Submit</button>
                            </div>
                        </form>
                            <div class="faculty-btn hover:scale-90 px-3 flex justify-center">
                                <form method="POST" action="logout.php" class="m-auto">
                                    <button type="submit" name="submit" value="submit" class="text-center">Log out</button>
                                </form>
                            </div>
                        </div>
                </div>
                <div id="imgcontainerunique" class=" flex h-5/6 w-11/12 m-auto p-12">
                    <div class="imgcontainer relative flex flex-col cert-drop h-full w-full">
                            <img id="preview" src="" class="upload-img m-auto hidden" />
                            <label id="custom-file-upload" class="m-auto text-2xl text-gray-400 font-bold">
                                <input name="template" form="configform" id="custom-file-input" type="file" accept="image/png"/>
                                Upload File
                            </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        var newimgwidth = 0
        var newimgheight = 0

        const  arr = new Array()

        document.getElementById("custom-file-input").addEventListener("change",(event)=>{

            document.getElementById("preview").classList.remove("hidden")

            const image = document.querySelector(".upload-img")


            image.src = URL.createObjectURL(event.target.files[0])
            document.getElementById("custom-file-upload").classList.add("hidden")


            const imgElement = document.createElement('img')
            const selectedImage = event.target.files[0]
            const objectUrl = URL.createObjectURL(selectedImage)

            imgElement.src = objectUrl

            imgElement.onload = ()=>{
                newimgwidth = imgElement.width 
                newimgheight = imgElement.height
            }

        })

         document.querySelector(".imgcontainer").addEventListener("click",(event)=>{
                document.getElementById("name").classList.remove("bg-blue-900")
                document.getElementById("name").classList.remove("scale-90")

                document.getElementById("year").classList.remove("bg-blue-900")
                document.getElementById("year").classList.remove("scale-90")

                document.getElementById("date").classList.remove("bg-blue-900")
                document.getElementById("date").classList.remove("scale-90")

               const bounds = event.currentTarget.getBoundingClientRect()
               const xcoordinate = event.clientX - bounds.left
               const ycoordinate = event.clientY - bounds.top

               const oldimgwidth = event.currentTarget.getBoundingClientRect().width
               const oldimgheight = event.currentTarget.getBoundingClientRect().height

               const newxcoordinate = (newimgwidth/oldimgwidth) * xcoordinate
               const newycoordinate = (newimgheight/oldimgheight) * ycoordinate

                const xname = document.getElementById("xname")
                const yname = document.getElementById("yname")

                const xdate = document.getElementById("xdate")
                const ydate = document.getElementById("ydate")

                const xyear = document.getElementById("xyear")
                const yyear = document.getElementById("yyear")

                if(arr[0]=="name"){
                    xname.value = newxcoordinate
                    yname.value = newycoordinate
                    arr.splice(0,1)
                }
                if(arr[0]=="date"){
                    xdate.value = newxcoordinate
                    ydate.value = newycoordinate
                    arr.splice(0,1)
                } 
                if(arr[0]=="year"){
                    xyear.value = newxcoordinate
                    yyear.value = newycoordinate
                    arr.splice(0,1)
                }
            })

        document.getElementById("name").addEventListener("click",(event)=>{
            arr.push("name")
            document.getElementById("name").classList.add("bg-blue-900")
            document.getElementById("name").classList.add("scale-90")
        })

        document.getElementById("date").addEventListener("click",(event)=>{
            arr.push("date")
            document.getElementById("date").classList.add("bg-blue-900")
            document.getElementById("date").classList.add("scale-90")
        })

         document.getElementById("year").addEventListener("click",(event)=>{
            arr.push("year")
            document.getElementById("year").classList.add("bg-blue-900")
            document.getElementById("year").classList.add("scale-90")
        })



    </script>
</body>
</html>