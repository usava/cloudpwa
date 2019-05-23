<?php
require_once('../../../lib/LiveChat_API.php');

try
{
  $API = new LiveChat_API();
  $reports = $API->reports->get("chats",$_GET);
} 
catch (Exception $e)
{
  die($e->getCode() . ' ' . $e->getMessage());
}

$pattern = "((0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])[-](19|20)\d\d)"; 

?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Reports for chats</title>
  <body>
    <form method="get" action="chats.php">
    <table border="1">
        <tr>
          <td>
            Date from: <input type="text" name="date_from" value="<?php 
            echo isset($_GET["date_from"]) ? $_GET["date_from"] : ""; 
            ?>" />
            <br />Example: 2010-11-21
          </td>
          <td>
            Date to: <input type="text" name="date_to" value="<?php 
            echo isset($_GET["date_to"]) ? $_GET["date_to"] : ""; 
            ?>" />
            <br />Example: 2010-11-21
          </td>
          <td>
            Operator: <input type="text" name="operator" value="<?php 
            echo isset($_GET["operator"]) ? $_GET["operator"] : ""; 
            ?>" />
            <br />Example: John.Doe@example.com
          </td>
          <td>
            Skill: <input type="text" name="skill" value="<?php 
            echo isset($_GET["skill"]) ? $_GET["skill"] : ""; 
            ?>" />
            <br />Example: 1
          </td>
          <td>
            Group by: 
            <select name="group_by">
              
              <?php
              $groupByOptions = array(
                  'hour'=>0,
                  'day'=>0,
                  'month'=>0,
                  'year'=>0,
              );
              if(isset($_GET["group_by"]) && isset($groupByOptions[$_GET["group_by"]]))
                $groupByOptions[$_GET["group_by"]]=1;
              else
                $groupByOptions['day']=1;
              foreach ($groupByOptions as $keyGroupBy=>$valueGroupBy)
              {
                ?>
                <option value="<?php echo $keyGroupBy;?>" <?php echo $valueGroupBy ? "selected='selected'" : "";?>><?php echo $keyGroupBy;?></option>
                <?php
              }
              ?>
            </select>
            <br />&nbsp;
          </td>
        </tr>
        <tr>
          <td colspan="5" style="text-align: center;">
            <input type="submit" value="Search">
          </td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center;">
            <a href="index.php">Back to reports</a>
          </td>
          <td colspan="3" style="text-align: center;">
            <a href="chats.php">clean params</a>
          </td>
        </tr>
          
    </table>
    </form>
    <table border="1">
      <thead>
        <tr>
        <tr>
          <th>Date</th>
          <th>Number of chats</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($reports as $keyReport => $report)
        {
          ?>
          <tr>
            <td><?php echo $report->date; ?></td>
            <td><?php echo $report->chats; ?></td>
          </tr>
          <?php
        }
        ?>
      </tbody>
    </table>

  </body>
</html>