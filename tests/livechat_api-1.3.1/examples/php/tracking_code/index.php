<?php

require_once('../../../lib/LiveChat_API.php');

try {
	$API = new LiveChat_API();
	$tracking_code = $API->tracking_code->get();
}
catch (Exception $e) {
	die($e->getCode().' '.$e->getMessage());
}

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Tracking code</title>
<body>

<textarea cols="80" rows="14" wrap="off" readonly="readonly" style="overflow:hidden" onclick="this.select()">
<?php echo $tracking_code; ?>
</textarea>

</body>
</html>