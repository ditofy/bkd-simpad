<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
$conn = oci_connect('REALISASI_PBB', 'R34L1S4S1_PBB_212', '192.168.10.21/SIMPBB');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$bulan = array(1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember");
$kabid = 'RESTI DESMILA, SSTP, M.Si';
$nip_kabid = '19791221 199810 2 001';
$kadis = 'Drs. SYAFWAL, MM';
$nip_kadis = '19690116 199009 1 001';
?>