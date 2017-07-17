<?php
   require_once "loginDB.php";
   require "util.php";
   require "html_fragments.php";

   session_start();
   is_authorized($_SESSION);

   $error_msg = "";
   $success_msg = "";
   $connection = new mysqli($hn, $un, $pw, $db);
   if($connection->connect_error) die($connection->connect_error);

   if(isset($_POST['projects']) && isset($_POST['msg'])
       && isset($_POST['deadline']) && isset($_POST['responsible']))
   {
     $msg = sanitizeMySQL($connection, $_POST['msg']);
     $days = sanitizeMySQL($connection, $_POST['deadline']);

     if($msg == "")
     {
       $error_msg = "Descripcion esta vacia";
     }

     else if(!is_numeric($days))
     {
       $error_msg = "El campo de dias necesita ser un numero";
     }
     else
     {
       $days = (int)$days;

     $project = $_POST['projects'];
     $no_whitespace_name = str_replace(' ', '', $project);
     $id = substr($no_whitespace_name, 0, 3);

     $query = "UPDATE projects SET curr_id_assigned = curr_id_assigned + 1 WHERE name = '$project'";
     $result = $connection->query($query);
     if(!$result) die($connection->error);

     $query = "SELECT curr_id_assigned FROM projects WHERE name = '$project'";
     $result = $connection->query($query);
     if(!$result) die($connection->error);
     $result->data_seek(0);
     $row = $result->fetch_array(MYSQLI_NUM);
     $number = (string)$row[0];
     $id = $id . $number;

     $deadline = time() + ($days * 24 * 60 * 60);

     $responsible = $_POST['responsible'];

     $query = "INSERT INTO tasks VALUES('1', '$msg', '$id', $deadline, '$responsible', '$project')";
     $result = $connection->query($query);
     if(!$result) die($connection->error);
     else
     {
       $success_msg = "Tarea agregada exitosamente";
     }
    }
  }
  $css_files = [0 =>"index.css" , 1 => "standard_form.css"];
  html_header($css_files, "Eliminar Proyecto");
  html_page_nav_bar($_SESSION['auth'], $_SESSION['fname'], $_SESSION['lname'], 1);
  html_dropdown_content($_SESSION['auth']);

  echo "
    <div class='container mybackground' >
    <div class='row text-center'  style='margin-top: 3%;'>
    <div class='col-md-3 ignore'></div>
    <div class='col-md-6 text-center' style='background-color:rgba(0, 0, 0, 0.4); min-height: 250px; padding-top:4%; padding-bottom: 4%; '>
   ";
  if($success_msg !== "")
  {
    echo "<h2 class='login-text'> $success_msg </h2>";
  }
  else if(projects_empty($connection))
  {
    echo "<h2 class='login-text'> No hay proyectos activos </h2>";
  }
  else
  {
    if($error_msg !== "")
    {
      echo "<div style='color:red; font-size: 15px;'> $error_msg </div>";
    }
    echo "
       <form method='post' action='addTask.php' onsubmit='return verify()'>
       <h3 class='login-text'>Selecciona el proyecto al que pertenece: </h3>
       ";
      html_displayProjects($connection);

    echo "
    <h3 class='login-text'>Dias antes de fecha limite: </h3>
    <input type='text' id='deadline' name='deadline'>
    <h3 class='login-text'>Responsable: </h3>";
    displayAllEmployees($connection, "responsible");

    echo "<h3 class='login-text'>Descripcion de la tarea</h3>
          <textarea style='width:80%; margin-bottom:5%;' name='msg' cols='40' rows='5'></textarea><br>
          <input style='width: 30%;' type='submit' value='Add'></form>";
   }
   echo "</div></div></div>";
   echo "
     <script src='./frontend_layout/js/isNumber.js'></script>
     <script src='./frontend_layout/js/verify.js'></script>";
   html_nav_script();
   echo "</body></html>";
   $connection->close();

?>
