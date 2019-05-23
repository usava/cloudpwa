<?php

require_once('../../../lib/LiveChat_API.php');

try {
	$API = new LiveChat_API();
	$onlyChatting = isset($_GET["onlyChatting"]) && $_GET["onlyChatting"]==1 ? true : false; 
	$visitors = $API->visitors->get($onlyChatting);
}
catch (Exception $e) {
	die($e->getCode().' '.$e->getMessage());
}

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Visitors list</title>
<body>

<table border="1">
<thead>
<tr>
<th>State</th>
<th>Host</th>
<th>IP</th>
<th>Browser</th>
<th>Current page</th>
<th>Country</th>
<th>Region</th>
<th>City</th>
</tr>
</thead>
<tbody>
<?php if(!empty($visitors)) 
  {  
?>
<?php foreach ($visitors as $visitor) { ?>
<tr>
<td><?php echo $visitor->state; ?></td>
<td><?php echo $visitor->host; ?></td>
<td><?php echo $visitor->ip; ?></td>
<td><?php echo $visitor->browser; ?></td>
<td><?php echo $visitor->page_current; ?></td>
<td><?php echo $visitor->country; ?></td>
<td><?php echo $visitor->region; ?></td>
<td><?php echo $visitor->city; ?></td>
</tr>
<?php } ?>
<tr>
 <td colspan="8">
  <?php 
   if($onlyChatting)
   {
	$url = "?onlyChatting=0";
	$anchor = "Show all visitors";
   }
   else {
	$url = "?onlyChatting=1";
	$anchor = "Show chatting visitors";
   }
   
  
  ?>
  <a href="index.php<?php echo $url; ?>"><?php echo $anchor; ?></a>
 </td>
</tr>
<?php }
else 
{
?>
<tr>
  <td colspan="9">
    <br />
    There are no chats to show  
  </td>
</tr>
<?php
}
?>
</tbody>
</table>

</body>
</html>