<?php
   require_once "loginDB.php";
   require "util.php";
   require "html_fragments.php";

   session_start();
   is_authorized($_SESSION);

   $success_msg = "";
   $connection = new mysqli($hn, $un, $pw, $db);
   if($connection->connect_error) die($connection->connect_error);
   if(isset($_POST['fname']) && isset($_POST['lname']) && $_POST['email']
     && isset($_POST['phone']))
  {
    $fname = sanitizeMySQL($connection, $_POST['fname']);
    $lname = sanitizeMySQL($connection, $_POST['lname']);
    $email = sanitizeMySQL($connection, $_POST['email']);
    $phone = sanitizeMySQL($connection, $_POST['phone']);

    $profile_path = "./Profiles/";
    $file_name = $fname.$lname;
    $file_name = str_replace(" ", "_", $file_name);
    $file_name.= ".jpg";
    $profile_path .= $file_name;

      if(!copy("./frontend_layout/img/paradigma_profile.jpg", $profile_path))
      {
        die("Image couldn't be copy. Contact Administrator");
      }

    $query  = "INSERT INTO employees VALUES('$fname', '$lname', '$email', '$phone', '2', NULL, NULL)";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    else
    {
      $success_msg = "$fname $lname fue agregado a la base de datos";
    }

  }
  $css_files = [0 =>"index.css" , 1 => "standard_form.css"];
  html_header($css_files, "Agregar Empleado");
  html_page_nav_bar($_SESSION['auth'], $_SESSION['fname'], $_SESSION['lname'], 2);
  html_dropdown_content($_SESSION['auth']);

  echo "
    <div class='container' >
    <div class='row text-center'  style='margin-top: 3%;'>
    <div class='col-md-3 ignore'></div>
    <div class='col-md-6 text-center' style='background-color:rgba(0, 0, 0, 0.4); min-height: 250px; padding-top:4%; padding-bottom: 4%; '>
   ";
   if($success_msg !== "")
   {
       echo "<h3 class='login-text'> $success_msg </h3>";
   }
   else
   {
       echo "<h2 class='login-text'> Forma para agregar empleado </h2>";
       echo "<form  action='./insert_new_employee.php' method='post' onsubmit='return verify()'>
          <div class= 'form_text'>
             <div class='form_row'>
                <h3 class='form_label first_label login-text'>Nombres:</h3><input class='form_label second_label' type='text' name='fname'>
             </div>
             <div class='form_row'>
                <h3 class='form_label first_label login-text'>Apellidos: </h3><input class='form_label second_label' type='text' name='lname'>
             </div>
             <div  class='form_row'>
                 <h3 class='form_label first_label login-text' >Email: </h3><input class='form_label second_label' type='text' name='email'>
             </div>
             <div class='form_row'>
                <h3 class='form_label first_label login-text'> Telefono:</h3><input class='form_label second_label' type='text' name='phone'>
             </div>
          </div>
          <input  style='text-align: center; width: 40%;' type='submit' value='Add'>
          </form>";
    }
    echo "</div></div></div>";
    echo "<script src='frontend_layout/js/verify.js'></script>";
    html_nav_script();
    echo "</body></html>";
    $connection->close();

 ?>
