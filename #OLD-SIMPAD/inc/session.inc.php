<?php
include_once "db.inc.php";
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: login.php');
}
/*
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
	header('Location: login.php');
}
$_SESSION['LAST_ACTIVITY'] = time(); */
$nm = $_SESSION['nama'];
$query = "SELECT * FROM public.user WHERE username = '$nm' ";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
?>