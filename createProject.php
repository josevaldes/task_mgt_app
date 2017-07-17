<?php
   require_once "loginDB.php";
   require "util.php";
   require "html_fragments.php";

   session_start();
   is_authorized($_SESSION);

   $connection = new mysqli($hn, $un, $pw, $db);
   if($connection->connect_error) die($connection->connect_error);
   $success_flag = false;
   $repeated_flag = false;
   $empty_flag = false;

   if(isset($_POST['project-name']))
   {
       // REMEMBER TO SANITIZE NAME DIRS
       $projName = sanitizeMySQL($connection, $_POST['project-name']);
       $query = "SELECT * FROM projects WHERE name = '$projName'";
       $result = $connection->query($query);
       if(!$result) die($connection->error);
       if($result->num_rows > 0)
       {
         $repeated_flag = true;
       }
       else
       {
         if($projName == "")
         {
           $empty_flag = true;;
         }
         else
         {
            $path = "./Projects/".$projName;
            $copy_path = $path . "/index.php";

            if(mkdir($path, 0777, true))
            {
               if(!copy("./Projects/index.php", $copy_path))
               {
                 die("Couldn't copy files. Contact administrator");
               }
               for($j = 0; $j < sizeof($folders); ++$j)
               {
                 $subPath = $path . "/". $folders[$j];
                 $copy_path = $subPath . "/index.php";

                 if(!mkdir($subPath, 0777, true))
                 {
                   die("Couldn't create ". $folders[$j] ." folder");
                 }
                 if(!copy("./Projects/index.php", $copy_path))
                 {
                    die("Couldn't copy files. Contact administrator");
                 }
               }
               $query = "INSERT INTO projects VALUES('$projName', 0)";
               $result = $connection->query($query);
               if(!$result) die($connection->error);
               $success_flag = true;
          }
          else
          {
             die("Project Folder couldn't be created. Contact Administrador" . add_homelink());
          }
        }
     }
  }

     $css_files = [0 =>"index.css" , 1 => "login.css"];
     html_header($css_files, "Crear Proyecto");
     html_page_nav_bar($_SESSION['auth'], $_SESSION['fname'], $_SESSION['lname'], 1);
     html_dropdown_content($_SESSION['auth']);

     echo "
       <div class='container mybackground' >
       <div class='row text-center' style='margin-top: -20%;'>
       <div class='col-md-4 ignore'></div>
       <div class='col-md-4 text-center' id='main_content' style='background-color:rgba(0, 0, 0, 0.4); min-height: 250px; padding-top:4%; padding-bottom: 4%; '>
      ";
        if($success_flag)
        {
           echo "<h2 class='login-text'>Proyecto creado exitosamente</h2>";
        }
        else
        {
          echo "
          <span class='login-text' style='font-size: 20px'>Nombre del Proyecto </span><br>";
          if($repeated_flag)
          {
            echo "<div style='font-size:15px; color:red; margin-bottom:5%;'>Proyecto ya existe</div>";
          }
          else if($empty_flag)
          {
            echo "<span style='font-size:15px; color:red'>No se ingreso nombre</span><br>";
          }
          echo "
             <form action='createProject.php' method='post' onsubmit = 'return verify()''>
                <input id= 'project' type='text' name='project-name'>
                <input type='submit' value='Create Project'>
             </form>
          ";
       }

    echo "</div></div></div>";
    echo "<script src='./frontend_layout/js/verify.js'></script>";
    html_nav_script();
    echo "</body></html>";

  $connection->close();

?>
