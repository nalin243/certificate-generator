<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty</title>

    <link rel="stylesheet" href="../public/index.css">
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/tailwind.css">
    
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>

    <?php

        require $_SERVER['DOCUMENT_ROOT']."/certificate-generator/" . 'config/session_config.php';;
        require $_SERVER['DOCUMENT_ROOT']."/certificate-generator/" . 'config/env_config.php';

        if($_SESSION['user_username']){

            //this means there was a successful login
            require $_SERVER['DOCUMENT_ROOT']."/certificate-generator/" . 'config/db_config.php';

            $message = "";

            $xname = (double)$_POST['xname'];
            $yname = (double)$_POST['yname'];
            $namewidth = (double)$_POST['namewidth'];
            $xdate = (double)$_POST['xdate'];
            $ydate = (double)$_POST['ydate'];
            $datewidth = (double)$_POST['datewidth'];
            $xyear = (double)$_POST['xyear'];
            $yyear = (double)$_POST['yyear'];
            $yearwidth = (double)$_POST['yearwidth'];
            $xevent = (double)$_POST['xevent'];
            $yevent = (double)$_POST['yevent'];
            $eventwidth = (double)$_POST['eventwidth'];

            $nameFont = $_POST['nameFont'];
            $nameFontSize = (double)$_POST['nameFontSize'];
            $nameColor = $_POST['nameColor'];

            $dateFont = $_POST['dateFont'];
            $dateFontSize = (double)$_POST['dateFontSize'];
            $dateColor = $_POST['dateColor'];

            $yearFont = $_POST['yearFont'];
            $yearFontSize = (double)$_POST['yearFontSize'];
            $yearColor = $_POST['yearColor'];

            $eventFont = $_POST['eventFont'];
            $eventFontSize = (double)$_POST['eventFontSize'];
            $eventColor = $_POST['eventColor'];

            $newimgheight = $_POST['newimgheight'];
            $newimgwidth = $_POST['newimgwidth'];

            $formid = $_POST['formid'];
            $eventname = $_POST['eventname'];
            $date = $_POST['datestring'];

            if(!count($_POST)==0){

                if($_FILES['template']['error']==0 && $_FILES['template']['size']>0){
                    //meaning that file has been uploaded without error

                    $results = $mysqli->query("select * from templates where formId='$formid' ");
                    $results = $results->fetch_all();

                    if(count($results)==0){//making sure not to accidentally add duplicate entries

                        $username = $_SESSION['user_username'];

                        $imgstring = file_get_contents($_FILES['template']['tmp_name']);
                        $img = imagecreatefromstring($imgstring);

                        $img = imagescale($img,$newimgwidth,$newimgheight);

                        ob_start();
                        imagepng($img);
                        $templateFile = base64_encode(ob_get_clean());

                        $deptname = $mysqli->query("select deptname from users where username='$username'");
                        $deptname = ($deptname->fetch_all())[0][0];

                        $mysqli->query("insert into templates(formId,certTemplate,xname,yname,xdate,ydate,xyear,yyear,xevent,yevent,namefont,namefontsize,namecolor,datefont,datefontsize,datecolor,yearfont,yearfontsize,yearcolor,eventfont,eventfontsize,eventcolor,date,eventname,deptname,namewidth,datewidth,yearwidth,eventwidth) values('$formid','$templateFile',$xname,$yname,$xdate,$ydate,$xyear,$yyear,$xevent,$yevent,'$nameFont',$nameFontSize,'$nameColor','$dateFont',$dateFontSize,'$dateColor','$yearFont',$yearFontSize,'$yearColor','$eventFont',$eventFontSize,'$eventColor','$date','$eventname','$deptname',$namewidth,$datewidth,$yearwidth,$eventwidth)");
                    }
                }
            }
        }
        else {
            header("Location: /certificate-generator/src/login_page.php");
            die();
        }

    ?>

    <div class="flex flex-col  h-screen w-screen   ">
        <div class="flex flex-col page h-full w-full overflow-y-scroll shrink-0 ">
            <div class="flex flex-row h-0.5/6 w-full   ">
                <div class="flex header h-5/6 w-2/12">
                    <img src="../public/assets/srmist.png" class="h-full w-full scale-75 mr-auto mb-auto mt-auto ">
                </div>
                <div class="flex header h-5/6 w-full">
                    <h1 class="header-text mr-auto mb-auto mt-auto ml-10">Certificate Configuration Dashboard,  Faculty of Science & Humanities. </h1>
                </div>
                <div class="flex header h-5/6 w-1/12">
                    <div class="px-3 flex m-auto">
                        <form method="POST" action="../modules/Auth/logout.php" class="m-auto">
                            
                            <button type="submit" name="submit" value="submit" class="text-center font-bold text-lg ">
                                <img class="h-5/6 w-3/12 m-auto active:translate-y-1 " src="../public/assets/logout.png" />
                                Logout
                            </button>
                        </form>
                    </div>     
                </div>
            </div>
            <div class="flex flex-row h-screen w-screen h-full w-full">
            
                <div class="flex flex-col h-full w-full">
                    <div class="flex flex-row  h-0.5/6 w-11/12 m-auto">
                        <div class="flex flex-col h-full w-1/12 justify-center ml-8  ">
                            <div class="flex h-full w-full  ">
                                <img class="h-2/6 w-4/12 mt-10 m-auto" src="../public/assets/dropper.png" />
                            </div>
                            <div class="flex justify-center h-10 w-15 rounded-md ">
                                <input id="color" name="color" class="live h-3/6 w-6/12 mb-5 bg-transparent m-auto" type="color"/>
                            </div>
                        </div>
                        <div class="flex flex-col h-full w-1/12 justify-center ml-10   ">
                            <div class="flex h-full w-full  ">
                                <img class="h-2/6 w-5/12 mt-10" src="../public/assets/font.png" />
                            </div>
                            <div class="flex justify-center h-10 w-15 rounded-md ">
                                <input value="30" id="fontsize" name="fontsize" class="live mb-5 bg-transparent m-auto w-11/12" type="number"/>
                            </div>
                        </div>
                        <div class="flex flex-col h-full w-2/12 justify-center ml-10   ">
                            <div class="flex h-full w-full ">
                                <h1 class="font-bold text-sm  mt-10">FONT STYLE</h1>
                            </div>
                            <div class="flex  h-full w-full rounded-md ">
                                <select id="font" name="font" class="live w-11/12 h-5/6 bg-transparent">
                                <option value="certasans.ttf">Certa Sans</option>
                                <option value="shortbaby.ttf">Short Baby</option>
                                <option value="OpenSans-Regular.ttf">Open Sans Regular</option>
                                <option value="OpenSans-Bold.ttf">Open Sans Bold</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex h-full w-5/12 -mr-6 justify-center   ">
                            
                        </div>
                        <div class="flex h-full w-1/12 mt-3 ml-5 justify-end">
                            <div class="flex justify-end h-full w-full rounded-md">
                                <img id="reset" class="h-1/6 w-3/12 m-auto cursor-pointer active:translate-y-1" src="../public/assets/reset.png" />
                            </div>
                        </div>
                    </div>
                    <div id="imgcontainerunique" class="ml-6 flex flex-col h-5/6 w-11/12 pt-10 px-10">
                        <div class="imgcontainer relative flex flex-col cert-drop h-full -mt-10 w-full ">
                                <img id="preview" src="" class="z-10 upload-img m-auto hidden" />
                                <canvas class="z-20 absolute inset-0" id="test" ></canvas>
                                <canvas class="z-10 absolute inset-0 border-8 border-red-900" id="liveview"></canvas>
                                <label id="custom-file-upload" class="m-auto text-2xl text-gray-400 font-bold">
                                    <input name="template" form="configform" id="custom-file-input" type="file" accept="image/png"/>
                                        Upload File
                                </label>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row h-full w-8/12 ">
                    <div class="flex flex-col h-5/6 w-full  m-auto p-14 mt-10 ">
                        <form class="h-full" id="configform" method="POST" action="config_page.php" enctype="multipart/form-data">

                            <input id="xname" name="xname" type="text" value="" class="live hidden">
                            <input id="yname" name="yname" type="text" value=""  class="live hidden">
                            <input id="namewidth" name="namewidth" type="text" value="" class="live hidden">
                            <input id="xdate" name="xdate" type="text" value=""  class="live hidden">
                            <input id="ydate" name="ydate" type="text" value=""  class="live hidden">
                            <input id="datewidth" name="datewidth" type="text" value="" class="live hidden">
                            <input id="xyear" name="xyear" type="text" value=""  class="live hidden">
                            <input id="yyear" name="yyear" type="text" value=""  class="live hidden">
                            <input id="yearwidth" name="yearwidth" type="text" value="" class="live hidden">
                            <input id="xevent" name="xevent" type="text" value=""  class="live hidden">
                            <input id="yevent" name="yevent" type="text" value=""  class="live hidden">
                            <input id="eventwidth" name="eventwidth" type="text" value="" class="live hidden">
                            <input id="newimgheight" name="newimgheight" type="text" value=""  class="live hidden">
                            <input id="newimgwidth" name="newimgwidth" type="text" value=""  class="live hidden">

                            <input id="nameFont" name="nameFont" type="text" value=""  class="live hidden">
                            <input id="nameFontSize" name="nameFontSize" type="text" value=""  class="live hidden">
                            <input id="nameColor" name="nameColor" type="text" value=""  class="live hidden">

                            <input id="dateFont" name="dateFont" type="text" value=""  class="live hidden">
                            <input id="dateFontSize" name="dateFontSize" type="text" value=""  class="live hidden">
                            <input id="dateColor" name="dateColor" type="text" value=""  class="live hidden">

                            <input id="yearFont" name="yearFont" type="text" value=""  class="live hidden">
                            <input id="yearFontSize" name="yearFontSize" type="text" value=""  class="live hidden">
                            <input id="yearColor" name="yearColor" type="text" value=""  class="live hidden">

                            <input id="eventFont" name="eventFont" type="text" value=""  class="live hidden">
                            <input id="eventFontSize" name="eventFontSize" type="text" value=""  class="live hidden">
                            <input id="eventColor" name="eventColor" type="text" value=""  class="live hidden">

                            <div class="flex flex-col h-full w-full   ">

                                <div class="flex flex-col h-full w-full ">
                                    <div class="flex h-full w-full justify-center ">
                                        <input id="formid" type="text" class="config-input p-4 pl-4 rounded-lg placeholder-gray-400 w-11/12" placeholder="Enter the form id" name="formid"/>
                                    </div>

                                    <div class="flex flex-row h-full w-full ">
                                        <div class="flex h-full w-full justify-center  ">
                                            <input id="eventname" type="text" class="live config-input w-10/12 mr-4 p-4 pl-4 rounded-lg placeholder-gray-400" placeholder="Enter the event name" name="eventname"/>
                                        </div>

                                        <div class="flex h-full w-full justify-center   ">
                                            <input id="datestring" type="text" class="live config-input w-10/12 ml-4 p-4 pl-4 rounded-lg placeholder-gray-400" placeholder="Enter the event date" name="datestring"/>
                                        </div>
                                    </div>

                                </div>

                                <div class="flex flex-col h-full w-full  ">

                                    <div class="flex flex-row h-full w-full  ">
                                        <div class="flex h-full w-full  ">
                                            <div name="name" id="name" class="flex flex-row h-3/6 w-8/12 active:translate-y-1  m-auto ">
                                                <div class="flex h-full w-4/12  m-auto ">
                                                    <img class="h-4/6 w-full m-auto cursor-pointer" src="../public/assets/student.png" />
                                                </div>
                                                <div class="flex h-full w-full m-auto ">

                                                    <h1 class="font-bold cursor-pointer text-lg m-auto">Student Name</h1>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="flex h-full w-full  ">
                                            <div name="year" id="year" class="flex h-3/6 w-8/12 active:translate-y-1  m-auto ">
                                                <div class="flex h-full w-4/12 m-auto ">
                                                    <img class="h-4/6 w-full m-auto cursor-pointer" src="../public/assets/class.png" />
                                                </div>
                                                <div class="flex h-full w-full m-auto ">

                                                    <h1 class="font-bold text-lg m-auto cursor-pointer">Class</h1>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-row h-full w-full  ">
                                        <div class="flex h-full w-full  ">
                                            <div name="event" id="event" class="flex h-3/6 w-8/12 active:translate-y-1  m-auto ">
                                                <div class="flex h-full w-4/12 m-auto ">
                                                    <img class="h-4/6 w-full m-auto cursor-pointer" src="../public/assets/ename.png" />
                                                </div>
                                                <div class="flex h-full w-full m-auto ">

                                                    <h1 class="font-bold text-lg m-auto cursor-pointer">Event Name</h1>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="flex h-full w-full  ">
                                            <div name="date" id="date" class="flex active:translate-y-1 h-3/6 w-8/12 m-auto ">
                                                <div class="flex h-full w-4/12 m-auto ">
                                                    <img class="h-4/6 w-full m-auto cursor-pointer" src="../public/assets/edate.png" />
                                                </div>
                                                <div class="flex h-full w-full m-auto ">

                                                    <h1 class="font-bold text-lg m-auto cursor-pointer">Event Date</h1>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                
                                <div class="flex h-1/6 w-full    ">
                                    <div class="faculty-btn hover:scale-90 active:translate-y-3 duration-500 px-3 flex justify-center">
                                        <button type="submit" name="submit" value="submit" class="text-center">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        //Canvas code for interactive rectangle taken from https://medium.com/variance-digital/interactive-rectangular-selection-on-a-responsive-image-761ebe24280c

        let nameClicked = dateClicked = eventClicked = yearClicked = false

        // const canvasElement = document.querySelector("#test")
        // const context = canvasElement.getContext("2d")

        const canvasLiveView = document.querySelector("#liveview")
        const contextLiveView = canvasLiveView.getContext("2d")

        // const imgWidth = document.querySelector("#preview").width
        // const imgHeight = document.querySelector("#preview").height 

        // canvasElement.width = imgWidth
        // canvasElement.height = imgHeight

        // canvasLiveView.width = imgWidth 
        // canvasLiveView.height = imgHeight

        const shortbaby = new FontFace("shortbaby","url('../fonts/shortbaby.ttf')")
        const certasans = new FontFace("certasans","url('../fonts/certasans.ttf')")
        const OpenSansRegular = new FontFace("OpenSans-Regular","url('../fonts/OpenSans-Regular.ttf')")
        const OpenSansBold = new FontFace("OpenSans-Bold","url('../fonts/OpenSans-Bold.ttf')")

        document.fonts.add(shortbaby)
        document.fonts.add(certasans)
        document.fonts.add(OpenSansRegular)
        document.fonts.add(OpenSansBold)

        var previewText = ""

        var eventname = document.querySelector("#eventname").value
        var date = document.querySelector("#datestring").value 

        var image = document.getElementById('preview');
        var canvas = document.getElementById('test')

        //hidden or text inputs
        var h_th_left = document.getElementById('thb_left')
        var h_th_top = document.getElementById('thb_top')
        var h_th_right = document.getElementById('thb_right')
        var h_th_bottom = document.getElementById('thb_bottom')

        var handleRadius = 15
        var dragTL = dragBL = dragTR = dragBR = dragMTop = dragMBottom = dragRMid = dragLMid = false;
        var dragWholeRect = false;

        var rect={}
        var current_canvas_rect={}

        var mouseX, mouseY
        var startX, startY

        var th_left = 504;
        var th_top = 0;
        var th_right = 3528;
        var th_bottom = 3024;

        var th_width = th_right - th_left;
        var th_height = th_bottom - th_top;

        var effective_image_width = 4032;
        var effective_image_height = 3024;

        //drawRectInCanvas() connected functions -- START
        function updateHiddenInputs(){
          var inverse_ratio_w =  effective_image_width / canvas.width;
          var inverse_ratio_h = effective_image_height / canvas.height ;
          h_th_left.value = Math.round(rect.left * inverse_ratio_w)
          h_th_top.value = Math.round(rect.top * inverse_ratio_h)
          h_th_right.value = Math.round((rect.left + rect.width) * inverse_ratio_w)
          h_th_bottom.value = Math.round((rect.top + rect.height) * inverse_ratio_h)
        }

        function drawCircle(x, y, radius) {
          var ctx = canvas.getContext("2d");
          ctx.fillStyle = "#D70040";
          ctx.beginPath();
          ctx.arc(x, y, radius, 0, 2 * Math.PI);
          ctx.fill();
        }

        function drawHandles() {
          // drawCircle(rect.left, rect.top, handleRadius);
          // drawCircle(rect.left+rect.width/2,rect.top,handleRadius);//middle top
          // drawCircle(rect.left + rect.width, rect.top, handleRadius);
          // drawCircle(rect.left + rect.width, rect.top + rect.height, handleRadius);

          // drawCircle(rect.left, rect.top + rect.height, handleRadius);

          drawCircle(rect.left, rect.top + rect.height/2, handleRadius);//left mid
          drawCircle(rect.left+rect.width, rect.top + rect.height/2, handleRadius);//right mid


          // drawCircle(rect.left+rect.width/2, rect.top + rect.height, handleRadius);//bottom middle

        }


        function drawRectInCanvas()
        {
          var ctx = canvas.getContext("2d")
          ctx.clearRect(0, 0, canvas.width, canvas.height)//clear canvas with draggable rectangle

          const contextLiveView = canvasLiveView.getContext("2d")

          contextLiveView.clearRect(0,0,canvas.width,canvas.height)//clear canvas with text
          
          let textwidth = contextLiveView.measureText(previewText).width
          contextLiveView.fillText(previewText, rectcenterx-(textwidth/2), rectcentery)

          ctx.beginPath()
          ctx.lineWidth = "3"
          ctx.fillStyle = "rgba(199, 87, 231, 0.09)"
          ctx.strokeStyle = "#000000"
          ctx.rect(rect.left, rect.top, rect.width, rect.height)
          ctx.fill()
          ctx.stroke()
          drawHandles()
          updateHiddenInputs()
        }
        //drawRectInCanvas() connected functions -- END

        function mouseUp(e) {
          dragTL = dragTR = dragBL = dragBR = dragMTop = dragMBottom = dragLMid = dragRMid = false;
          dragWholeRect = false;
        }

        //mousedown connected functions -- START
        function checkInRect(x, y, r) {
          return (x>r.left && x<(r.width+r.left)) && (y>r.top && y<(r.top+r.height));
        }

        function checkCloseEnough(p1, p2) {
          return Math.abs(p1 - p2) <= handleRadius*5;
        }

        function getMousePos(canvas, evt) {
          var clx, cly
          if (evt.type == "touchstart" || evt.type == "touchmove") {
            clx = evt.touches[0].clientX;
            cly = evt.touches[0].clientY;
          } else {
            clx = evt.clientX;
            cly = evt.clientY;
          }
          var boundingRect = canvas.getBoundingClientRect();
          return {
            x: clx - boundingRect.left,
            y: cly - boundingRect.top
          };
        }

        function mouseDown(e) { 
          var pos = getMousePos(this,e);
          mouseX = pos.x;
          mouseY = pos.y;
          // 0. inside movable rectangle
          if (checkInRect(mouseX, mouseY, rect)){
              dragWholeRect=true;
              startX = mouseX;
              startY = mouseY;
          }
          // //middle top
          // else if(checkCloseEnough(mouseX, rect.left+ (rect.width/2)) && checkCloseEnough(mouseY, rect.top)){
          //   dragMTop = true;
          // }
          // //middle bottom
          //  else if(checkCloseEnough(mouseX, rect.left + (rect.width/2)) && checkCloseEnough(mouseY, rect.top + rect.height)){
          //   dragMBottom = true;
          // }
          // left mid
          else if(checkCloseEnough(mouseX, rect.left) && checkCloseEnough(mouseY, rect.top + rect.height/2)){
            dragLMid = true;
          }
          //right mid
          else if(checkCloseEnough(mouseX, rect.left+rect.width) && checkCloseEnough(mouseY, rect.top + rect.height/2)){
            dragRMid = true;
          }


          // (5.) none of them
          else {
              // handle not resizing
          }
          drawRectInCanvas();
        }
        //mousedown connected functions -- END

        function mouseMove(e) {   

        //if mouse is moving then check which button has been clicked
        if(nameClicked){
            update_name_coords()
            previewText = "John Doe"
        }
        if(dateClicked){
            update_date_coords()
            previewText = date 
        }
        if(eventClicked){
            update_event_coords()
            previewText = eventname
        }
        if(yearClicked){
            update_year_coords()
            previewText = "III Preview"
        }


          var pos = getMousePos(this,e);
          mouseX = pos.x;
          mouseY = pos.y;

          if (dragWholeRect) {
              e.preventDefault();
              e.stopPropagation();
              dx = mouseX - startX;
              dy = mouseY - startY;
              if ((rect.left+dx)>0 && (rect.left+dx+rect.width)<canvas.width){
                rect.left += dx;
              }
              if ((rect.top+dy)>0 && (rect.top+dy+rect.height)<canvas.height){
                rect.top += dy;
              }
              startX = mouseX;
              startY = mouseY;
          }

          // else if (dragMTop){
          //       e.preventDefault();
          //       e.stopPropagation();

          //       var newSide = Math.abs(rect.height + rect.top - mouseY);
           
          //       rect.top = rect.height + rect.top - newSide;
          //       rect.height = newSide;
          // }
          // else if (dragMBottom){
          //       e.preventDefault();
          //       e.stopPropagation();
          //       var newSide = Math.abs(rect.top - mouseY);

          //       rect.height = newSide;
          // }
          else if (dragRMid){
                e.preventDefault();
                e.stopPropagation();
                var newSide = Math.abs(rect.left - mouseX);
        
                rect.width = newSide;
          }
           else if (dragLMid){
                var newSide = Math.abs(rect.left+rect.width - mouseX);

                rect.left = rect.left + rect.width - newSide;
                rect.width = newSide;
            }
          drawRectInCanvas();
        }

        function updateCurrentCanvasRect(){
          current_canvas_rect.height = canvas.height
          current_canvas_rect.width = canvas.width
          current_canvas_rect.top = image.offsetTop
          current_canvas_rect.left = image.offsetLeft
        }

        function repositionCanvas(){
          //make canvas same as image, which may have changed size and position
          canvas.height = image.height;
          canvas.width = image.width;
          canvas.style.top = image.offsetTop + "px";;
          canvas.style.left = image.offsetLeft + "px";
          //compute ratio comparing the NEW canvas rect with the OLD (current)
          var ratio_w = canvas.width / current_canvas_rect.width;
          var ratio_h = canvas.height / current_canvas_rect.height;
          //update rect coordinates
          rect.top = rect.top * ratio_h;
          rect.left = rect.left * ratio_w;
          rect.height = rect.height * ratio_h;
          rect.width = rect.width * ratio_w;
          updateCurrentCanvasRect();
          drawRectInCanvas();
        }

        function initCanvas(){
            canvas.height = image.height;
            canvas.width = image.width;
            canvas.style.top = image.offsetTop + "px";;
            canvas.style.left = image.offsetLeft + "px";
            updateCurrentCanvasRect();

            canvasLiveView.height = canvas.height
            canvasLiveView.width = canvas.width
            canvasLiveView.style.top = image.offsetTop + "px";;
            canvasLiveView.style.left = image.offsetLeft + "px";

            let previewImage = new Image()
            previewImage.src = image.src

            previewImage.addEventListener("load", ()=>{
                contextLiveView.drawImage(previewImage,0,0,image.width,image.height)
            //     contextLiveView.font = '50px serif'
          });
        }

        function initRect(){
          var ratio_w = canvas.width / effective_image_width;
          var ratio_h = canvas.height / effective_image_height;
          //BORDER OF SIZE 6!
          rect.height = (th_height*ratio_h-450)
          rect.width = (th_width*ratio_w-250)
          rect.top = th_top*ratio_h+10
          rect.left = th_left*ratio_w+10
          
        }

        function init(){
          //initializing all event listeners and other functions
          canvas.addEventListener('mousedown', mouseDown, false);
          canvas.addEventListener('mouseup', mouseUp, false);
          canvas.addEventListener('mouseleave', mouseUp, false);
          canvas.addEventListener('mousemove', mouseMove, false);

          canvas.addEventListener('touchstart', mouseDown);
          canvas.addEventListener('touchmove', mouseMove);
          canvas.addEventListener('touchend', mouseUp);
          initCanvas();
          initRect();
          drawRectInCanvas();
        }

        window.addEventListener('resize',repositionCanvas)
                
        //#######################################################################

        var newimgwidth = 0
        var newimgheight = 0

        var oldimgwidth = 0 
        var oldimgheight = 0

        let xname = 0
        let yname = 0
        let xdate = 0
        let ydate = 0
        let xyear = 0
        let yyear = 0
        let xevent = 0
        let yevent = 0
        let imgFile = ""
        let liveImg = ""

        // let nameUpdated = dateUpdated = yearUpdated = eventUpdated = false

        // let nameRect = dateRect = yearRect = eventRect = false

        let nameFont = ""
        let nameColor = ""
        let nameFontSize = ""

        let dateFont = ""
        let dateColor = ""
        let dateFontSize = ""

        let yearFont = ""
        let yearColor = ""
        let yearFontSize = ""

        let eventFont = ""
        let eventColor = ""
        let eventFontSize = ""

        let color = ""
        let font = ""
        let fontsize = ""

        const xnameElement =  document.getElementById("xname")
        const ynameElement = document.getElementById("yname")
        const namewidthElement = document.getElementById("namewidth")

        const xdateElement =  document.getElementById("xdate")
        const ydateElement = document.getElementById("ydate")
        const datewidthElement = document.getElementById("datewidth")

        const xyearElement =  document.getElementById("xyear")
        const yyearElement = document.getElementById("yyear")
        const yearwidthElement = document.getElementById("yearwidth")

        const xeventElement =  document.getElementById("xevent")
        const yeventElement = document.getElementById("yevent")
        const eventwidthElement = document.getElementById("eventwidth")

        const nameFontElement = document.getElementById("nameFont")
        const nameFontSizeElement = document.getElementById("nameFontSize")
        const nameColorElement = document.getElementById("nameColor")

        const dateFontElement = document.getElementById("dateFont")
        const dateFontSizeElement = document.getElementById("dateFontSize")
        const dateColorElement = document.getElementById("dateColor")

        const yearFontElement = document.getElementById("yearFont")
        const yearFontSizeElement = document.getElementById("yearFontSize")
        const yearColorElement = document.getElementById("yearColor")

        const eventFontElement = document.getElementById("eventFont")
        const eventFontSizeElement = document.getElementById("eventFontSize")
        const eventColorElement = document.getElementById("eventColor")

        let rectcenterx = 0
        let rectcentery = 0
        let rectwidth = 0

        const imageElement = document.querySelector(".upload-img")

        const liveCallback = function(event){

            let xname = xnameElement.value
            let yname = ynameElement.value
            let namewidth = namewidthElement.value
            let xdate = xdateElement.value
            let ydate = ydateElement.value
            let datewidth = datewidthElement.value
            let xyear = xyearElement.value
            let yyear = yyearElement.value
            let yearwidth = yearwidthElement.value
            let xevent = xeventElement.value
            let yevent = yeventElement.value
            let eventwidth = eventwidthElement.value

            eventname = document.querySelector("#eventname").value ? document.querySelector("#eventname").value : "Preview Event"
            date = document.querySelector("#datestring").value ? document.querySelector("#datestring").value : "12-12-12"
            color = document.querySelector("#color").value
            font = document.querySelector("#font").selectedOptions[0].value
            fontsize = document.querySelector("#fontsize").value

            if(nameClicked){
                update_name_font_data()
                
                const contextLiveView = canvasLiveView.getContext("2d")
                contextLiveView.fillStyle = `${nameColorElement.value}`
                contextLiveView.font = `${nameFontSizeElement.value}px ${nameFontElement.value.split(".")[0]}`
                contextLiveView.clearRect(0,0,canvas.width,canvas.height)//clear canvas with text
                
                let textwidth = contextLiveView.measureText(previewText).width
                contextLiveView.fillText(previewText, rectcenterx-(textwidth/2), rectcentery)
            }
            if(dateClicked){
                update_date_font_data()
                previewText = date
                const contextLiveView = canvasLiveView.getContext("2d")
                contextLiveView.fillStyle = `${dateColorElement.value}`
                contextLiveView.font = `${dateFontSizeElement.value}px ${dateFontElement.value.split(".")[0]}`
                console.log(dateFontSize)
                contextLiveView.clearRect(0,0,canvas.width,canvas.height)//clear canvas with text
                
                let textwidth = contextLiveView.measureText(previewText).width//uses this to get offset value for centering
                contextLiveView.fillText(previewText, rectcenterx-(textwidth/2), rectcentery)
            }
            if(eventClicked){
                update_event_font_data()
                previewText = eventname
                const contextLiveView = canvasLiveView.getContext("2d")
                contextLiveView.fillStyle = `${eventColorElement.value}`
                contextLiveView.font = `${eventFontSizeElement.value}px ${eventFontElement.value.split(".")[0]}`
                contextLiveView.clearRect(0,0,canvas.width,canvas.height)//clear canvas with text
                
                let textwidth = contextLiveView.measureText(previewText).width
                contextLiveView.fillText(previewText, rectcenterx-(textwidth/2), rectcentery)
            }
            if(yearClicked){
                update_year_font_data()
                const contextLiveView = canvasLiveView.getContext("2d")
                contextLiveView.fillStyle = `${yearColorElement.value}`
                contextLiveView.font = `${yearFontSizeElement.value}px ${yearFontElement.value.split(".")[0]}`
                contextLiveView.clearRect(0,0,canvas.width,canvas.height)//clear canvas with text
                
                let textwidth = contextLiveView.measureText(previewText).width
                contextLiveView.fillText(previewText, rectcenterx-(textwidth/2), rectcentery)
            }

            // const httpRequest = new XMLHttpRequest()

            // httpRequest.onreadystatechange = ()=>{
            //     if(httpRequest.readyState===4 && httpRequest.status==200){
            //         response = (JSON.parse(httpRequest.responseText))
            //         imageElement.src = `data:image/png;base64,${response[0]}`
            //     }
            // }

            // const formData = new FormData()
            // formData.append("img", imgFile)
            // formData.append("xname",xname)
            // formData.append("yname",yname)
            // formData.append("namewidth",namewidth)
            // formData.append("xdate",xdate)
            // formData.append("ydate",ydate)
            // formData.append("datewidth",datewidth)
            // formData.append("xevent",xevent)
            // formData.append("yevent",yevent)
            // formData.append("eventwidth",eventwidth)
            // formData.append("xyear",xyear)
            // formData.append("yyear",yyear)
            // formData.append("yearwidth",yearwidth)
            // formData.append("eventname",eventname)
            // formData.append("date",date)
            // formData.append("nameFont",nameFont)
            // formData.append("nameFontSize",nameFontSize)
            // formData.append("nameColor",nameColor)

            // formData.append("dateFont",dateFont)
            // formData.append("dateFontSize",dateFontSize)
            // formData.append("dateColor",dateColor)

            // formData.append("yearFont",yearFont)
            // formData.append("yearFontSize",yearFontSize)
            // formData.append("yearColor",yearColor)

            // formData.append("eventFont",eventFont)
            // formData.append("eventFontSize",eventFontSize)
            // formData.append("eventColor",eventColor)

            // formData.append("imgHeight",canvas.height)
            // formData.append("imgWidth",canvas.width)

            const newimgheightElement = document.getElementById("newimgheight")
            const newimgwidthElement = document.getElementById("newimgwidth")
            newimgheightElement.value = canvas.height 
            newimgwidthElement.value = canvas.width

            // httpRequest.open("POST","../modules/Config_Page/live_template.php",true)
            // httpRequest.send(formData)
        }

        const liveElements = document.querySelectorAll(".live")

        liveElements.forEach((element)=>{
            ["input","click"].forEach((event)=>{
                element.addEventListener(event,liveCallback)
            })
        })

        function update_name_font_data(){
            nameFont = font
            nameColor = color
            nameFontSize = fontsize

            nameFontElement.value = font 
            nameFontSizeElement.value = fontsize
            nameColorElement.value = color
        }

        function update_event_font_data(){
            eventFont = font
            eventColor = color
            eventFontSize = fontsize

            eventFontElement.value = font 
            eventFontSizeElement.value = fontsize
            eventColorElement.value = color
        }

        function update_date_font_data(){
            dateFont = font
            dateColor = color
            dateFontSize = fontsize

            dateFontElement.value = font 
            dateFontSizeElement.value = fontsize
            dateColorElement.value = color
        }

        function update_year_font_data(){
            yearFont = font
            yearColor = color
            yearFontSize = fontsize

            yearFontElement.value = font 
            yearFontSizeElement.value = fontsize
            yearColorElement.value = color
        }


        function update_name_coords(){
            nameUpdated = true
            rectcenterx = ((rect.left+rect.width/2))
            rectcentery = (((rect.top+(rect.top+rect.height))/2))
            rectwidth = rect.width

            xnameElement.value = rectcenterx 
            ynameElement.value = rectcentery
            namewidthElement.value = rectwidth

            nameRect = rect
        }

        function update_date_coords(){
            dateUpdated = true
            rectcenterx = ((rect.left+rect.width/2))
            rectcentery = (((rect.top+(rect.top+rect.height))/2))
            rectwidth = rect.width

            xdateElement.value = rectcenterx 
            ydateElement.value = rectcentery
            datewidthElement.value = rectwidth

            dateRect = rect
        }

        function update_year_coords(){
            rectcenterx = ((rect.left+rect.width/2))
            rectcentery = (((rect.top+(rect.top+rect.height))/2))
            rectwidth = rect.width

            xyearElement.value = rectcenterx 
            yyearElement.value = rectcentery
            yearwidthElement.value = rectwidth

            yearRect = rect
        }

        function update_event_coords(){
            rectcenterx = ((rect.left+rect.width/2))
            rectcentery = (((rect.top+(rect.top+rect.height))/2))
            rectwidth = rect.width

            xeventElement.value = rectcenterx 
            yeventElement.value = rectcentery
            eventwidthElement.value = rectwidth

            eventRect = rect
        }

        document.getElementById("custom-file-input").addEventListener("change",(event)=>{

            const imgElement = document.createElement('img')
            document.getElementById("preview").classList.remove("hidden")

            imageElement.src = URL.createObjectURL(event.target.files[0])
            document.getElementById("custom-file-upload").classList.add("hidden")

            const selectedImage = event.target.files[0]
            imgFile =  event.target.files[0]

            const objectUrl = URL.createObjectURL(selectedImage)

            imgElement.src = objectUrl

            imgElement.onload = ()=>{
                newimgwidth = imgElement.width 
                newimgheight = imgElement.height
            }

        })

        document.querySelector(".imgcontainer").addEventListener("click",liveCallback)

        document.getElementById("name").addEventListener("click",(event)=>{
            eventClicked = false
            dateClicked = false 
            yearClicked = false
            nameClicked = true 
            init()//initializing the canvas
            document.getElementById("name").classList.add("bg-blue-900")
            document.getElementById("name").classList.add("scale-90")
        })

        document.getElementById("date").addEventListener("click",(event)=>{
            eventClicked = false
            dateClicked = true 
            yearClicked = false
            nameClicked = false
            init()//initializing the canvas
            document.getElementById("date").classList.add("bg-blue-900")
            document.getElementById("date").classList.add("scale-90")
        })

         document.getElementById("year").addEventListener("click",(event)=>{
            eventClicked = false
            dateClicked = false 
            yearClicked = true
            nameClicked = false
            init()//initializing the canvas
            document.getElementById("year").classList.add("bg-blue-900")
            document.getElementById("year").classList.add("scale-90")
        })
        document.getElementById("event").addEventListener("click",(event)=>{
            eventClicked = true
            dateClicked = false 
            yearClicked = false
            nameClicked = false
            init()//initializing the canvas
            document.getElementById("event").classList.add("bg-blue-900")
            document.getElementById("event").classList.add("scale-90")
        })

        document.getElementById("reset").addEventListener("click",(event)=>{
            //restets everthing without reloading the page
            xnameElement.value = ""
            ynameElement.value = ""
            xdateElement.value = ""
            ydateElement.value = ""
            xyearElement.value = ""
            yyearElement.value = ""
            xeventElement.value = ""
            yeventElement.value = ""

            document.querySelector("#eventname").value = ""
            document.querySelector("#datestring").value = ""
            document.querySelector("#color").value = "#000000"
            document.querySelector("#font").selectedIndex = 0
            document.querySelector("#fontsize").value = "30"
            document.querySelector("#formid").value = ""

            document.getElementById("preview").src = ""
            document.getElementById("preview").classList.add("hidden")
            document.getElementById("custom-file-upload").classList.remove("hidden")

            initCanvas()
        })

    </script>
</body>
</html>