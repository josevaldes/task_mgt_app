<?php
  require_once("loginDB.php");
  require "util.php";
  require "html_fragments.php";

  session_start();
  is_authorized($_SESSION);
  $success_msg = "";
  $error_msg = "";

  $connection = new mysqli($hn, $un, $pw, $db);
  if($connection->connect_error) die($connection->connect_error);

  $query = "SELECT id, description FROM tasks WHERE status = '3'";
  $result = $connection->query($query);
  if(!$result) die($connection->error);

  $rows = $result->num_rows;
  if($rows == 0)
  {
    $error_msg = "No hay tareas finalizadas disponibles";
    //die("You have no finished tasks available" . add_homelink());
  }

  else if(isset($_POST['tasks']))
  {
    $tmp = $_POST['tasks'];
    $i = 0;
    $id = '';
    while($tmp[$i] != ':')
    {
      $id = $id . $tmp[$i];
      ++$i;
    }
    $query = "DELETE FROM tasks WHERE id = '$id'";
    $result = $connection->query($query);
    if(!$result) die($connection->error);
    else
    {
      $success_msg = "Tarea Eliminada";
      //die("Task Deleted" . add_homelink());
    }
  }

  $css_files = [0 =>"index.css" , 1 => "standard_form.css"];
  html_header($css_files, "Eliminar Proyecto");
  html_page_nav_bar($_SESSION['auth'], $_SESSION['fname'], $_SESSION['lname'], 1);
  html_dropdown_content($_SESSION['auth']);

  echo "
    <div class='container mybackground'>
    <div class='row text-center' >
    <div class='col-md-12 text-center' style='background-color:rgba(0, 0, 0, 0.4); min-height: 250px; padding-top:4%; padding-bottom: 4%;'>
   ";
    if($success_msg !== "")
    {
      echo "<h2 class='login-text'> $success_msg </h2>";
    }
    else if($error_msg !== "")
    {
      echo "<h2 class='login-text'> $error_msg </h2>";
    }
    else
    {
       echo "
           <form method='post' onchange='showDescription()' action='deleteFinishedTask.php' >
           <h3 class='login-text'>Selecciona la tarea terminada que quieres eliminar:</h3>
            ";
       html_displayTasks($connection, "tasks", "3");
       echo "<input type='submit' value='Delete'></form>";
       html_displayTaskDescription();
    }
  echo "</div></div></div>";
  echo " <script src='frontend_layout/js/showDescription.js'></script>";
  html_nav_script();
  echo "</body></html>";
  $connection->close();

?>
