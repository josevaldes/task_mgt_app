<?php
   function html_header($css_path_arr, $title)
   {
     $path = "./frontend_layout/css/";
     echo "
     <!DOCTYPE html>
         <html>
           <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>$title</title>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css'>
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>";
            for($i = 0; $i < count($css_path_arr); ++$i)
            {
               $css_path = $path.$css_path_arr[$i];
               echo "<link rel='stylesheet' href='$css_path'>";
            }
            echo "</head>";
   }

   function html_page_nav_bar($auth, $fname, $lname, $curr_tab = 0)
   {
     $profile_pic = underscore_name($fname, $lname);
     $path_pic = "./Profiles/$profile_pic.jpg";
     $fourth_op = "PERFIL";
     $curr_class = [
                 1 => "",
                 2 => "",
                 3 => "",
                 4 => ""];

     switch($curr_tab)
     {
         case 1: $curr_class[1] = "current"; break;
         case 2: $curr_class[2] = "current"; break;
         case 3: $curr_class[3] = "current"; break;
         case 4: $curr_class[4] = "current"; break;
         default: break;
     }

     echo "<body class='mybackground raleway'>
       <header class='container-fluid' >
         <div class='row text-center' style='background-color: black;'>
             <div class='col-md-2'>
           </div>";

      if($auth == "1")
      {
        echo " <div class='col-md-2 element menu_text dropdown_menu menu_1 $curr_class[1]' onclick='toggleOptions(1)' >
                     PROYECTOS
              </div>

              <div class='col-md-2 element menu_text dropdown_menu menu_2 $curr_class[2]' onclick='toggleOptions(2)' >
                 ADMINSTRADOR DE USUARIOS
               </div>

              <div class='col-md-2 element menu_text dropdown_menu menu_3  $curr_class[3]' onclick='toggleOptions(3)' >
                UTILIDADES
               </div>";
      }
      else
      {
        echo "<a href='./upload.php'>
                <div class='col-md-2 element menu_text dropdown_menu menu_1 $curr_class[1]' >
                     AGREGAR ARCHIVOS
              </div>
              </a>

              <a href='./tasksDisplay.php'>
                      <div class='col-md-2 element menu_text dropdown_menu menu_2 $curr_class[2]' >
                          MOSTRADOR DE TAREAS
                    </div>
                    </a>

              <a href='./explorer.php' target='_blank'>
                      <div class='col-md-2 element menu_text dropdown_menu menu_3 $curr_class[3]' >
                           EXPLORADOR DE ARCHIVOS
                      </div>
              </a>
        ";
      }

      echo "
           <div class='col-md-2 element menu_text dropdown_menu menu_4 perfil $curr_class[4]'  onclick='toggleOptions(4)'>
                <span class='dropdown_menu' style='width:50%;'>PERFIL </span> <span style='width: 50%;'><img class='dropdown_menu' src='$path_pic' style='border: 2px solid white; border-radius: 50%; height: 70px; width:80px;  background-color: transparent'></span>
           </div>
           <div class='col-md-2'>
             <a href='./index.php'><img style='width: 70%; margin-left: 25%;' src='./frontend_layout/img/logo_paradigma.png'></a>
           </div>
         </div>
       </header>
          ";
   }

   function html_dropdown_content($auth)
   {
      echo " <div class='container-fluid' >
                <div class='row text-center'>
                    <div class='col-md-2'></div>
           ";
      if($auth == "1")
      {
        echo "
            <div class='col-md-2' >
              <ul class='text-center menu' id='menu_1'>
                <a href='./createProject.php'><li class='dropdown_content'>CREAR PROYECTO</li></a>
                <a href='./deleteProject.php'><li class='dropdown_content'>ELIMINAR PROYECTO</li></a>
                <a href='./addTask.php'><li class='dropdown_content'>AGREGAR TAREA</li></a>
                <a href='./deleteFinishedTask.php'><li class='dropdown_content'>ELIMINAR TAREA TERMINADA</li></a>
                <a href='./tasksSetting.php'><li class='dropdown_content'>ACTUALIZAR TAREA</li></a>
              </ul>
            </div>

            <div class='col-md-2'>
              <ul class='text-center menu' id='menu_2'>
                <a href='./insert_new_employee.php'><li class='dropdown_content'>AGREGAR EMPLEADO</li></a>
                <a href='./remove_employee.php'><li class='dropdown_content'>ELIMINAR EMPLEADO</li></a>
                <a href='./insert_new_manager.php'><li class='dropdown_content'>AGREGAR ADMINISTRADOR</li></a>
                <a href='./reset_employee.php'><li class='dropdown_content'>REINICIAR CREDENCIALES DE EMPLEADO</li></a>
              </ul>
             </div>

            <div class='col-md-2' >
              <ul class='text-center menu' id='menu_3'>
                <a href='./upload.php'><li class='dropdown_content'>AGREGAR ARCHIVO</li></a>
                <a href='./tasksDisplay.php'><li class='dropdown_content'>MOSTRADOR DE TAREAS</li></a>
                <a href='./explorer.php' target='_blank'><li class='dropdown_content'>EXPLORADOR DE ARCHIVOS</li></a>
              </ul>
             </div>
             ";
      }
      else
      {
           echo "<div class='col-md-2'></div><div class='col-md-2'></div><div class='col-md-2'></div>";
      }
      echo
      "
      <div class='col-md-2' >
            <ul class='text-center menu' id='menu_4'>
              <a href='./employee_settings.php'><li class='dropdown_content'>AJUSTES</li></a>
              <a href='./logout.php'><li class='dropdown_content'>LOGOUT</li></a>
            </ul>
      </div>
      <div class='col-md-2'>
      </div>
    </div>
  </div>";
}


function html_nav_script()
{
  echo "
   <script src='./frontend_layout/js/jquery-3.2.0.min.js'></script>
   <script>
   function toggleOptions(column)
   {
      var curr = document.getElementsByClassName('menu_' + column)[0];
      curr.classList.toggle('selected')
      var column_id = 'menu_' + column;

      var elements = document.getElementsByClassName('menu');
      for(var i = 0; i < elements.length; ++i)
      {
        if(elements[i].id === column_id)
        {
          elements[i].classList.toggle('show');
        }
        else
        {
           if (elements[i].classList.contains('show'))
           {
              elements[i].classList.remove('show');
           }
        }
      }
      var menus = document.getElementsByClassName('dropdown_menu');
      for(var j = 0; j < menus.length; ++j)
      {
        if(!menus[j].classList.contains(column_id) && menus[j].classList.contains('selected'))
        {
          menus[j].classList.remove('selected');
        }
      }
   }

   // Close the dropdown if the user clicks outside of it

   window.onclick = function(event)
   {
       var main_content = document.getElementById('main_content');
       if (!event.target.matches('.dropdown_menu'))
       {
          var dropdowns = document.getElementsByClassName('menu');
          var i;
          for (i = 0; i < dropdowns.length; ++i)
          {
             var openDropdown = dropdowns[i];
             if (openDropdown.classList.contains('show'))
             {
                 openDropdown.classList.remove('show');
             }
         }
         var menus = document.getElementsByClassName('dropdown_menu');
         var j;
         for (j = 0; j < menus.length; ++j)
         {
            var curr_menu = menus[j];
            if (curr_menu.classList.contains('selected'))
            {
                curr_menu.classList.remove('selected');
            }
        }
        if(main_content)
        {
          main_content.style.zIndex = '0';
        }
      }
       else
       {
         if(main_content)
         {
           main_content.style.zIndex = '-1';
         }
       }
   }

</script>
      ";
   }

   function html_displayProjects($connection, $width="100%")
   {
     $query = "SELECT name FROM projects";
     $result = $connection->query($query);
     if(!$result) die($connection->error);
     $rows = $result->num_rows;
     if($rows < 1)
     {
       return false; //die("You have no open projects");
     }
     echo "<select name='projects' size='1' style='color: black; width: $width;'>";
     for($j = 0; $j < $rows; ++$j)
     {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_NUM);
        echo "<option value='$row[0]'> $row[0] </option>";
     }
     echo "</select>";
     return true;
   }


    function html_description($description, $id, $deadline, $project, $responsible = "")
    {
        $firstPart = "<div class ='task' draggable='true' id='$id' ondragstart='drag(event)'> ";
        date_default_timezone_set('America/Los_Angeles');
        $deadline_date = date('d-M-y', $deadline);
        $time_left = ceil(($deadline - time()) / 60 / 60 / 24);
        $project_msg = "<p> Proyecto: $project <br>";
        $deadline_msg  = "Fecha de Entrega: $deadline_date <br>";
        $time_left_msg = "Dias Restantes: $time_left <br>";
        $responsible_msg = "";
        if($responsible != "")
        {
          $responsible_msg = "Responsable: $responsible<br>";
        }
        $lastPart = "</p> </div>";
        return $firstPart.$project_msg.$deadline_msg.$time_left_msg.$responsible_msg.$description.$lastPart;
    }

    function html_displayTasks($connection, $name, $status = "")
    {
      $query = "";
      if($status == "")
      {
         $query = "SELECT id, description FROM tasks";
      }
      else if($status == "1" || $status == "2" || $status == "3")
      {
         $query = "SELECT id, description FROM tasks WHERE status = '$status'";
      }
      else
      {
        die("Status Error. Please contact administrator");
      }
      $result = $connection->query($query);
      if(!$result) die($connection->error);
      $rows = $result->num_rows;
      echo "<select style='width: 15%; margin-right:2%;' name= '$name' id='tasks' size='1'>";
      for($j = 0; $j < $rows; ++$j)
      {
          $result->data_seek($j);
          $row = $result->fetch_array(MYSQLI_NUM);
          echo "<option value='$row[0]: $row[1]'> $row[0] </option>";
      }
      echo "</select>";
    }

    function html_displayTaskDescription()
    {
      echo "<h3 class='login-text'> Descripcion: </h3>";
      echo "<p class='login-text' style='font-size:20px;' id = 'description'> </p>";

    }
 ?>
