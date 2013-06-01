<?php

$name = $_POST['name'];
$from = $_POST['email'];
$zip = $_POST['zip'];
$comments = $_POST['comments'];

//$to_address = "jojo@mortaragency.com";
$to_address = "customerservice@intersectent.com";
$subject = "Physician Locator Request from ".$from;
$message = "A new physician locator request has been submitted through the website:\n\n";
$message .= "Name: " . $name . "\nEmail: " . $from . "\nZIP code: " . $zip . "\nComments: \n" . $comments;

$headers = "MIME-Version: 1.0\n";
$headers .= "Content-type: text/plain; charset=iso-8859-2\n";
$headers .= "From: $from\n" . "Reply-To: $from\n" . "X-Mailer: PHP/" . phpversion() . "\n";

mail($to_address, $subject, $message, $headers);

?>