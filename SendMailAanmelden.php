<?php
$to = 'ili.aachboun1@gmail.com';
$subject = 'ZefiZorg ' . $_POST['Voornaam'] . $_POST['Achternaam'];
$message = "Voornaam: " . $_POST['Voornaam'] . " " . "Achternaam:" . $_POST['Achternaam'] . "\n" .
    "email: " . $_POST['emailContact'] . "\n" .
    "nummer: " . $_POST['nmbContact'] . "\n" .

    $headers = 'From: ' . $_POST['naamContact'] . "\r\n" .
        'Reply-To: $email' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
header("Location: contact.php");
die();

?>