<?php
   require_once "loginDB.php";
   require "util.php";
   require "html_fragments.php";

   session_start();
   no_employee_login($_SESSION);

   $fname = $_SESSION['fname'];
   $lname = $_SESSION['lname'];


   $css_files = [0 => "index.css", 1 => "explorer.css"];
   html_header($css_files, "Explorador de Archivos");
   html_page_nav_bar($_SESSION['auth'], $fname, $lname, 3);
   html_dropdown_content($_SESSION['auth']);
   echo
   "
      <div id ='main_content' class = 'container text-center'>
         <div class = 'row'>
            <div  class='col-md-12'>

   ";

   $root_path = "./Projects/";
   $curr_path = $_SESSION['folder'];
   $class_mode = [1=> "standard_li", 2 => "icons_menu", 3 => "standard_li"];
   $displayMode = $_SESSION["displayMode"];
   if(isset($_POST["displayMode"]))
   {
     $displayMode = (int)$_POST["displayMode"];
   }
   $_SESSION["displayMode"] = $displayMode;
   $displayFlag = ($displayMode == 2);
   if(isset($_POST['addPath']))
   {
     $addPath = $_POST['addPath'];
     if($addPath == "parent")
     {
       if($curr_path != $root_path)
       {
         $paths = explode("/", $curr_path);
         array_pop($paths);
         array_pop($paths);
         $curr_path = "";
         foreach($paths as &$path)
         {
           $curr_path .= $path."/";
         }
         unset($path);
       }
     }
     else if($addPath == "root")
     {
       $curr_path = $root_path;
     }
     else
     {
       $curr_path = $curr_path.$addPath."/";
     }
   }
   $_SESSION['folder'] = $curr_path;



   $curr_display = str_replace("./", "", $curr_path);
   $curr_display = str_replace("/", " > ", $curr_display);
   echo "<div style='text-align:left; margin-bottom: 3%;'><h4>$curr_display</h4>";
   //echo "<h4> Display Mode: $displayMode </h4>";
   echo "<span><img id='parent' onclick='fun(this, $displayMode)' class='upper_icons' src='./frontend_layout/img/back.png'></span>
          <span><img id='home' onclick='fun(this, $displayMode)' class='upper_icons' src='./frontend_layout/img/home.png'></span></div>";
   if($displayMode == 2)
   {
     echo "
              </div>
              <div class='row row_menu' >
                 <div id='Audio' class='col-md-4 menu_option' onclick='fun(this, 2)'>
                       <img class='icons_menu' src='./frontend_layout/img/music.png'>
                       <div class='menu_title'>
                       <p> Audio </p>
                       </div>
                 </div>
                 <div id='Documentos' class='col-md-4 menu_option' onclick='fun(this, 2)'>
                       <img class='icons_menu' src='./frontend_layout/img/documents.png'>
                       <div class='menu_title'>
                       <p> Documentos </p>
                       </div>
                 </div>
                <div id='Imagenes' class='col-md-4 menu_option' onclick='fun(this, 2)'>
                    <img class='icons_menu' src='./frontend_layout/img/images.png'>
                    <div class='menu_title'>
                    <p> Imagenes </p>
                    </div>
                 </div>
               </div>
          <div class='row row_menu' >
              <div id='Original_Editables' class='col-md-4 menu_option' onclick='fun(this, 2)'>
                    <img class='icons_menu' src='./frontend_layout/img/editables.png'>
                    <div class='menu_title'>
                    <p> Editables </p>
                    </div>
              </div>
              <div id='Video' class='col-md-4 menu_option' onclick='fun(this, 2)'>
                    <img class='icons_menu' src='./frontend_layout/img/video.png'>
                    <div class='menu_title'>
                    <p> Video </p>
                    </div>
              </div>
            </div>
         ";
   }
   else
   {
      $curr_files = scandir($curr_path);
      $curr_files = array_slice($curr_files, 2);
      echo "<ul class='standard_ul'>";
      foreach ($curr_files as &$curr_file)
      {
        $tmp_file = explode(".", $curr_file);
        if(sizeof($tmp_file) == 1 )
        {
           echo "<li id='$curr_file' class='standard_li' onclick = 'fun(this, $displayMode)'>$curr_file</li>";
        }
        else if(get_valid_extensions($tmp_file[1]) != '')
        {
          echo "<a href='$curr_path$curr_file'  target='_blank' > <li class='standard_li'>$curr_file </li></a>";
        }
      }
      unset($curr_file);
      echo "</ul>";
      echo "
            </div>
           </div>
         </div>";
    }
   html_nav_script();
   ?>
   <script>
      function fun(elem, displayMode)
      {
        var addPath = "";
        if(elem.id == "parent")
        {
          addPath = "parent"
          if(displayMode !== 1)
          {
            --displayMode;
          }
        }
        else if(elem.id == "home")
        {
          addPath = "root";
          displayMode = 1;
        }
        else
        {
           addPath = elem.id;
           if(displayMode !== 3)
           {
             ++displayMode;
           }
        }
        var thisForm = document.createElement("form");
        thisForm.action = "./explorer.php";
        thisForm.method = "POST";
        var thisInput = document.createElement("input");
        thisInput.value = addPath;
        thisInput.name  = "addPath";
        thisInput.type  = "hidden";
        thisForm.appendChild(thisInput);
        var displayInput = document.createElement("input");
        displayInput.value = displayMode;
        displayInput.name  = "displayMode";
        displayInput.type  = "hidden";
        thisForm.appendChild(displayInput);
        document.body.appendChild(thisForm);
        thisForm.submit();
      }
   </script>

</body>
</html>
<?php
 ?>
