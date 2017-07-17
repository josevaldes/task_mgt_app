<?php
   require_once "loginDB.php";
   require "util.php";
/*
   $connection = new mysqli($hn, $un, $pw);

   if($connection->connect_error) die($connection->connect_error);

   // Create database
   $query = "CREATE DATABASE $db";
  if ($connection->query($query) === TRUE)
  {
    echo "Database created successfully";
    $connection->close();*/
    $connection = new mysqli($hn, $un, $pw, $db);
    if($connection->connect_error) die($connection->connect_error);

    $query = "CREATE TABLE employees (
      first_names VARCHAR(35) NOT NULL,
      last_names VARCHAR(35) NOT NULL,
      email VARCHAR(35) NOT NULL PRIMARY KEY,
      phone VARCHAR(10) NOT NULL,
      auth VARCHAR(1) NOT NULL,
      username VARCHAR(32) UNIQUE,
      password VARCHAR(32)
    )";
    if($connection->query($query) === FALSE)
    {
      die("First Error: " .$connection->error);
    }

    $query = "CREATE TABLE projects (
      name VARCHAR(20),
      curr_id_assigned INT(11)
    )";
    if($connection->query($query) === FALSE)
    {
      die("Second Error: " .$connection->error);

    }

    $query = "CREATE TABLE tasks(
      status VARCHAR(1),
      description TEXT,
      id VARCHAR(32),
      deadline INT(11),
      responsible VARCHAR(32),
      project VARCHAR(32)
    )";

    if($connection->query($query) === FALSE)
    {
      die("Third Error: " . $connection->error);
    }

    echo "Database successfully added <br>";

    $fname = "admin";
    $lname = $fname;
    $email = $fname."@me.com";
    $phone = "0000000000";
    $auth  = '1';
    $username = $fname;
    $pw_temp = $fname;
    $token = hash('ripemd128', "$salt1$pw_temp$salt2");
    $query = "INSERT INTO employees VALUES('$fname', '$lname', '$email', '$phone', '$auth','$username', '$token')";
    if($connection->query($query) === FALSE)
    {
      die("User couldn't be introduced " . $connection->error);
    }

      echo "Admin account introduced successfully <br>";

    $connection->close();

    mkdir("./Projects", 0777, true);
    mkdir("./Profiles", 0777, true);

    echo "Folders successfully made <br>";

    $fh = fopen("./Projects/index.php", 'w') or die("Failed to create file");
    $text = "<?php echo ";
    $text .= "<!DOCTYPE html PUBLIC '-//IETF//DTD HTML 2.0//EN'><html><head>
    <title>404 Not Found</title>
    </head><body>
    <h1>Not Found</h1>
    <p>The requested URL was not found on this server.</p>

    </body></html>
     ?>";
     fwrite($fh, $text) or die("Could not write to file");
     fclose($fh);

     echo "Index File successfully made <br>";


    $profile_path = "./Profiles/";
    $file_name = $fname.$lname;
    $file_name = str_replace(" ", "_", $file_name);
    $file_name.= ".jpg";
    $profile_path .= $file_name;

      if(!copy("./frontend_layout/img/paradigma_profile.jpg", $profile_path))
      {
        die("Image couldn't be copy. Contact Administrator");
      }

  echo "Image successfully copy <br>";
  unlink("setup.php");

/*
}

else
{
    echo "Error creating database: " . $connection->error;
}

$connection->close();
*/
 ?>
