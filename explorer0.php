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
   html_page_nav_bar($_SESSION['auth'], $fname, $lname);
   html_dropdown_content($_SESSION['auth']);
   echo
   "
      <div id ='main_content' class = 'container text-center'>
         <div class = 'row'>
            <div  class='col-md-12'>

   ";

   $root_path = "./Projects";
   $curr_path = $root_path;
   $class_mode = [1=> "standard_li", 2 => "icons_menu", 3 => "standard_li"];
   $displayMode = 1;

   if(isset($_POST["displayMode"]))
   {
     $displayMode = (int)$_POST["displayMode"];
   }
   $displayFlag = ($displayMode == 2);
   /*
   $tmp = $_SESSION['folder'];
   echo "<script>alert('Before: $tmp')</script>";
*/
   if(isset($_POST['addPath']))
   {
     $curr_path = $_POST['addPath'];
   }
   $_SESSION['folder'] = $curr_path;

   $tmp = $curr_path;
   echo "<script>alert('After: $tmp')</script>";


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
          echo "<a href='$curr_path/$curr_file'  target='_blank' > <li class='standard_li'>$curr_file </li></a>";
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
        <?php echo "var addPath = '$curr_path';"; ?>
        if(elem.id == "parent")
        {
          if(displayMode !== 1)
          {
            --displayMode;
            var myarray = addPath.split("/");
            addPath = myarray[0];
            for(var i = 1; i < myarray.length - 1; ++i)
            {
              addPath += "/" + myarray[i];
            }
          }
        }
        else if(elem.id == "home")
        {
          addPath = "./Projects";
          displayMode = 1;
        }
        else
        {
           if(displayMode !== 3)
           {
             ++displayMode;
             addPath += "/" + elem.id;
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
        alert(addPath);
        alert(displayMode);
        document.body.appendChild(thisForm);
        thisForm.submit();
      }
   </script>

</body>
</html>
<?php
 ?>
