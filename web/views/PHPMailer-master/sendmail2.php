<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/PHPMailer.php';
require 'src/SMTP.php';


function envoi_mail($host = 'smtp.gmail.com', $username, $password, $port = 465, $setForm_email, $setForm_name, $addadress_email, $addadress_name, $subject, $body){
   $mail = new PHPMailer(True);

   $mail->isSMTP(); // Paramétrer le Mailer pour utiliser SMTP 
   $mail->Host = $host; // Spécifier le serveur SMTP
   $mail->SMTPAuth = true; // Activer authentication SMTP
   $mail->Username = $username; // Votre adresse email d'envoi
   $mail->Password = $password; // Le mot de passe de cette adresse email
   $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Accepter SSL
   $mail->Port = $port;

   $mail->setFrom($setForm_email, $setForm_name); // Personnaliser l'envoyeur
   $mail->addAddress($addadress_email, $addadress_name); // Ajouter le destinataire



   $mail->isHTML(true); // Paramétrer le format des emails en HTML ou non

   $mail->Subject = $subject;
   $mail->Body = $body;

   if(!$mail->send()) {
      // echo 'Erreur, message non envoyé.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
   } else {
      echo 'Le message a bien été envoyé !';
   } 

   $mail->SMTPDebug = 1;
}





