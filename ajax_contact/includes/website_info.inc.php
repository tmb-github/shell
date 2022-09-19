<?php

$website_url = 'shell.com';
$website_name = 'Shell';
$smtp_password = 'PASSWORD';
$email_subject_prefix = '[SHELL] ';

$smtp_username = 'admin@' . $website_url;

$server_host = 'secure###.[###YOUR MAIL HOST###].com';
// if your network does not support SMTP over IPv6, unrem this line:
// $server_host = gethostbyname($server_host);

$server_port = 587; // TLS only

$recipient_name = 'Admin';
$recipient_email = $smtp_username;
