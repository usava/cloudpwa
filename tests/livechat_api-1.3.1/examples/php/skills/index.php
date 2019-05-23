<?php

require_once('../../../lib/LiveChat_API.php');

try {
	$API = new LiveChat_API();
	
	// Example of adding a skill
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$API->skills->post($_POST);
	}
	
	/**
	 * Returns skills for which the operator is assigned to
	 */
	$skills = $API->skills->get();
	
}
catch (Exception $e) {
	die($e->getCode().' '.$e->getMessage());
}

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Skills test</title>
<style>
label { display: block; float: left; width: 120px; padding-right: 5px; text-align: right; }
.submit { padding-left: 125px; }
</style>
<body>

<form method="post" action="add.php">
<h3>Add a skill</h3>
<p><label for="name">Skill name:</label> <input type="text" name="name" id="name"></p>
<p class="submit"><input type="submit" value="Add skill"></p>
</form>

<?php 
    
    if(isset($skills) && !(empty($skills)))
    {
?>
<h3>Skills list</h3>
<table border="1">
<thead>
<tr>
<th>ID</th>
<th>Skill name</th>
<th>Action</th>
</tr>
</thead>
<tbody>
    <?php 
    
    foreach ($skills as $skillValue)
    {
	?>
    <tr>
	<td>
	    <?php echo $skillValue->id; ?>
	</td>
	<td>
	    <?php echo $skillValue->name; ?>
	</td>
	<td>
	    <a href="edit.php?id=<?php echo $skillValue->id; ?>">edit</a> 
		| 
	    <a href="delete.php?id=<?php echo $skillValue->id; ?>" onclick="return confirm('This will permanently remove the skill <?php echo $skillValue->name; ?>. Proceed?');">delete</a>
	</td>
    </tr>
	<?php
    }
    
    ?>
</tbody>
</table>
<?php 
    } 
    else 
    {
	?>
	There are no skills set to the operator
	<?php
    }
?>


</body>
</html>