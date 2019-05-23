<?php

require_once('../../../lib/LiveChat_API.php');

try {
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	$API = new LiveChat_API();
	$chats = $API->chats->get($page,$_GET);
}
catch (Exception $e) {
	die($e->getCode().' '.$e->getMessage());
}

$transcripts = $chats->chats;
$total = $chats->total;
$pages = $chats->pages;

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Chats list</title>
<body>
<form method="get" action="index.php">
<table border="1">
<thead>
  <tr>
    <th>
      Date from: <input type="text" name="date_from" value="<?php 
      echo isset($_GET["date_from"]) ? $_GET["date_from"] : ""; 
      ?>" />
      <br />Example: 2010-11-21
    </th>
    <th>
      Date to: <input type="text" name="date_to" value="<?php 
      echo isset($_GET["date_to"]) ? $_GET["date_to"] : ""; 
      ?>" />
      <br />Example: 2010-11-21
    </th>
    <th colspan="3">
      Operator: <input type="text" name="operator" value="<?php 
      echo isset($_GET["operator"]) ? $_GET["operator"] : ""; 
      ?>" />
      <br />Example: John.Doe@example.com
    </th>
    <th>
      Skill: <input type="text" name="skill" value="<?php 
      echo isset($_GET["skill"]) ? $_GET["skill"] : ""; 
      ?>" />
      <br />Example: 1
    </th>
    <th colspan="3">
      Query : <input type="text" name="query" value="<?php 
      echo isset($_GET["query"]) ? $_GET["query"] : ""; 
      ?>" />
      <br />&nbsp;
    </th>
    <th  colspan="4">
									Visitor satisfaction: 
								<select name="rate">

										<?php
										$rateOptions = array(
														-1 => array(
																		'option'		=> '-'
														),
														-2 => array(
																		'option'		=> 'Rated'
														),
														0 => array(
																		'option'		=> 'Not Rated'
														),
														2 => array(
																		'option'		=> 'Rated bad'
														),
														1 => array(
																		'option'		=> 'Rated good'
														),
										);
										if(isset($_GET["rate"]) && isset($rateOptions[$_GET["rate"]]))
												$rateOptions[$_GET["group_by"]]['selected']=1;
										else
												$rateOptions[-1]['selected']=1;
										foreach ($rateOptions as $keyGroupBy => $valueRate)
										{
												?>
												<option value="<?php echo $keyGroupBy;?>" <?php echo isset($valueRate['selected']) && $valueRate['selected'] ? "selected='selected'" : "";?>><?php echo $valueRate['option'];?></option>
												<?php
										}
										?>
								</select> 
      <br />&nbsp;
    </th>
  </tr>
  <tr>
    <th colspan="13" style="text-align: center;">
      <input type="submit" value="Search">
    </th>
  </tr>
  <tr>
    <th colspan="13" style="text-align: center;">
      <a href="index.php">clean params</a>
    </th>
  </tr>
<?php if(!empty($transcripts)) 
  {  
?>
<tr>
<th colspan="13">
  Pagination: 
<?php 
$hrefParams = $API->chats->parseParams($_GET,"page");
for ($page = 1; $page <= $pages; $page++)
{
 ?>
 <a href="<?php echo "?page=".$page.($hrefParams!="" ? "&".$hrefParams : ""); ?>"><?php echo $page; ?></a>
 <?php
}

?>
</th>  
</tr>
<?php 
  }
?>
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
<th>Goal</th>
<th>Visitor satisfaction</th>
</tr>
</thead>
<tbody>
<?php 
if(!empty($transcripts)) 
  {  
$iterator=0;
foreach ($transcripts as $transcript) 
{ 
				
//				print_r($transcript);exit;
				?>
<tr>
<td><?php echo ++$iterator; ?></td>
<td>
 <a title="Show single chat item this ID" href="single.php?id=<?php echo $transcript->id; ?>"><?php echo $transcript->id; ?></a>
</td>
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
</td>
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
<td>
				<?php 
				if($transcript->goal !== null)
				{
								echo $transcript->goal->name;
				}
				
				?>
</td>
<td>
<?php
$rate = "";
switch	($transcript->rate)
{
case	'not_rated':
				$rate = "Not Rated";
				break;
case	'rated_good':
				$rate = "Rated good";
				break;
case	'rated_bad':
				$rate = "Rated bad";
				break;
}
echo $rate;
?></td>
</tr>
<?php } ?>
<?php }
else 
{
?>
<tr>
  <td colspan="11">
    <br />
    There are no chats to show  
  </td>
</tr>
<?php
}
?>
</tbody>
</table>
</form>
</body>
</html>