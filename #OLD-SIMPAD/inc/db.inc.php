<?php
//if (!isset($_SESSION)) { session_start(); }
//include_once $_SESSION['base_dir']."inc/auth.inc.php";
date_default_timezone_set('Asia/Jakarta');
$dbconn = pg_connect("host=173.16.6.2 dbname=simpad user=simpad password=simpad1!2@") or die('Could not connect: ' . pg_last_error());
//$dbconn = pg_connect("host=localhost dbname=simpad user=simpad password=simpad1!2@") or die('Could not connect: ' . pg_last_error());
$nm_kadis = "Drs. SYAFWAL, MM";
$nip_kadis = "19690116 199009 1 001";
$nm_kabid = "Drs. E Z A";
$nip_kabid = "19760108 199511 1 002";
$bulan = array(1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember", 0 => "Desember");
$bulan_s = array('01' => "Januari", '02' => "Februari", '03' => "Maret", '04' => "April", '05' => "Mei", '06' => "Juni", '07' => "Juli", '08' => "Agustus", '09' => "September", '10' => "Oktober", '11' => "November", '12' => "Desember");
$nm_pajak = array('01' => "AIR BAWAH TANAH", '02' => "REKLAME", '03' => "RESTORAN", '06' => "HIBURAN");
?>