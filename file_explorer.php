<?php
require "util.php";

session_start();
no_employee_login($_SESSION);

echo "<body style='margin:0';>
        <iframe style='margin:0; border:none; width: 100%; height: 100%;' src='./explorer.php'>
        </iframe>
      </body>";
/*
<script>
window.onbeforeunload = function()
{
  alert('TEst');
}
</script>
";*/
 ?>
