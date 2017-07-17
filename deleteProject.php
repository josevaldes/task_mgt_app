<?php // upload2.php
  require_once "loginDB.php";
  require "util.php";
  require "html_fragments.php";

  session_start();
  is_authorized($_SESSION);

  $connection = new mysqli($hn, $un, $pw, $db);
  if ($connection->connect_error) die($connection->connect_error);

  $query = "SELECT name FROM projects";
  $result = $connection->query($query);
  if(!$result) die($connection->error);

  $empty_flag = false;
  $success_flag = false;
  $rows = $result->num_rows;

  if($rows < 1)
  {
    $empty_flag = true;
  }

  else if (isset($_POST['projects']))
  {
    $project_folder = $_POST['projects'];

    $path = "./Projects/$project_folder";
    $myfolders = scandir($path);
    $myfolders = array_slice($myfolders, 2);
    for($j = 0; $j < sizeof($myfolders); ++$j)
    {
      $myfile = $myfolders[$j];
      if(strpos($myfile, '.') !== false)
      {
        unlink($path."/".$myfile);
      }
      else
      {
         $curr_folder = "$path/$myfolders[$j]/";
         $curr_files = scandir($curr_folder);
         $curr_files = array_slice($curr_files, 2);
         foreach ($curr_files as &$curr_file)
         {
           $curr_file = $curr_folder . $curr_file;
         }
         unset($curr_file);
         array_map('unlink',$curr_files);
         if(!rmdir($curr_folder)) die("Folder couldn't be deleted. Contact Administrador" . add_homelink());
      }

    }

    if(!rmdir($path)) die("Main Project couldn't be deleted");
    $query = "DELETE FROM projects WHERE name = '$project_folder'";
    $result = $connection->query($query);
    if(!$result) die($connection->error);
    $success_flag = true;
  }

  $css_files = [0 =>"index.css" , 1 => "standard_form.css"];
  html_header($css_files, "Eliminar Proyecto");
  html_page_nav_bar($_SESSION['auth'], $_SESSION['fname'], $_SESSION['lname'], 1);
  html_dropdown_content($_SESSION['auth']);

  echo "
    <div class='container mybackground' >
    <div class='row text-center' style='margin-top: 8%;'>
    <div class='col-md-4 ignore'></div>
    <div class='col-md-4 text-center' style='background-color:rgba(0, 0, 0, 0.4); min-height: 250px; padding-top:4%; padding-bottom: 4%; '>
   ";
   if($success_flag)
   {
     echo "<h2 class='login-text'> Proyecto Eliminado </h2>";
   }
   else if($empty_flag)
   {
     echo "<h2 class='login-text'> No hay proyectos activos </h2>";
   }
   else
   {
    echo "
      <h1 class='login-text'> Advertencia! Todos los archivos de dicho proyecto seran eliminados </h1>
      <h3 class='login-text'> Seleccionar el proyecto a eliminar </h3>
      <form method='post' action='./deleteProject.php'>";
       html_displayProjects($connection);
      echo "<input style='margin-top: 5%; width:50%;' type='submit' value='Delete'></form>";
   }

  echo "</div></div></div>";
  html_nav_script();
  echo "</body></html>";
  $connection->close();
?>
