<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';


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

    // Upload attachment file
    if (!empty($_FILES["attachment"]["name"])) {

        // File path config
        $targetDir = "uploads/";
        $fileName = basename($_FILES["attachment"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        // Allow certain file formats
        $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg');
        if (in_array($fileType, $allowTypes)) {
            // Upload file to the server
            if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFilePath)) {
                $uploadedFile = $targetFilePath;
            } else {
                $uploadStatus = 0;
                $statusMsg = "Sorry, there was an error uploading your file.";
            }
        } else {
            $uploadStatus = 0;
            $statusMsg = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.';
        }
    }


    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $_POST['Voornaam'] . " " . $_POST['Achternaam'] . " heeft aangemeld als zorgproffesional";
    $mail->Body = '<b>Voornaam: </b>' . $_POST['Voornaam'] . "<br>";
    $mail->Body .= '<b>Achternaam: </b>' . $_POST['Achternaam'] . "<br>";
    $mail->Body .= '<b>Email: </b>' . $_POST['emailContact'] . "<br>";
    $mail->Body .= '<b>Nummer: </b>' . $_POST['nmbContact'];

    $mail->addAttachment($targetFilePath, $fileName);
    $mail->send();

    @unlink($targetFilePath);
    header('location: https://zefizorg.nl/aanmelden.php', true, 301); exit;
} catch (Exception $e) {
    echo "Bericht kon niet verstuurd worden!: {$mail->ErrorInfo}";
}
