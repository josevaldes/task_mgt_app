<?php

// Salts used for the password
 $salt1    = "#q@&h*";
 $salt2    = "!3f%";

// Array of folder name that will be inside every project
 $folders = [0 => "Audio",
             1 => "Imagenes",
             2 => "Video",
             3 => "Original_Editables",
             4 => "Documentos"];

/*
 * Function name: sanitizeString
 * Description: Strips characters used for in html docs
 * Input: String given by user
 * Output: String with html characters stripped
 */
 function sanitizeString($var)
 {
   $var = stripslashes($var);
   $var = strip_tags($var);
   $var = htmlentities($var);
   return $var;
 }

 /*
  * Function name: sanitizeMySQL
  * Description: Strips characters used for in html docs and SQL query
  * Input: MySQL database connection, String given by user
  * Output: String with SQL and html characters stripped
  */
 function sanitizeMySQL($connection, $var)
 {
   $var = $connection->real_escape_string($var);
   $var = sanitizeString($var);
   return $var;
 }

 /*
  * Function name: getDaysDeadline
  * Description: Get the number of full days remaining for a deadline
  * Input: Deadline given in seconds
  * Output: Number of full days remaining
  */
 function getDaysDeadline($deadline)
 {
   return ceil(($deadline - time()) / 60 / 60 / 24);
 }

 /*
  * Function name: getSecondsDeadline
  * Description: Get the UNIX time second for a deadline
  * Input: Days before a deadline
  * Output: UNIX time for deadline
  */
 function getSecondsDeadline($deadline)
 {
   return time() + ($deadline * 24 * 60 * 60);
 }

 /*
  * Function name: get_valid_extensions
  * Description: Check if a file extension is allowed to be uploaded
  * Input: String with the extension name
  * Output:
  *   - Empty String if extension is not valid
  *   - The input string if extension is valid
  */
 function get_valid_extensions($myext)
 {
   switch($myext)
   {
     case 'jpg': $ext = 'jpg'; break;
     case 'gif':  $ext = 'gif'; break;
     case 'png':  $ext = 'png'; break;
     case 'tif': $ext = 'tif'; break;
     case 'eps': $ext = 'eps'; break;
     case 'pdf': $ext = 'pdf'; break;
     case 'doc': $ext = 'doc'; break;
     case 'docx': $ext = 'docx'; break;
     case 'txt' : $ext = 'txt'; break;
     case 'mov': $ext = 'mov'; break;
     case 'ai': $ext = 'ai'; break;
     case 'psd': $ext = 'psd'; break;
     case 'xml': $ext = 'xml'; break;
     case 'mp3': $ext = 'mp3'; break;
     case 'avi': $ext = 'avi'; break;
     case 'mp4': $ext = 'mp4'; break;
     case 'acc': $ext = 'acc'; break;
     case 'ae': $ext = 'ae'; break;
     case 'prproj': $ext = 'prproj'; break;
     case 'ttf': $ext = 'ttf'; break;
     case 'fon': $ext ='fon'; break;
     case 'otf': $ext = 'otf'; break;
     default:           $ext = '';    break;
   }
   return $ext;
 }

 /*
  * Function name: no_employee_login
  * Description: Checks if user has the session credentials
  * Input: $_SESSION array
  * Output: None
  *   - It does nothing if the user has a valid session array
  *   - It returns the user to the login screen if user has no valid session array
  */
 function no_employee_login($session)
 {
   if(!isset($session['uname']))
   {
     header("Location: ./login.php");
     die();
   }
 }

 /*
  * Function name: is_authorized
  * Description: Checks if user is an administrator
  * Input: $_SESSION array
  * Output: None
  *   - It does nothing if user is a valid administrator
  *   - It outputs an error message if user has a valid session array
  */
 function is_authorized($session)
 {
   no_employee_login($session);
   if($session['auth'] != "1")
   {
     die("You are not authorized to see this page");
   }
 }

 /* Temporal function: Adds a link to home page */
 function add_homelink()
 {
   return "<h2><a href='./index.php'>Home</a></h2>";
 }

 /*
  * Function name: update_username
  * Description: Restore the responsible and authorization keys of a given employee
  * Input: MySQL database connection, String email of employee and String username of employee
  * Output: None
  */
 function update_username($connection, $email, $uname)
 {
    $query = "UPDATE tasks SET responsible = '$uname' WHERE responsible = '$email'";
    $result = $connection->query($query);
    if (!$result) die($connection->error);

    $query = "UPDATE employees SET auth = '2' WHERE email = '$email'";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
 }

 /*
  * Function name: update_username_only
  * Description: Change the username of an employee for  a new one
  * Input: mySQL database connection, String new username, String old username
  * Output: None
  */
 function update_username_only($connection, $new_uname, $uname)
 {
    $query = "UPDATE tasks SET responsible = '$new_uname' WHERE responsible = '$uname'";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
 }

 function displayAllEmployees($connection, $name, $reference = "username")
 {
   echo "<select name='$name' size='1'>";
   $query = "SELECT $reference, first_names, last_names FROM employees";
   $result = $connection->query($query);
   if(!$result) die($connection->error);
   $rows = $result->num_rows;
   for($i = 0; $i < $rows; ++$i)
   {
     $result->data_seek[$i];
     $row = $result->fetch_array(MYSQLI_NUM);
     if($row[0] != NULL)
     {
        echo "<option value='$row[0]'> $row[1] $row[2] </option>";
     }
   }
   echo "</select>";
 }

 function displayTasks($connection, $name, $status = "")
 {
   $query = "";
   if($status == "")
   {
      $query = "SELECT id, description FROM tasks";
   }
   else if($status == "1" || $status == "2" || $status == "3")
   {
      $query = "SELECT id, description FROM tasks WHERE status = '$status'";
   }
   else
   {
     die("Status Error. Please contact administrator");
   }
   $result = $connection->query($query);
   if(!$result) die($connection->error);
   $rows = $result->num_rows;
   echo "<select name= '$name' id='tasks' size='1'>";
   for($j = 0; $j < $rows; ++$j)
   {
       $result->data_seek($j);
       $row = $result->fetch_array(MYSQLI_NUM);
       echo "<option value='$row[0]: $row[1]'> $row[0] </option>";
   }
   echo "</select>";
 }

 function displayProjects($connection)
 {
   $query = "SELECT name FROM projects";
   $result = $connection->query($query);
   if(!$result) die($connection->error);
   $rows = $result->num_rows;
   if($rows < 1)
   {
     die("You have no open projects");
   }
   echo "<select name='projects' size='1' style='color: black'>";
   for($j = 0; $j < $rows; ++$j)
   {
      $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_NUM);
      echo "<option value='$row[0]'> $row[0] </option>";
   }
   echo "</select>";
 }

 function displayTaskDescription()
 {
   echo "<p> Description: </p>";
   echo "<p id = 'description'> </p>";
   echo " <script src='frontend_layout/js/showDescription.js'></script>";
 }

 /*
  * Function name: underscore_name
  * Description: Concatanes two strings and replace empty spaces for underscores
  * Input: String first names, String last names
  * Output: Strign concatenated with underscores
  */
 function underscore_name($fname, $lname)
 {
   $file_name = $fname.$lname;
   $file_name = str_replace(" ", "_", $file_name);
   return $file_name;
 }

 function projects_empty($connection)
 {
   $query = "SELECT name FROM projects";
   $result = $connection->query($query);
   if(!$result) die($connection->error);
   $rows = $result->num_rows;
   return ($rows < 1);
 }
 ?>
