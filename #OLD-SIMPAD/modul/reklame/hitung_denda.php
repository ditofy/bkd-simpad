<?php
//$jth_tempo='20-02-2017';
//$sekarang = date("d-m-Y");
//$sekarang = '10-09-2017';
$awal  = date_create('2017-02-10');
$akhir = date_create('2017-03-10'); // waktu sekarang
$diff  = date_diff( $awal, $akhir );
//$diff=date_diff(date_create(date("Y-m-d", strtotime($jth_tempo))),date_create(date("Y-m-d", strtotime($sekarang))));

//echo $diff->format("%R%a %m");
echo $diff->m;

if( ($diff->format("%R%a")-30) > 0) {
//	echo $diff->format("%R%a %m              "); //kena denda
	$tgl_jatuh_tempo = date_create(date("Y-m-d", strtotime($jth_tempo)));
	$tgl_jatuh_tempo->add(new DateInterval('P31D'));
	$jth_tempo = $tgl_jatuh_tempo->format('Y-m-d');
	$thn_a = date("Y", strtotime($jth_tempo));
	$thn_b = date("Y", strtotime($sekarang));
	$selisih_tahun = $thn_b-$thn_a;
	if ( $selisih_tahun == 0) {
		//echo "tahun sama";
		
		$bln_a = date("m", strtotime($jth_tempo));
		$bln_b = date("m", strtotime($sekarang));
		$denda = ($bln_b-$bln_a)+1;
		echo $denda;
	} else {
		echo "tahun beda";
		
		
	}
} else {
	//echo"tidak kena denda";
}

//$a = $diff->format("%R%a")+10;
//echo $a;
//$tgl_jatuh_tempo->add(new DateInterval('P10D'));
//echo $tgl_jatuh_tempo->format('Y-m-d');
?>