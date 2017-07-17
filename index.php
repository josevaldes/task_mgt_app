<?php
require "util.php";
require "html_fragments.php";
session_start();

$welcome_msg = '';
$margin_header = "0";

no_employee_login($_SESSION);
if(isset($_SESSION['uname']))
{
   $uname  = $_SESSION['uname'];
   $fname  = $_SESSION['fname'];
   $lname  = $_SESSION['lname'];
   $auth   = $_SESSION['auth'];
   $_SESSION['folder'] ="./Projects/";
}

$css_files = [0 => "index.css", 1=>"standard_form.css"];
html_header($css_files, "Bienvenido");
html_page_nav_bar($_SESSION['auth'], $fname, $lname);
html_dropdown_content($_SESSION['auth']);
echo "
<div id='main_content' class='container-fluid' style='margin-top: $margin_header;'>
  <div class='row'>
    <div class='col-md-2'>
    </div>
    <div class='col-md-8' style='width: 100%;'>
      <h1 class='header-text'> BIENVENIDO DE VUELTA</h1>
      <h1 class='header-text'> $uname </h1>
    </div>
  </div>
</div>
";
html_nav_script();
echo "</body></html>";
?>
