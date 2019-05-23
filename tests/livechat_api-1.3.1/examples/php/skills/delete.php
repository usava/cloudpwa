<?php

require_once('../../../lib/LiveChat_API.php');

try {
	$API = new LiveChat_API();

	// Check if ID has been passed
	if (isset($_GET['id']))
	{
		$id = (int)$_GET['id'];

		$API->skills->delete($id);
	}

	// Skill successfully deleted
	// Redirect to skills list
	header('Location: index.php');
	exit;
}
catch (Exception $e) {
	die($e->getCode().' '.$e->getMessage());
}