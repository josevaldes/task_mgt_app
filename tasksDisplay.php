<?php
   require_once "loginDB.php";
   require "util.php";
   require "html_fragments.php";

   session_start();
   no_employee_login($_SESSION);
   $uname = $_SESSION['uname'];
   $auth = $_SESSION['auth'];
   $auth_flag = ($auth == "1");
   $connection = new mysqli($hn, $un, $pw, $db);
   if($connection->connect_error) die($connection->connect_error);
   $tasks =
   [
      0 => array(),
      1 => array(),
      2 => array(),
   ];
   for($j = 0; $j < 3; ++$j)
   {
      $status = $j + 1;
      if($auth_flag)
      {
        $query = "SELECT description, id, deadline, project, responsible FROM tasks WHERE status = '$status'";
      }
      else
      {
        $query = "SELECT description, id, deadline, project FROM tasks WHERE status = '$status' AND responsible = '$uname'";
      }
      $result = $connection->query($query);
      if(!$result) die($connection->error);
      $rows = $result->num_rows;
      for($k = 0; $k < $rows; ++$k)
      {
         $result->data_seek($k);
         $row = $result->fetch_array(MYSQLI_NUM);
         array_push($tasks[$j], $row);
      }
   }

 ?>
 <?php
 $css_files = [0 => "index.css", 1 => "tasksDisplay.css"];
 html_header($css_files, "Mostrador de tareas" );
 $curr_tab = 2;
 if($auth_flag)
 {
   $curr_tab = 3;
 }

 html_page_nav_bar($auth, $_SESSION['fname'], $_SESSION['lname'], $curr_tab);
 html_dropdown_content($auth);
?>

<div class='container' id='main_content'>
  <div class='row'>
    <h2 style='color:white;'>Da click y arrastra la tarea para cambiar su estatus</h2>
 <div class='col-md-4 containers'>
   <div class='row'>
     <div class='col-md-12 task-title'>
       TAREAS NUEVAS
     </div>
  </div>
  <div class='row'>
   <div id = '1' class='col-md-12 content' ondrop='drop(event, this.id)' ondragover='allowDrop(event)'>
     <?php
     if($auth_flag)
     {
       for($i = 0; $i < sizeof($tasks[0]); ++$i)
       {
         echo html_description($tasks[0][$i][0], $tasks[0][$i][1], $tasks[0][$i][2], $tasks[0][$i][3],  $tasks[0][$i][4]);
       }
     }
     else
      {
        for($i = 0; $i < sizeof($tasks[0]); ++$i)
        {
          echo html_description($tasks[0][$i][0], $tasks[0][$i][1], $tasks[0][$i][2], $tasks[0][$i][3]);
        }
     }
      ?>
   </div>
 </div>
 </div>

 <div class='col-md-4 containers'>
   <div class='row'>
     <div class='col-md-12 task-title'>
       EN PROCESO
     </div>
   </div>
   <div class='row'>
   <div id = '2' class='col-md-12 content' ondrop='drop(event, this.id)' ondragover='allowDrop(event)'>
     <?php
         if($auth_flag)
         {
           for($i = 0; $i < sizeof($tasks[1]); ++$i)
           {
             echo html_description($tasks[1][$i][0], $tasks[1][$i][1], $tasks[1][$i][2], $tasks[1][$i][3],  $tasks[1][$i][4]);
           }
         }
         else
          {
            for($i = 0; $i < sizeof($tasks[1]); ++$i)
            {
              echo html_description($tasks[1][$i][0], $tasks[1][$i][1], $tasks[1][$i][2],  $tasks[1][$i][3]);
            }
         }
      ?>
   </div>
 </div>
 </div>

 <div class='col-md-4 containers'>
   <div class='row'>
     <div class='col-md-12 task-title'>
       FINALIZADAS
     </div>
   </div>
   <div class='row'>
   <div id = '3' class='col-md-12 content' ondrop='drop(event, this.id)' ondragover='allowDrop(event)'>
     <?php
     if($auth_flag)
     {
       for($i = 0; $i < sizeof($tasks[2]); ++$i)
       {
         echo html_description($tasks[2][$i][0], $tasks[2][$i][1], $tasks[2][$i][2], $tasks[2][$i][3],  $tasks[2][$i][4]);
       }
     }
     else
      {
        for($i = 0; $i < sizeof($tasks[2]); ++$i)
        {
          echo html_description($tasks[2][$i][0], $tasks[2][$i][1], $tasks[2][$i][2],  $tasks[2][$i][3]);
        }
     }
      ?>
    </div>
  </div>
   </div>
  </div>
</div>



<?php
   html_nav_script();
   echo " <script src='./frontend_layout/js/dragging.js'> </script>
          </body></html>
         ";
   $connection->close();
 ?>
