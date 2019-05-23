<?php

require_once('../../../lib/LiveChat_API.php');

try {
	$API = new LiveChat_API();

	// Make sure we're doing a POST request
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$API->operators->add($_POST);
	}

	// Operator successfully added
	// Redirect to operators list
	header('Location: index.php');
	exit;
}
catch (Exception $e) {
	die($e->getCode().' '.$e->getMessage());
}