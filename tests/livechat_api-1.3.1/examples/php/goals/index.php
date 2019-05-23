<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
	require_once('../../../lib/LiveChat_API.php');
	
	$mark = false;
	
	try {
		$API = new LiveChat_API();

		// Make sure we're doing a POST request

		$API->goals->mark($_POST);
		$mark = true;
	}
	catch (Exception $e) {
		die($e->getCode().' '.$e->getMessage());
	}
}
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Goals</title>
<style>
label { display: block; float: left; width: 120px; padding-right: 5px; text-align: right; }
.submit { padding-left: 125px; }
</style>
<body>
<?php 

if(isset($mark) && $mark )
{
?>
	<h2>The event has been marked as successful.</h2>
<?php 	
}
?>
<form method="post" action="index.php">
<h3>Mark goal as successful</h3>
<p><label for="goal_id">Goal id</label> <input type="text" name="goal_id" id="login"></p>
<p><label for="visitor_id">Visitor id:</label> <input type="text" name="visitor_id" id="password"></p>
<p><label for="order_id">Order id:</label> <input type="text" name="order_id" id="password"></p>
<p><label for="order_price">Order price:</label> <input type="text" name="order_price" id="password"></p>
<p><label for="order_currency">Order currency:</label> <input type="text" name="order_currency" id="password"></p>
<p><label for="order_description">Order description:</label> <input type="text" name="order_description" id="name"></p>
<p class="submit"><input type="submit" value="Mark as successful"></p>
</form>

</body>
</html>