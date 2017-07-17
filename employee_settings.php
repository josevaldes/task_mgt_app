<?php
   require_once "loginDB.php";
   require "util.php";
   require "html_fragments.php";

   session_start();
   no_employee_login($_SESSION);

   $connection = new mysqli($hn, $un, $pw, $db);
   if($connection->connect_error) die($connection->connect_error);

   $name_file =  underscore_name($_SESSION['fname'], $_SESSION['lname']);
   $empty_name_upload = false;
   $upload_flag = false;
   $valid_upload = false;
   $small_img_flag = false;
   $upload_msg = "";
   $invalid_pwd = "";
   $auth = $_SESSION['auth'];
   $uname = $_SESSION['uname'];
   $query = "SELECT * FROM employees WHERE username = '$uname'";
   $result = $connection->query($query);
   if(!$result) die($connection->error);
   if($result->num_rows != 1)
   {
     die("Assertion error. Contact administrator");
   }
   $result->data_seek(0);
   $employee_row = $result->fetch_array(MYSQLI_NUM);

   if(isset($_POST['currpass']) && (isset($_POST['email']) || isset($_POST['phone']) || isset($_POST['uname']) || isset($_POST['pass'])))
   {
     $curr_pass = $_POST['currpass'];
     $curr_token = hash("ripemd128", "$salt1$curr_pass$salt2");
     if($curr_token != $employee_row[6])
     {
       $invalid_pwd = "Your Password is incorrect. Verify your information";
     }
    else
    {
        if(isset($_POST['email']))
        {
          $update = sanitizeMySQL($connection, $_POST['email']);
          $query = "UPDATE employees SET email = '$update' WHERE username = '$uname'";
          $result = $connection->query($query);
          if(!$result) die($connection->error);
          $_SESSION['email']  = $update;
        }

        if(isset($_POST['phone']))
        {
          $update = sanitizeMySQL($connection, $_POST['phone']);
          $query = "UPDATE employees SET phone = '$update' WHERE username = '$uname'";
          $result = $connection->query($query);
          if(!$result) die($connection->error);
        }

        if(isset($_POST['pass']) && $_POST['pass'] != '')
        {
          $pass_tmp = $_POST['pass'];
          $update = hash("ripemd128", "$salt1$pass_tmp$salt2");
          $query = "UPDATE employees SET password = '$update' WHERE username = '$uname'";
          $result = $connection->query($query);
          if(!$result) die($connection->error);
          $_SESSION['passwd'] = $update;
        }

        if(isset($_POST['uname']))
        {
           $update = sanitizeMySQL($connection, $_POST['uname']);
           $query = "UPDATE employees SET username = '$update' WHERE username = '$uname'";
           $result = $connection->query($query);
           if(!$result) die($connection->error);
           update_username_only($connection, $update, $uname);
           $_SESSION['uname']  = $update;
        }
        $connection->close();
        header("Refresh:0");
        die();
     }
   }
   else
   {
     if ($_FILES)
     {
       $upload_flag = true;
       $name = $_FILES['filename']['name'];
       if($name == "")
       {
          $empty_name_upload = true;
       }
       else
       {
          $tmp_ext = explode(".", $name)[1];

          if ($tmp_ext && !$empty_name_upload)
          {
            switch($tmp_ext)
            {
              case 'jpg':
              case 'png': $valid_upload = true; break;
              default: $valid_upload = false; break;
            }
            if($valid_upload)
            {
                  $path = "./Profiles/$name_file.jpg";
                  move_uploaded_file($_FILES['filename']['tmp_name'], $path);
                  $connection->close();
                  header("Refresh:0");
                  die();
            }
          }
        }
      }
    }

     if($upload_flag)
     {
       if($empty_name_upload)
       {
         $upload_msg = "Ninguna imagen fue seleccionado";
       }
       else if(!$valid_upload)
       {
         $upload_msg = "Archivo no valido";
       }
       else if($small_img_flag)
       {
         $upload_msg = "Imagen muy pequeña";
       }
     }
     $margin_header = "-5%";
     if($auth == "1")
     {
       $margin_header = "-20%";
     }
     $css_files = [0 => "index.css", 1 => "employee_settings.css"];
     html_header($css_files, "Ajustes de Perfil");
     html_page_nav_bar($auth, $_SESSION['fname'], $_SESSION['lname'], 4);
     html_dropdown_content($auth);
     echo
     "
        <div class='container-fluid' id='main_content' style='color:white; clear:both; margin-top: 3%;'>
         <div class='row'>
            <div class='col-md-2'>
            </div>
            <div class='col-md-7'>
               <h2> Ajustes de Perfil </h2>
               <h4> Para realizar cambios, introduce tu contraseña actual y la informacion que desees cambiar. </h4>";
     if($invalid_pwd !== "")
     {
       echo "<h4 style='color:red'>$invalid_pwd</h4>";
     }
     echo "
            </div>
         </div>
          <div class='row'>
             <div class='col-md-2'>
             </div>
             <div class='col-md-4 sections' >
                <form action='employee_settings.php' method='post'>
                   Contraseña Actual: <input type='password' class='default_select' name='currpass'><br><br>
                   <span>Nombres: $employee_row[0]</span><br>
                   <span>Apellidos: $employee_row[1]</span><br>
                   <span>Email: <input type='text' class='default_select' name='email' value='$employee_row[2]'></span><br>
                   <span>Telefono:<input type='text' class='default_select'name='phone' value='$employee_row[3]'></span><br>
                   <span>Usuario: <input type='text' class='default_select' name='uname' value='$employee_row[5]'></span><br>
                   <span>Nueva Contraseña: <input type='password' class='default_select' name='pass'></span><br>
                   <div class='text-center' style='margin-top: 5%;'>
                   <input class='default_select' type='submit' value='Add' style='width:25%;'>
                   </div>
                </form>
             </div>
             <div class='col-md-3 text-center sections' style='margin-left: 2%;'>

                <p> Imagen de Perfil </p>
                <img style='border-radius: 50%; border: 2px solid; width: 50%;' src='./Profiles/$name_file.jpg'>

                <form method='post' action='employee_settings.php' enctype='multipart/form-data'>
                   <div style='margin-top: 10%;'> Inserta la imagen deseada:<br>
                   <input type='file' name='filename' size='10'>
                   </div>
                   <div class='text-center' style='margin-top: 10%;'>
                      <input class='default_select' type='submit' value='Upload'>
                      <span id='upload message'>$upload_msg</span>
                   </div>
                </form>
             </div>
          </div>
        </div>
     ";
    html_nav_script();
     echo "</body></html>";

 ?>
