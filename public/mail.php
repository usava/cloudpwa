<?php

$to = 'uptofly73@gmail.com';
$subject = 'Request Contact from cloudmidas.com';

$name = $_REQUEST['contact_name'] ?: '';
$email = $_REQUEST['contact_email'] ?: '';
$phone = $_REQUEST['contact_phone'] ?: '';
$text = $_REQUEST['contact_message'] ?: '';

// Message
$message = '
<html>
<head>
  <title>Request Contact from main page CloudMidas.com</title>
</head>
<body>
  <p>Here are the contact data from form!</p>
  <table>
    <tr>
      <th>Field</th><th>Value</th>
    </tr>
    <tr>
      <td>Name</td><td>'.$name.'</td>
    </tr>
    <tr>
      <td>Email</td><td>'.$email.'</td>
    </tr>
	<tr>
      <td>Phone</td><td>'.$phone.'</td>
    </tr>
	<tr>
      <td>Message</td><td>'.$text.'</td>
    </tr>
  </table>
</body>
</html>
';

// To send HTML mail, the Content-type header must be set
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=utf-8';

// Additional headers
$headers[] = 'To: Manager <'.$to.'>';
$headers[] = 'From: CloudMidas.com <info@cloudmidas.com>';
$headers[] = 'From: info@cloudmidas.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

// Mail it
mail($to, $subject, $message, implode("\r\n", $headers)); 

header('Location: https://cloudmidas.com');