<?php

require_once('../../../lib/LiveChat_API.php');

try {
	$API = new LiveChat_API();

	// Make sure we're doing a POST request
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$API->skills->add($_POST);
	}

	// Skill successfully added
	// Redirect to skills list
	header('Location: index.php');
	exit;
}
catch (Exception $e) {
	die($e->getCode().' '.$e->getMessage());
}