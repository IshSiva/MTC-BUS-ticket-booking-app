<?php
  $server = 'localhost';
  $uname  = 'root';
  $pwd = 'admin';
  $dbname = 'mtc_bus';

  // Creating a connection
  $conn =new mysqli($server, $uname, $pwd,$dbname);

  //Checking for connection establishment
  if($conn->connect_error)
  {   
	die("Connection Error: ".$conn->connect_error);

  }
?>
