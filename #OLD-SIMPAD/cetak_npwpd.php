<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */

    // get the HTML
	$width_in_mm = '215mm';
	$height_in_mm = '140mm';
    ob_start();
    include('npwpd.php');
    $content = ob_get_clean();

    // convert in PDF
    require_once('modul/html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('L', array($width_in_mm,$height_in_mm), 'en', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('npwpd.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }