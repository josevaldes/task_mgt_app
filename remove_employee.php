<?php
require_once("loginDB.php");
require "util.php";
require "html_fragments.php";

session_start();
is_authorized($_SESSION);

$success_flag = false;
$connection = new mysqli($hn, $un, $pw, $db);
if($connection->connect_error) die($connection->connect_error);

if(isset($_POST['remove']))
{
  $email = $_POST['remove'];

  $query = "SELECT first_names, last_names FROM employees WHERE email = '$email'";
  $result = $connection->query($query);
  if(!$result) die($connection->error);
  $result->data_seek(0);
  $row = $result->fetch_array(MYSQLI_NUM);
  $file_name = underscore_name($row[0], $row[1]);
  unlink("./Profiles/$file_name.jpg");

  $query = "DELETE FROM employees WHERE email= '$email'";
  $result = $connection->query($query);
  if(!$result) die($connection->error);
  else
  {
    $success_flag = true;
  }
}

$css_files = [0 =>"index.css" , 1 => "standard_form.css"];
html_header($css_files, "Eliminar Empleado");
html_page_nav_bar($_SESSION['auth'], $_SESSION['fname'], $_SESSION['lname'], 2);
html_dropdown_content($_SESSION['auth']);

echo "
    <div class='container mybackground' >
    <div class='row text-center' style='margin-top: 8%;'>
    <div class='col-md-4 ignore'></div>
    <div class='col-md-4 text-center' style='background-color:rgba(0, 0, 0, 0.4); min-height: 250px; padding-top:4%; padding-bottom: 4%; '>
   ";
if($success_flag)
{
  echo "<h2 class='login-text'> Empleado Eliminado </h2>";
}
else
{
  echo "
      <h3 class='login-text'> Use la opcion 'Actualizar Tarea' para seleccionar un nuevo responsable para aquellas tareas sin responsable</h3>
      <h3 class='login-text'> Seleccionar empleado a eliminar </h3>
      <form method='post' action='./remove_employee.php'>";
  displayAllEmployees($connection, "remove", "email");
  echo "<input style='margin-top: 5%; width:20%;' type='submit' value='Delete'>
      </form>";
}
echo "</div></div></div>";
html_nav_script();
echo "</body></html>";

$connection->close();
 ?>
