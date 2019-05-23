<?php

require_once('../../../lib/LiveChat_API.php');

try {
	$API = new LiveChat_API();

	// Two different behaviours here:
	// - if REQUEST_METHOD is POST, update skill data
	// - otherwise, display skill edit form

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']))
	{
		$id = (int)$_POST['id'];
		$response = $API->skills->update($id, $_POST);

		// Redirect to skills list
		header('Location: index.php');
		exit;
	}

	// Display skill edit form
	// Check if ID has been passed
	if (isset($_GET['id']) == false)
	{
		header('Location: index.php');
		exit;
	}

	$id = (int)$_GET['id'];

	// Load skill details
	$skill = $API->skills->get($id);
}
catch (Exception $e) {
	die($e->getCode().' '.$e->getMessage());
}
	
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Edit skill data	</title>
<style>
label { display: block; float: left; width: 120px; padding-right: 5px; text-align: right; }
.submit { padding-left: 125px; }
</style>
<body>

<form method="post" action="edit.php">
<h3>Update skill details</h3>
<p><label for="name">Name:</label> <input type="text" name="name" id="name" value="<?php echo $skill->name; ?>"></p>
<p class="submit"><input type="submit" value="Update skill"> <input type="hidden" name="id" value="<?php echo $skill->id; ?>"></p>
</form>

</body>
</html>