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
if($auth == "1")
{
  $margin_header = "-20%";
}
$css_files = [0 => "index.css"];
html_header($css_files, "Bienvenido");
html_page_nav_bar($_SESSION['auth'], $fname, $lname);
html_dropdown_content($_SESSION['auth']);
echo "
<style>
   img,p
   {
     width: 50%;
   }
</style>
<div id='main_content' class='container' style='background-color:rgba(0,0,0,0.4);'>
  <div class='row'>
    <div id='Audio' class='col-md-4' onclick='fun(this, 2)'>
          <img class='icons_menu' src='./frontend_layout/img/music.png'>
          <p class='menu_title' style='color:white; text-align:center;'> Audio </p>
    </div>
  <div id='Documentos' class='col-md-4' onclick='fun(this, 2)'>
          <img class='icons_menu' src='./frontend_layout/img/documents.png'>
          <p class='menu_title' style='color:white; text-align:center;'> Documentos </p>
    </div>
      <div id='Imagenes' class='col-md-4' onclick='fun(this, 2)'>
          <img class='icons_menu' src='./frontend_layout/img/images.png'>
          <p class='menu_title' style='color:white; text-align:center;'>Imagenes</p>
    </div>
  </div>
  <div class='row'>
    <div id='Original_Editables' class='col-md-4' onclick='fun(this, 2)'>
          <img class='icons_menu' src='./frontend_layout/img/editables.png'>
          <p class='menu_title' style='color:white; text-align:center;'>Editables</p>
    </div>
    <div id='Video' class='col-md-4' onclick='fun(this, 2)'>
          <img class='icons_menu' src='./frontend_layout/img/video.png'>
          <p  class='menu_title' style='color:white; text-align:center;'>Video</p>
    </div>
  </div>
</div>
";
html_nav_script();
echo "</body></html>";
?>
