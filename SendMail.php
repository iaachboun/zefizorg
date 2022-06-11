<?php
$to = 'ili.aachboun1@gmail.com';
$subject = 'ZefiZorg ' . $_POST['bedrijfnaam'];
$message = "Bedrijfsnaam: " . $_POST['bedrijfnaam'] . "\n" .
    "email: " . $_POST['emailContact'] . "\n" .
    "Naam: " . $_POST['naamContact'] . "\n" .
    "nummer: " . $_POST['nmbContact'] . "\n" .
    "Bericht: " . $_POST['vraagContact'] . "\n";

$headers = 'From: ' . $_POST['naamContact'] . "\r\n" .
    'Reply-To: $email' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
header("Location: contact.php");
die();

?>