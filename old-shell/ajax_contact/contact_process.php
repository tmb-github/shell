<?php

// https://github.com/PHPMailer/PHPMailer
// For example use cases, see: https://stackoverflow.com/questions/48128618/how-to-use-phpmailer-without-composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

// Edit this for each site:
include 'includes/website_info.inc.php';

function tmb_html($argument) {
	return htmlentities($argument, ENT_QUOTES, 'UTF-8');
}

// For shell.com
if (array_key_exists('sender', $_POST)) {
	$sender_name = tmb_html($_POST['sender']); 
}

// if (array_key_exists('name', $_POST)) {
//	$sender_name = tmb_html($_POST['name']);
// }

$sender_email = tmb_html($_POST['email']);
$sender_subject = tmb_html($_POST['subject']);
$sender_message = nl2br(tmb_html($_POST['message']));

$email_subject = $email_subject_prefix . ' ' . $sender_subject;

$email_body_html = '<!DOCTYPE html>';
$email_body_html .= '<html lang=en-US>';
$email_body_html .= '<head>';
$email_body_html .= '  <meta charset=utf-8>';
$email_body_html .= '  <title>' . $website_name . ' Website Contact</title>';
$email_body_html .= '</head>';
$email_body_html .= '<body>';
$email_body_html .= '  <h3>' . $website_name . ' Website Contact</h3>';
$email_body_html .= '  <p>Name: ' . $sender_name . '</p>';
$email_body_html .= '  <p>Email: <a href="mailto:' . $sender_name . '<' . $sender_email . '>" rel="noreferrer noopener" target="_blank">' . $sender_email . '</a></p>';
$email_body_html .= '  <p>' . $sender_message . '</p>';
$email_body_html .= '</body>';
$email_body_html .= '</html>';

/////////////////////////
// CREATE MAIL OBJECT: //
/////////////////////////

$mail = new PHPMailer;
$mail->isSMTP(); 

// NB: Setting this to anything other than 0 will cause the $mail->send() method to return
// server response text that will cause the JavaScript routine on the client to fail.
// 0 = off (for production use) || 1 = client messages || 2 = client and server messages
$mail->SMTPDebug = 0;

$mail->Host = $server_host;
$mail->Port = $server_port;

// ssl is deprecated:
$mail->SMTPSecure = 'tls';

$mail->SMTPAuth = true;
$mail->Username = $smtp_username;
$mail->Password = $smtp_password;
$mail->setFrom($sender_email, $sender_name);
$mail->addAddress($recipient_email, $recipient_name);
$mail->Subject = $email_subject;

// To read an HTML message body from an external file, convert referenced
// images to embedded, use:
// $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
$mail->isHTML(true);
$mail->msgHTML($email_body_html); 

$alt_body = mb_substr($sender_message, 0, 80);
$alt_body .= '...';
$mail->AltBody =  $alt_body;

//$mail->addAttachment('images/phpmailer_mini.png'); // Attach an image file

if (!$mail->send()) {
	$sent_message = 'Mailer Error: ' . $mail->ErrorInfo;
} else {
// The JavaScript expects the text 'success' to indicate successful sending:
	$sent_message = 'success';
}

print_r($sent_message);
exit;

?>