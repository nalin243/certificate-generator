<?php

    use PHPMailer\PHPMailer\PHPMailer;
    
    require "env_config.php";

    $MAIL_PASSWORD = $_ENV['MAIL_PASSWORD'];

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = 'da3798@srmist.edu.in';
    $mail->Password = "$MAIL_PASSWORD";

    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';


?>