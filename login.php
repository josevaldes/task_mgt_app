<?php // authenticate.php
  require_once 'loginDB.php';
  require 'util.php';
  require 'html_fragments.php';

  $connection =
    new mysqli($hn, $un, $pw, $db);

  if ($connection->connect_error) die($connection->connect_error);
  $error_msg = "";
  session_start();

  if (isset($_POST['uname']) &&
      isset($_POST['passwd']) &&
      !isset($_SESSION['uname']))
  {
    $un_temp = sanitizeMySQL($connection, $_POST['uname']);
    $pw_temp = $_POST['passwd'];

    $query  = "SELECT * FROM employees WHERE username='$un_temp'";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    else if ($result->num_rows == 1)
    {
        $row = $result->fetch_array(MYSQLI_NUM);

          $result->close();

        $token = hash('ripemd128', "$salt1$pw_temp$salt2");
        if ($row[6] != NULL && $token == $row[6] )
        {
           $_SESSION['uname']  = $un_temp;
           $_SESSION['passwd'] = $pw_temp;
           $_SESSION['email']  = $row[2];
           $_SESSION['fname']  = $row[0];
           $_SESSION['lname']  = $row[1];
           $_SESSION['auth']   = $row[4];
           $_SESSION['folder'] = "./Projects/";
           $_SESSION['displayMode'] = 1;
        }
        else $error_msg = "Usuario o Contraseña Invalido";//die("Invalid username/password combination");
    }
    else $error_msg = "Usuario o Contraseña Invalido";//die("Invalid username/password combination");
  }
  if(isset($_SESSION['uname']))
  {
    header("Location: index.php"); //Redirect browser
    die();
  }
  else
  {
    $css_files = [0 => "login.css"];
    html_header($css_files, "Employee Login");

    echo "
    <body class= 'mybackground raleway'>
      <div class='container mybackground' id='login'>
      <div class='row'>
      <div class='col-md-4 ignore'></div>
      <div class='col-md-4 text-center' style='background-color:rgba(0, 0, 0, 0.4);' >
        <img style='margin-bottom: 5%;' src='./frontend_layout/img/logo_paradigma.png' alt='Paradigma' id='logo'>";
    if($error_msg !== "")
    {
        echo "<h4 style='color:red'> $error_msg </h4>";
    }
    echo "
        <form method='post' action='login.php'>
        <div id='credential_field'>
          <span class='login-text'>USUARIO</span><br>
          <input type='text' name='uname'><br>
          <span class='login-text'>PASSWORD</span><br>
          <input type='password' name='passwd'><br>
          <input  type='submit' value='LOGIN'>
        </div>
        </form>
        <div id='recover_field'>
        <p class='login-text'> PRIMER INGRESO/CAMBIO DE CONTRASEÑA <br>
            INGRESAR CORREO
        </p>
        <form method='post' action='set_employee_credentials.php' >
        <input type='text' name='email'><br>
        <input  type= 'submit' value='CHECK'>
        </form>
      </div>
      </div>
      <div class='col-md-4 ignore'></div>
    </div>
  </div>
  </body>
  </html>
  ";

  }
  ?>
