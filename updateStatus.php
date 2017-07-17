<?php
   require_once "loginDB.php";

   $flag = "0";
   if(isset($_POST['statusId']) && isset($_POST['taskId']))
   {
     $curr_task = $_POST['taskId'];
     $curr_status = $_POST['statusId'];

     $connection = new mysqli($hn, $un, $pw, $db);
     if($connection->connect_error) die($connection->connect_error);

     $query = "UPDATE tasks SET status = '$curr_status' WHERE id = '$curr_task'";
     $result = $connection->query($query);
     if(!$result) die($connection->error);
     else
      {
       $flag = "1";
     }
   }
   echo $flag;

 ?>
