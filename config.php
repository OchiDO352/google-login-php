<?php
session_start();
session_regenerate_id(true);
// change the information according to your database
$db_connection = mysqli_connect("localhost", "root", "", "google_login");
?>