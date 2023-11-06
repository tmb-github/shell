<?php

$website_url = $domain_name;
$website_name = $site_title;
$smtp_password = 'PASSWORD';
$email_subject_prefix = '[' . $site_title_short_form_uc . '] ';

$smtp_username = 'admin@' . $website_url;

$server_host = 'secure###.[###YOUR MAIL HOST###].com';
// if your network does not support SMTP over IPv6, unrem this line:
// $server_host = gethostbyname($server_host);

$server_port = 587; // TLS only

$recipient_name = 'Admin';
$recipient_email = $smtp_username;
