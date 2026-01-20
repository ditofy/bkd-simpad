<?php
// Server in the this format: <computer>\<instance name> or 
// <server>,<port> when using a non default port number
/*$serverMSSQL = '192.168.10.7:1433';
// Connect to MSSQL
$link = mssql_connect($serverMSSQL, 'usadi', 'valid49');
$seldb = mssql_select_db('V@LID49V6_2020', $link);
$stat_sync_sipkd = false;

*/
date_default_timezone_set('Asia/Jakarta');
$server = '192.168.10.7';
$username = 'usadi';
$password = 'oemar49';
$db='V@LID49V6_2022';
//$connectionInfo = array( "Database"=>"V@LID49V6_2020", "UID"=>"usadi", "PWD"=>"valid49");
//$conn = sqlsrv_connect( $serverName, $connectionInfo);
$con = mssql_connect($server, $username, $password);
$seldb = mssql_select_db($db, $con);
?>