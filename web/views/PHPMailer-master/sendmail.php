<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/PHPMailer.php';
require 'src/SMTP.php';
$mail = new PHPMailer(True);

$mail->isSMTP(); // Paramétrer le Mailer pour utiliser SMTP 
$mail->Host = 'smtp.gmail.com'; // Spécifier le serveur SMTP
$mail->SMTPAuth = true; // Activer authentication SMTP
$mail->Username = 'nicolas.fleur.84@gmail.com'; // Votre adresse email d'envoi
$mail->Password = 'Kruger52?'; // Le mot de passe de cette adresse email
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Accepter SSL
$mail->Port = 465;

$mail->setFrom('nicolas.fleur.84@gmail.com', 'Maître du CO2'); // Personnaliser l'envoyeur
$mail->addAddress('nicolas.fleur.84@gmail.com', 'Nicolas FLEUR'); // Ajouter le destinataire



$mail->isHTML(true); // Paramétrer le format des emails en HTML ou non

$mail->Subject = 'Alerte ';
$mail->Body = 'CO2 trop élevée veuillez aérer la zone';

if(!$mail->send()) {
   echo 'Erreur, message non envoyé.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
   echo 'Le message a bien été envoyé !';
} 

 $mail->SMTPDebug = 1;
