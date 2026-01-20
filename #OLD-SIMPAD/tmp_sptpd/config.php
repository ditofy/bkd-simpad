<?php
set_time_limit(0);

define('TAHUN', '2023');
define('NAMA_TDD', 'Drs. SYAFWAL, MM');
define('NIP_TDD', '19690116 199009 1 001');

define('DNS', '173.16.6.2/SIMPBB');
define('USER', 'REALISASI_PBB');
define('PASSWORD', 'R34L1S4S1_PBB_212');

define('SPPT_URL', 'https://pajakqris.payakumbuhkota.go.id/sppt');

function conn() {
    $options = [
        PDO::ATTR_EMULATE_PREPARES   => false, // Disable emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Disable errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Make the default fetch be an associative array
    ];
    
    try {
        return new PDO('oci:dbname=' . constant('DNS'), constant('USER'), constant('PASSWORD'), $options);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function penyebut($nilai)
{
    $nilai = abs((int) $nilai);
    $huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
    $temp = '';
    if($nilai < 12) {
        $temp = ' ' . $huruf[$nilai];
    } else if($nilai <20) {
        $temp = penyebut($nilai - 10) . ' belas';
    } else if($nilai < 100) {
        $temp = penyebut($nilai/10) . ' puluh' . penyebut($nilai % 10);
    } else if($nilai < 200) {
        $temp = ' seratus' . penyebut($nilai - 100);
    } else if($nilai < 1000) {
        $temp = penyebut($nilai/100) . ' ratus' . penyebut($nilai % 100);
    } else if($nilai < 2000) {
        $temp = ' seribu' . penyebut($nilai - 1000);
    } else if($nilai < 1000000) {
        $temp = penyebut($nilai/1000) . ' ribu' . penyebut($nilai % 1000);
    } else if($nilai < 1000000000) {
        $temp = penyebut($nilai/1000000) . ' juta' . penyebut($nilai % 1000000);
    } else if($nilai < 1000000000000) {
        $temp = penyebut($nilai/1000000000) . ' milyar' . penyebut(fmod($nilai,1000000000));
    } else if($nilai < 1000000000000000) {
        $temp = penyebut($nilai/1000000000000) . ' trilyun' . penyebut(fmod($nilai,1000000000000));
    }     
    return $temp;
}

function terbilang($nilai)
{
    if($nilai < 0) {
        $hasil = 'minus '. trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }     		
    return strtoupper($hasil) . ' RUPIAH';
}

function nominal($nilai)
{
    return (!empty($nilai)) ? number_format($nilai, 0, ',', '.') : '0';
}