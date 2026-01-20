<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
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
$no_sptpd = $_REQUEST['no_sptpd'];
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
$link_pdf = "\"".$base_url."modul/hotel/sptpd.php?no_sptpd=".$no_sptpd."&bdir=".$base_dir."\"";
$pdf_gen_param = $pdf_gen." --page-height 400mm --page-width 215mm ".$link_pdf;
//$process = proc_open($_SESSION['PDF_GEN'].' --page-height 330mm --page-width 215mm '.$_SESSION['base_url'].'modul/reklame/skp.php?no_skp='.$no_skp.'&bdir='.$_SESSION['base_dir'].' '.$nm_file, $descr, $pipes, $cwd);
$process = proc_open($pdf_gen_param." ".$nm_file, $descr, $pipes, $cwd);
proc_close($process);
//echo $_SESSION['base_url']."tmp/".$_SESSION['user']."/".$nm_file;
echo $base_url."tmp/".$_SESSION['user']."/".$nm_file;
?>
