<?php

    use PHPMailer\PHPMailer\PHPMailer;
    
    require "env_config.php";

    $MAIL_PASSWORD = $_ENV['MAIL_PASSWORD'];
    $MAIL_ADDRESS = $_ENV['MAIL_ADDRESS'];

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = "$MAIL_ADDRESS";
    $mail->Password = "$MAIL_PASSWORD";

    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';


?>