<?php // upload2.php
  require_once "loginDB.php";
  require "util.php";
  require "html_fragments.php";

  session_start();
  no_employee_login($_SESSION);

  $connection = new mysqli($hn, $un, $pw, $db);
  if ($connection->connect_error) die($connection->connect_error);

  $query = "SELECT name FROM projects";
  $result = $connection->query($query);
  if(!$result) die($connection->error);
  $rows = $result->num_rows;

  $css_files = [0 => "index.css",
                1 => "upload.css"];
  html_header($css_files, "Agregar Archivo");

  $auth = $_SESSION['auth'];
  $curr_tab = 1;
  $margin_header = "5%";
  if($auth == "1")
  {
    $curr_tab = 3;
    $margin_header = "-15%";
  }
  html_page_nav_bar($auth, $_SESSION['fname'], $_SESSION['lname'], $curr_tab);
  html_dropdown_content($auth);
  echo "
  <script>
     function set_loading(gif_code)
     {
       if(gif_code === 0)
       {
         path = 'loading.gif';
         alt = 'Loading';
       }
       else if(gif_code === 1)
       {
         path = 'success.gif';
         alt = 'Exitoso'
       }
       else
       {
         path = 'error.gif';
         alt = 'Error';
       }
       var loading = document.getElementById('loading_gif');
       loading.src = './frontend_layout/img/' + path;
       loading.alt = alt;
     }
  </script>";
    echo
    "
    <div class='container-fluid'>
      <div class='row' style=' margin-top: $margin_header';>
         <div class='col-md-4'>
         </div>
         <div id='main_content' class='col-md-4 text-center' style='color:white; font-size: 25px;'>";

      if(projects_empty($connection))
      {
        echo "<h2> No hay Proyectos activos </h2>
               </div>
             </div>
          </div>
        ";
        html_nav_script();

        echo "</body></html>";
        $connection->close();
        die();
      }
      echo "
            <form method='post' action='upload.php' enctype='multipart/form-data' onsubmit='set_loading(0)'>
            <div id='file_section'>
               SELECCIONAR ARCHIVO:
               <input type='file' name='filename' size='10' class='file_input'>
            </div>
            <div id='project_section'>
               SELECCIONAR PROYECTO:<br>
    ";
    html_displayProjects($connection, "30%");
    echo
    "
            <input type='submit' value='Agregar' style='color:black;'>
            <span><img id='loading_gif'></span>
            </div>
            </form>
            <div id='upload_message'>
    ";


  if ($_FILES && isset($_POST['projects']))
  {

    $name = $_FILES['filename']['name'];
    if($name == "")
    {
       die("Ningun archivo fue seleccionado");
    }
    $tmp_ext = explode(".", $name)[1];
    $ext = get_valid_extensions($tmp_ext);

    if ($ext)
    {
      $project_folder = $_POST['projects'];
      switch($ext)
      {
        case 'eps':
        case 'jpg':
        case 'png':
        case 'gif':
        case 'tif': $folder = 'Imagenes'; break;
        case 'mp3':
        case 'acc': $folder = "Audio"; break;
        case 'pdf':
        case 'doc':
        case 'txt':
        case 'docx': $folder = "Documentos"; break;
        case 'mov':
        case 'avi':
        case 'mp4': $folder = "Video"; break;
        case 'ai':
        case 'xml':
        case 'ae':
        case 'ttf':
        case 'otf':
        case 'fon':
        case 'prproj': $folder = "Original_Editables"; break;
      }
      $n =  explode(".", $name)[0].".".$ext;
      $path = "./Projects/$project_folder/$folder/$n";
      move_uploaded_file($_FILES['filename']['tmp_name'], $path);

      // Success Message
      echo "Transferencia exitosa:<br>";
      echo "Archivo en $folder folder dentro de $project_folder <br>
           <script> set_loading(1) </script>";


    }
    else
    {
        // Failure message
       echo " '$name' no es un archivo aceptable.
       <script> set_loading(2) </script>";
    }

  }
  else
  {
     // Default Message
     echo "Ningun archivo a sido agregado.";
  }
  echo "
         </div>
       </div>
      <div class='col-md-4'>
      </div>
    </div>
  </div>
  ";
  html_nav_script();

  echo "</body></html>";
  $connection->close();
?>
