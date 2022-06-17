<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/PHPMailer/phpmailer/src/Exception.php';
require 'vendor/PHPMailer/phpmailer/src/PHPMailer.php';
require 'vendor/PHPMailer/phpmailer/src/SMTP.php';


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'mail.mijndomein.nl';                     //Set the SMTP server to send through
    $mail->SMTPAuth = true;                                   //Enable SMTP authentication
    $mail->Username = 'info@zefizorg.nl';                     //SMTP username
    $mail->Password = 'zefiPassword99!';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('info@zefizorg.nl', 'ZefiZorg.nl');
    $mail->addAddress('info@zefizorg.nl', 'zefizorg.nl');     //Add a recipient
    $mail->addReplyTo('info@example.com', 'Information');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $_POST['naamContact'] . " heeft aangemeld voor contact.";
    $mail->Body = '<b>Bedrijfsnaam: </b>' . $_POST['bedrijfnaam'] . "<br>";
    $mail->Body .= '<b>Volledige naam: </b>' . $_POST['naamContact'] . "<br>";
    $mail->Body .= '<b>Email: </b>' . $_POST['emailContact'] . "<br>";
    $mail->Body .= '<b>Nummer: </b>' . $_POST['nmbContact'] . "<br>";
    $mail->Body .= '<b>Bericht: </b>' . $_POST['vraagContact'];

    $mail->send();
    header('location: /contact.php');
    echo 'Bericht is verstuurd!';
} catch (Exception $e) {
    echo "Bericht kon niet verstuurd worden!: {$mail->ErrorInfo}";
}