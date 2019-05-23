<?php

$handler = fopen('application.log', 'w+');
fwrite($handler, "Start-Chat");

fwrite(print_r($_POST));
// read the webhook sent by LiveChat
$data = file_get_contents('php://input');

fwrite($handler, $data);
$data = json_decode($data);

// make sure the "chat_started" event occured
if ($data->event_type === 'chat_started')
{
    // read additional information about your visitor
    // from your internal database
	$email = $data->visitor->email;
        fwrite($handler, $email);

/*	$visitorDetails = $MyDatabase->query($email);
*/
	// send this information to LiveChat apps
	$fields = array();
	$fields[] = (object)array(
		'name' => 'Age',
		'value' => 30
	);
	$fields[] = (object)array(
		'name' => 'Position',
		'value' => 'https://lc.chat/now/10872888/'
	);

	$curlFields = http_build_query(array(
		'license_id' => $data->license_id,
		'token' => $data->token,
		'id' => 'my-integration',

		// Do not enter "http" prefix in the icon URL.
		// Your server must be able to serve the icon
		// using both https:// and http:// protocols.
		'icon' => 'cloudmidas.com/storage/branding_media/uYO080fLlb7H2Cn78m9LdNmzsAAMTSVZbrnUFWfB.png',

		'fields' => $fields
	));

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.livechatinc.com/visitors/'.$data->visitor->id.'/details');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlFields);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Version: 2'));
	$result = curl_exec($ch);
	curl_close($ch);
}