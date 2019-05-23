<?php

require_once('../../../lib/LiveChat_API.php');

try {
	if(isset($_GET["id"]) && $_GET["id"]!="")
	{
	 $id = $_GET["id"];
	}
	else 
	{
	 header('Location: index.php');
	 exit;
	}
	$API = new LiveChat_API();
	$chat = $API->chats->getSingleChat($id);
}
catch (Exception $e) {
	die($e->getCode().' '.$e->getMessage());
}

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Single chat</title>
<body>

<table border="1">
<thead>
<tr>
 <th colspan="11">Single chat info</th>
</tr>
<tr>
<th>Iterator</th>
<th>Id</th>
<th>Visitor name</th>
<th>Operators</th>
<th>Prechat survey</th>
<th>Postchat survey</th>
<th>Messages</th>
<th>Started</th>
<th>Ended</th>
<th>Duration</th>
<th>Custom variables</th>
</tr>
</thead>
<tbody>
<?php 
$chats = array();
$chats[] = $chat;
$iterator=0;
foreach ($chats as $transcript) { 
	?>
<tr>
<td><?php echo ++$iterator; ?></td>
<td>
 <a href="id=<?php echo $transcript->id; ?>"><?php echo $transcript->id; ?></a>
<?php echo $transcript->id; ?></td>
<td><?php echo $transcript->visitor_name; ?></td>
<td><?php 
foreach ($transcript->operators as $keyOperator => $operator)
{
 echo $keyOperator!=0 ? "<br />" : "";
 echo $operator->display_name;
}
?></td>
<td><?php 
foreach ($transcript->prechat_survey as $keyPrechat => $prechat_survey)
{
 echo $keyPrechat!=0 ? "<br />" : "";
 echo "key: ".$prechat_survey->key." value: ".$prechat_survey->value;
}
echo "&nbsp;";
?></td>
<td><?php 
foreach ($transcript->postchat_survey as $keyPostchat => $postchat_survey)
{
 echo $keyPostchat!=0 ? "<br />" : "";
 echo "key: ".$postchat_survey->key." value: ".$postchat_survey->value;
}
echo "&nbsp;";
?></td>
<td><?php 
foreach ($transcript->messages as $keyMessage => $message)
{
 echo $keyMessage!=0 ? "<br />" : "";
 echo "Author name: ".$message->author_name."<br />";
 echo "text: ".$message->text."<br />";
 echo "date: ".$message->date."<br />";
}
echo "&nbsp;";
?>
<?php echo $transcript->messages; ?></td>
<td><?php echo $transcript->started; ?></td>
<td><?php echo $transcript->ended; ?></td>
<td><?php echo $transcript->duration; ?></td>
<td><?php 
foreach ($transcript->custom_variables as $keyCustomVar => $custom_variable)
{
 echo $keyCustomVar!=0 ? "<br />" : "";
 echo "key: ".$custom_variable->key." value: ".$custom_variable->value."<br />";
}
echo "&nbsp;";
?>
</td>
</tr>
<?php } ?>
</tbody>
<tfoot>
<tr>
 <th colspan="11"><a href="index.php">Back to chats list</a></th>
</tr>
</tfoot>
</table>

</body>
</html>