<?php

require_once('../../../lib/LiveChat_API.php');

try {
	$API = new LiveChat_API();

	// Example of adding an operator
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$API->operators->post($_POST);
	}

	// Example of deleting the operator
	if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']))
	{
		$id = (int)$_GET['id'];
		$API->operators->delete($id);
	}

	// Example of getting operators list
	$operators = $API->operators->get();
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

<form method="post" action="add.php">
<h3>Add an operator</h3>
<p><label for="login">E-mail:</label> <input type="text" name="login" id="login"></p>
<p><label for="password">Password:</label> <input type="password" name="password" id="password"></p>
<p><label for="name">Name:</label> <input type="text" name="name" id="name"></p>
<p><label for="display_name">Display name:</label> <input type="text" name="display_name" id="display_name"></p>
<p class="submit"><input type="submit" value="Add operator"></p>
</form>

<h3>Operators list</h3>
<table border="1">
<thead>
<tr>
<th>ID</th>
<th>E-mail</th>
<th>Name</th>
<th>Display name</th>
<th>Status</th>
<th>Permission</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ($operators as $operator) { ?>
<tr>
<td><?php echo $operator->id; ?></td>
<td><?php echo $operator->login; ?></td>
<td><?php echo $operator->name; ?></td>
<td><?php echo $operator->display_name; ?></td>
<td><?php echo $operator->status; ?></td>
<td><?php echo $operator->permission; ?></td>
<td>
	<a href="edit.php?id=<?php echo $operator->id; ?>">edit</a> 
	| 
	<a href="delete.php?id=<?php echo $operator->id; ?>" onclick="return confirm('This will permanently remove the operator <?php echo $operator->name; ?>. Proceed?');">delete</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>

</body>
</html>