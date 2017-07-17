<?php
   require_once "loginDB.php";
   require "util.php";
   require "html_fragments.php";

   $connection = new mysqli($hn, $un, $pw, $db);
   if($connection->connect_error) die($connection->connect_error);

   $css_files = [0 => "login.css"];
   html_header($css_files, "Cambio de Credenciales");

   echo "
   <body class= 'mybackground raleway'>
     <div class='container mybackground' id='login'>
     <div class='row'>
     <div class='col-md-4 ignore'></div>
     <div class='col-md-4 text-center' style='background-color:rgba(0, 0, 0, 0.4); min-height: 250px; padding-top:1%; padding-bottom: 4%' >
     <div style='text-align: left;'>
       <a href='./login.php'>
          <img style='width: 15%; display:inline-block; padding-bottom:3%;' src='./frontend_layout/img/back.png' alt='Regresar'>
          <h3 class='login-text' style='text-align:left; display:inline-block; margin-left: 0.5%; text-decoration-style:none;'>Regresar a iniciar sesion</h3>
       </a>
       </div>
        ";
  $end_html = "</div></div></div></body></html>";

  if(isset($_POST['uname']) && isset($_POST['pwd']) && isset($_POST['email']))
  {
    $uname = sanitizeMySQL($connection, $_POST['uname']);
    $email =   sanitizeMySQL($connection, $_POST['email']);
    $pwd = $_POST['pwd'];

    $query  = "UPDATE employees SET username = '$uname' WHERE email = '$email'";
    $result = $connection->query($query);
    if (!$result) die($connection->error);

    $token = hash('ripemd128', "$salt1$pwd$salt2");

    $query  = "UPDATE employees SET password = '$token' WHERE email = '$email'";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    if(isset($_POST['reset']))
    {
       update_username($connection, $email, $uname);
    }
    die("<h2 class='login-text'>Actualizacion de credenciales exitosa</h2>".$end_html);

  }
  else if(isset($_POST['currpass']) && isset($_POST['pass']) && isset($_POST['email']))
  {
    $currpass = $_POST['currpass'];
    $currtoken =  hash('ripemd128', "$salt1$currpass$salt2");
    $pw_temp = $_POST['pass'];
    $email = $_POST['email'];

    $query = "SELECT password FROM employees WHERE email = '$email'";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_NUM);

    if($currtoken != $row[0])
    {
      echo "<h2 class='login-text' style='margin-bottom: 10%;'>Contraseña Incorrecta, por favor intente de nuevo</h2>";
      echo "<form action='./set_employee_credentials.php' method='post'>
            <span class='login-text' style='font-size: 25px;'> Contraña Actual: <br>
            <input type='password' name='currpass'> <br>
           <span class='login-text' style='font-size: 25px;'> Nueva Contraseña: </span><br>
           <input type='password' name='pass'>
            <input type='hidden' value='$email' name='email'>
            <input style='color:black;' type='submit' value='SUBMIT'>
            </form>";
       die($end_html);
    }

    $token =  hash('ripemd128', "$salt1$pw_temp$salt2");
    $query = "UPDATE employees SET password = '$token' WHERE email = '$email'";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    else
    {
      die("<h2 class='login-text'>Actualizacion de credenciales exitosa</h2>".$end_html);
    }

  }

  else if(isset($_POST['email']))
  {
    $email = sanitizeMySQL($connection, $_POST['email']);
    $query  = "SELECT username, auth FROM employees WHERE email = '$email'";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    else if($result->num_rows != 1) die("<h2 class='login-text'>Email no registrado</h2>".$end_html);

    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_NUM);
    if($row[0] != NULL)
    {
       echo "<h2 class='login-text' style='margin-bottom: 10%;'> Reinicia tu Contraseña</h2>";
       echo "<form action='./set_employee_credentials.php' method='post'>
             <span class='login-text' style='font-size: 25px;'> Contraña Actual: <br>
             <input type='password' name='currpass'> <br>
            <span class='login-text' style='font-size: 25px;'> Nueva Contraseña: </span><br>
            <input type='password' name='pass'>
             <input type='hidden' value='$email' name='email'>
             <input style='color:black;' type='submit' value='SUBMIT'>
             </form>";
    }
    else
    {
      echo "<h2 class='login-text' style='margin-bottom: 10%;'> Agregue sus nuevas credenciales </h2>";
      echo "<form action='./set_employee_credentials.php' method='post'>
            <span class='login-text' style='font-size: 25px;'>Nombre de Usuario: </span><br>
            <input type='text' name='uname'><br>
            <span class='login-text' style='font-size: 25px;'>Nueva Contraseña: </span><br>
            <input type='password' name='pwd'>
            <input type='hidden' value='$email' name='email'>";
      if($row[1] == '3')
      {
        echo "<input type='hidden' value='reset' name='reset'>";
      }
      echo "<input style='color:black;' type='submit' value='SUBMIT'>
            </form>";
    }
    echo $end_html;
  }
  else
  {
    header("Location: ./login.php");
    die();
  }
 ?>
