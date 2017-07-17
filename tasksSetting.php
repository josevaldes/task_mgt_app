<?php
  require_once("loginDB.php");
  require "util.php";
  require "html_fragments.php";

  session_start();
  is_authorized($_SESSION);

  $connection = new mysqli($hn, $un, $pw, $db);
  if($connection->connect_error) die($connection->connect_error);
  $query = "SELECT id, description FROM tasks";
  $result = $connection->query($query);
  if(!$result) die($connection->error);

  $error_msg_1 = "";
  $error_msg_2 = "";
  $success_msg = "";
  $id = "";

  $css_files = [0 =>"index.css" , 1 => "standard_form.css"];
  html_header($css_files, "Actualizar Tarea");
  html_page_nav_bar($_SESSION['auth'], $_SESSION['fname'], $_SESSION['lname'], 1);
  html_dropdown_content($_SESSION['auth']);

  echo "
    <div class='container'>
    <div class='row text-center' >
   ";

  $rows = $result->num_rows;
  if($rows == 0)
  {
    $error_msg_1 = "No hay tareas disponibles";
  }

  if(isset($_POST['responsible']) && isset($_POST['deadline']) &&
    isset($_POST['msg']) && isset($_POST['id']) && $error_msg_1 === "")
  {
    $id = $_POST['id'];
    $msg = sanitizeMySQL($connection, $_POST['msg']);
    $days = sanitizeMySQL($connection, $_POST['deadline']);

    if($msg == "")
    {
      $error_msg_2 = "Descripcion del proyecto esta vacia";
    }

    else if(!is_numeric($days))
    {
      $error_msg_2 = "Dias debe ser un numero";
    }
    else
    {
      $days = (int)$days;
      $days = getSecondsDeadline($days);

      $responsible = $_POST['responsible'];

      $query = "UPDATE tasks SET description = '$msg', deadline='$days', responsible = '$responsible' WHERE id = '$id'";
      $result = $connection->query($query);
      if(!$result) die($connection->error);
      $success_msg = "Actualizacion de tarea exitosa";
    }
  }
  if((isset($_POST['tasks']) || $error_msg_2 !== "") && $success_msg === "" )
  {
    if($error_msg_2 === "")
    {
       $tmp = $_POST['tasks'];
       $i = 0;
       $id = '';
       while($tmp[$i] != ':')
       {
         $id = $id . $tmp[$i];
         ++$i;
       }
    }
    $query = "SELECT * FROM tasks WHERE id = '$id'";
    $result = $connection->query($query);
    if(!$result) die($connection->error);
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_NUM);

    $deadline = getDaysDeadline($row[3]);

    echo "<div class='col-md-3 ignore'></div>
        <div class='col-md-6 text-center' style='background-color:rgba(0, 0, 0, 0.4); min-height: 250px; padding-top:4%; padding-bottom: 4%; margin-top:5%;'>";

    if($error_msg_2 !== "")
    {
      echo "<div style='color:red; font-size: 15px;'> $error_msg_2 </div>";
    }
    echo "
       <form method='post' action='./tasksSetting.php' onsubmit='return verify()'>
       <h3 class='login-text'>ID de Tarea: $row[2] </h3>
       <input type='hidden' value= '$row[2]' name='id'>
    ";

    echo "
    <h3 class='login-text'>Dias antes de fecha limite: </h3>
    <input type='text' id='deadline' name='deadline' value='$deadline'>
    <h3 class='login-text'>Responsable: </h3>";
    displayAllEmployees($connection, "responsible");

    echo "<h3 class='login-text'>Descripcion de la tarea</h3>
          <textarea style='width:80%; margin-bottom:5%;' name='msg' cols='40' rows='5'>$row[1]</textarea><br>
          <input style='width: 30%;' type='submit' value='Modificar'></form>";
  }
  else
  {
    echo "<div class='col-md-12 text-center' style='background-color:rgba(0, 0, 0, 0.4); min-height: 250px; padding-top:4%; padding-bottom: 4%;'>";
   if($success_msg !== "")
   {
     echo "<h2 class='login-text'> $success_msg </h2>";
   }

   else if($error_msg_1 !== "")
   {
      echo "<h2 class='login-text'> $error_msg_1 </h2>";
   }

   else
   {
       echo "
           <form method='post' onchange='showDescription()' action='./tasksSetting.php' >
           <h3 class='login-text'>Selecciona la tarea terminada que quieres modificar:</h3>
           ";
       html_displayTasks($connection, "tasks");
       echo "<input type='submit' value='Modificar'>
             </form>";
       html_displayTaskDescription();
    }
 }

  echo "</div></div></div>";
  echo " <script src='frontend_layout/js/showDescription.js'></script>";
  echo "<script src='frontend_layout/js/isNumber.js'></script>";
  echo "<script src='frontend_layout/js/verify.js'></script>";
  html_nav_script();
  echo "</body></html>"

?>
