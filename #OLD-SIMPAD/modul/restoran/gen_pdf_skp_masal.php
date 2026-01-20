<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
set_time_limit(0);
//header('Content-Type: text/event-stream;');
//header('Content-Encoding: none; ');
header('Cache-Control: no-cache');
function send_message($id, $message) {
    $d = array('message' => $message);      
    echo "id: $id" . PHP_EOL;
    echo "data: " . json_encode($d) . PHP_EOL;
    echo PHP_EOL;	  
    //ob_flush();
	//ob_start();
	//ob_flush();
    //flush();
	ob_flush();
	ob_clean();
	//ob_clean();
}

$descr = array(
    0 => array(
        'pipe',
        'r'
    ) ,
    1 => array(
        'pipe',
        'w'
    ) ,
    2 => array(
        'pipe',
        'w'
    )
);
$thn_pajak = $_GET['thn_pajak'];
$bln_pajak = $_GET['bln_pajak'];
$a = uniqid();
$nm_file = $a.".pdf";
$pipes = array();
$pdf_gen = $_SESSION['PDF_GEN'];
$cwd = $_SESSION['TMP_FOLDER'];
$base_dir = $_SESSION['base_dir'];
$base_url = $_SESSION['base_url'];
if (file_exists($cwd)) {
	 //echo "The file $cwd exists";
} else {
	//echo "The file $cwd does not exist";
	if (!mkdir($cwd, 0777, true)) {
    	die('Failed to create folders...');
	}
}
//exec('del D:/wamp/www/htdocs/simpad_dev/tmp/*.pdf');
array_map('unlink', glob($cwd."*.pdf"));
$link_pdf = "\"".$base_url."modul/restoran/skp_masal.php?thn=".$thn_pajak."&bln=".$bln_pajak."&bdir=".$base_dir."\"";
$pdf_gen_param = $pdf_gen." --page-height 330mm --page-width 215mm ".$link_pdf;
//$process = proc_open($_SESSION['PDF_GEN'].' --page-height 330mm --page-width 215mm '.$_SESSION['base_url'].'modul/reklame/skp.php?no_skp='.$no_skp.'&bdir='.$_SESSION['base_dir'].' '.$nm_file, $descr, $pipes, $cwd);
$process = proc_open($pdf_gen_param." ".$nm_file, $descr, $pipes, $cwd);

if (is_resource($process)) {
fputs($pipes[0], "");
fclose($pipes[0]);
fclose($pipes[1]);
while (!feof($pipes[2])) {
		$f = fgets($pipes[2]);		
		//echo "<pre>";
        //print $f;
		//echo "</pre>";
		//flush();
        //ob_flush();
		$msg = "<pre>".$f."</pre>";
		send_message('INFO', $f);
		//sleep(0.5);
    }
fclose($pipes[2]);
}
proc_close($process);
//echo $_SESSION['base_url']."tmp/".$_SESSION['user']."/".$nm_file;
send_message('SELESAI', $base_url."tmp/".$_SESSION['user']."/".$nm_file);
?>
