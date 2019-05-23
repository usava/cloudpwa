<?php

require_once('../../../lib/LiveChat_API.php');

try {
	$API = new LiveChat_API();

	// Two different behaviours here:
	// - if REQUEST_METHOD is POST, update operator data
	// - otherwise, display operator edit form

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']))
	{
		$id = (int)$_POST['id'];
		$response = $API->operators->update($id, $_POST);
		//Redirect to operators list
		header('Location: index.php');
	}

	// Display operator edit form
	// Check if ID has been passed
	if (isset($_GET['id']) == false)
	{
		header('Location: index.php');
		exit;
	}

	$id = (int)$_GET['id'];

	// Load operator details
	$operator = $API->operators->get($id);
}
catch (Exception $e) {
	die($e->getCode().' '.$e->getMessage());
}
	
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Operators list</title>
<style>
label { display: block; float: left; width: 120px; padding-right: 5px; text-align: right; }
.submit { padding-left: 125px; }
</style>
<body>

<form method="post" action="edit.php<?php echo isset($_GET['id']) ? "?id=".$_GET['id'] : (isset($_POST['id']) ? "?id=".$_POST['id'] : "")?>">
<h3>Update operator details</h3>
<p><label for="login">Login:</label> <?php echo $operator->login; ?></p>
<p><label for="password">Password:</label> <input type="password" name="password" id="password"> (leave empty to leave the old password)</p>
<p><label for="name">Name:</label> <input type="text" name="name" id="name" value="<?php echo $operator->name; ?>"></p>
<p><label for="display_name">Display name:</label> <input type="text" name="display_name" id="display_name" value="<?php echo $operator->display_name; ?>"></p>
<p><label for="status">Status:</label> 
	<select name="status">
              
	  <?php
	  $statusOptions = array(
		  'offline'	=>0,
		  'away'	=>0,
		  'online'	=>0
	  );
	  if(isset($_GET["status"]) && isset($groupByOptions[$_GET["status"]]) && in_array($status,$statusOptions))
		$statusOptions[$_GET["status"]]=1;
	  else if(isset($statusOptions[$operator->status]))
		  $statusOptions[$operator->status]=1;
	  foreach ($statusOptions as $keyStatus=>$valueStatus)
	  {
		?>
		<option value="<?php echo $keyStatus;?>" <?php echo $valueStatus ? "selected='selected'" : "";?>><?php echo $keyStatus;?></option>
		<?php
	  }
	  ?>
	</select>
</p>
<p class="submit"><input type="submit" value="Update operator"> <input type="hidden" name="id" value="<?php echo $operator->id; ?>"></p>
</form>

</body>
</html>