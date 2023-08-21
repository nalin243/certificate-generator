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
    <div class="flex flex-col h-screen min-w-screen overflow-auto ">
        <div class="flex flex-col h-screen w-full shrink-0  ">
            <div class="flex h-1/6 w-full ">
                <img src="./src/assets/srmlogo.png" class="p-15 mt-4 m-auto h-full w-3/12">
            </div>
            <div class="flex flex-row h-full w-full ">
                <div class="flex flex-col h-4/6 w-10/12 m-auto p-14">
                    <div class="flex h-full w-full justify-center ">
                        <input type="text" class="form-id p-4 pl-4 rounded-lg placeholder-gray-400" placeholder="Enter the form id..." name="formid"/>
                    </div>
                    <div class="flex flex-row h-full w-full  ">
                        <div class="flex h-full w-full  ">
                            <button name="submit" value="submit" class="faculty-btn hover:scale-90 px-3">Name</button>
                        </div>
                        <div class="flex h-full w-full ">
                            <button name="submit" value="submit" class="faculty-btn hover:scale-90 px-3">Date</button>
                        </div>
                    </div>
                    <div class="flex h-full w-full ">
                        <button name="submit" value="submit" class="faculty-btn hover:scale-90 px-3">Submit</button>
                    </div>
                </div>
                <div class="flex h-5/6 w-11/12 m-auto p-12 ">
                    <div class="flex flex-col cert-drop h-full w-full ">
                        <div class="flex h-full w-full justify-start "> 
                            <img src="./src/assets/drop.png" class="upload-img mt-auto mx-auto" />
                        </div>
                        <div class="flex h-full w-full justify-center ">
                            <h1 class="text-gray-400 font-bold">Upload template image.</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>