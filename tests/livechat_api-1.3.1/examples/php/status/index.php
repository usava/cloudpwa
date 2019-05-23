<?php

require_once('../../../lib/LiveChat_API.php');

try {
	$API = new LiveChat_API();
	$status = $API->status->get();
}
catch (Exception $e) {
	die($e->getCode().' '.$e->getMessage());
}

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Status</title>
<body>

LiveChat status: <strong><?php echo $status; ?></strong>
</body>
</html>