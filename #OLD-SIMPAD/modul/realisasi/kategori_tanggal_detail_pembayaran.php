<link href="css/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">
<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
function selisih($jam_keluar) {
$jam_selesai=date("H:i:s",mktime(date("H",strtotime($jam_keluar))-0,date("i",strtotime($jam_keluar))-0,date("s",strtotime($jam_keluar)),0,0,0));
return $jam_selesai;
}

include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$data = $_REQUEST['postdata'];
$page = pg_escape_string($_REQUEST['page']);
$num_per_page = 30;
$sub_total = 0;
$grand_total = 0;
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$tgl_awal = $arr_data['tgl-awal'];
$tgl_akhir =  $arr_data['tgl-akhir'];
$jns_objek =  $arr_data['jenis-objek'];
$jns_laporan =  $arr_data['jenis-laporan'];
//$schema = $list_schema[$arr_data['jns-obj']];
//$page = pg_escape_string($_REQUEST['page']);
$num_per_page = 30;
$sub_total = 0;
$grand_total = 0;
//$arr_data = array();

$total_pokok = 0;
$total_denda = 0;
$total = 0;
$query_obj= pg_query("select nm_obj_pajak from public.obj_pajak where kd_obj_pajak='$jns_objek'")or die('Query failed: ' . pg_last_error());
$row_obj=pg_fetch_array($query_obj);
//Hitung Halaman
if ($jns_laporan == '02'){
switch ($jns_objek) {
       case "01":
			$num_results = pg_query("select COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total from air_tanah.pembayaran A INNER JOIN 
			air_tanah.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ") or die('Query failed: ' . pg_last_error());
       break;
	   case "02":
			$num_results  = pg_query("select COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total from reklame.pembayaran A INNER JOIN 
			reklame.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ") or die('Query failed: ' . pg_last_error());
       break;
	   case "03":
			$num_results  = pg_query("select COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total from restoran.pembayaran A LEFT JOIN 
			restoran.skpdkb C ON C.jns_surat=A.jns_surat and C.thn_pajak=A.thn_pajak and C.bln_pajak=A.bln_pajak and C.kd_obj_pajak=A.kd_obj_pajak and A.no_urut_surat=C.no_urut_surat 
			LEFT JOIN restoran.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp D on D.kd_provinsi=b.kd_provinsi and D.kd_kota=b.kd_kota and D.kd_jns=b.kd_jns and D.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha E on E.kd_obj_pajak=b.kd_obj_pajak and E.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'") or die('Query failed: ' . pg_last_error());
       break;
	   case "04":
			$num_results = pg_query("select COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total from ppj.pembayaran A INNER JOIN 
			ppj.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ") or die('Query failed: ' . pg_last_error());
       break;
	   case "05":
			$num_results  = pg_query("select COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total from parkir.pembayaran A INNER JOIN 
			parkir.skpdkb C ON C.jns_surat=A.jns_surat and C.thn_pajak=A.thn_pajak and C.bln_pajak=A.bln_pajak and C.kd_obj_pajak=A.kd_obj_pajak and A.no_urut_surat=C.no_urut_surat 
			LEFT JOIN parkir.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp D on D.kd_provinsi=b.kd_provinsi and D.kd_kota=b.kd_kota and D.kd_jns=b.kd_jns and D.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha E on E.kd_obj_pajak=b.kd_obj_pajak and E.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ") or die('Query failed: ' . pg_last_error());
       break;
	   case "06":
			$num_results  = pg_query("select COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total from hiburan.pembayaran A INNER JOIN 
			hiburan.skpdkb C ON C.jns_surat=A.jns_surat and C.thn_pajak=A.thn_pajak and C.bln_pajak=A.bln_pajak and C.kd_obj_pajak=A.kd_obj_pajak and A.no_urut_surat=C.no_urut_surat 
			LEFT JOIN hiburan.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp D on D.kd_provinsi=b.kd_provinsi and D.kd_kota=b.kd_kota and D.kd_jns=b.kd_jns and D.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha E on E.kd_obj_pajak=b.kd_obj_pajak and E.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ") or die('Query failed: ' . pg_last_error());
       break;
	   case "07":
			$num_results  = pg_query("select COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total from hotel.pembayaran A INNER JOIN 
			hotel.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ") or die('Query failed: ' . pg_last_error());
       break;
	   case "08":
			$num_results = pg_query("select COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total from galianc.pembayaran A INNER JOIN 
			galianc.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ") or die('Query failed: ' . pg_last_error());
       break;
	   case "09":
			$num_results  = pg_query("SELECT COUNT(*) AS JUMLAH,SUM(A.POKOK_PAJAK) AS G_TOTAL from BPHTB.PEMBAYARAN A  
			LEFT JOIN BPHTB.SKPDKB E ON A.JNS_SURAT=E.JNS_SURAT AND A.THN_PAJAK=E.THN_PAJAK AND A.BLN_PAJAK=E.BLN_PAJAK AND A.KD_OBJ_PAJAK=E.KD_OBJ_PAJAK AND A.NO_URUT_SURAT=E.NO_URUT_SURAT 
			LEFT JOIN BPHTB.SSPD B ON A.JNS_SURAT=B.JNS_SURAT AND A.THN_PAJAK=B.THN_PAJAK AND A.BLN_PAJAK=B.BLN_PAJAK AND A.KD_OBJ_PAJAK=B.KD_OBJ_PAJAK AND A.NO_URUT_SURAT=B.NO_URUT_SURAT 
			LEFT JOIN PUBLIC.WP C ON C.NIK=B.NIK 
			LEFT JOIN PUBLIC.KEG_USAHA D ON D.KD_OBJ_PAJAK=B.KD_OBJ_PAJAK AND E.KD_KEG_USAHA=B.KD_SH
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ") or die('Query failed: ' . pg_last_error());
       break;
	   case "10":
			$query = pg_query("select COUNT(*) AS jumlah,SUM(A.pkb_pokok) AS g_total from pkb.pembayaran_opsen A
			WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'") or die('Query failed: ' . pg_last_error());
       break; 
	   case "11":
			$query = pg_query("select COUNT(*) AS jumlah,SUM(A.bbnkb_pokok) AS g_total from bbnkb.pembayaran_opsen A
			WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'") or die('Query failed: ' . pg_last_error());
       break;  
	   default:
	   $num_results  = pg_query("select c.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from bphtb.pembayaran A INNER JOIN 
			bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.keg_usaha C on C.kd_obj_pajak=b.kd_obj_pajak and C.kd_keg_usaha=b.kd_sh
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by c.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
	   break;
	   
}


$numrows=pg_fetch_array($num_results);
$num_pages = ceil($numrows['jumlah'] / $num_per_page);
if ($page > $num_pages || $page == '') {
  $page = $num_pages - 1;
}
if ($page <= 0) {
  $page = 0;
}
$offset = $page * $num_per_page;
$grand_total = $numrows['g_total'];

//echo $num_pages;
//echo $offset;
//echo $grand_total;
pg_free_result($num_results);
//$page= 22;

//if ($jns_laporan == '02'){
switch ($jns_objek) {
       case "01":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as 								npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar from air_tanah.pembayaran A INNER JOIN 
			air_tanah.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY D.nm_keg_usaha ASC LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
       break;
	   case "02":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_reklame as nm_usaha,b.alamat_reklame as alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.no_bukti,A.nm_cab_bank from reklame.pembayaran A INNER JOIN 
			reklame.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
       break;
	   case "03":
$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,D.nama,E.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank from restoran.pembayaran A 
LEFT JOIN restoran.skpdkb C ON C.jns_surat=A.jns_surat and A.thn_pajak=C.thn_pajak and C.bln_pajak=A.bln_pajak and C.kd_obj_pajak=A.kd_obj_pajak and C.no_urut_surat=A.no_urut_surat
		LEFT JOIN restoran.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			
			LEFT JOIN public.wp D on D.kd_provinsi=b.kd_provinsi and D.kd_kota=b.kd_kota and D.kd_jns=b.kd_jns and D.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha E on E.kd_obj_pajak=b.kd_obj_pajak and E.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());

       break;
	   case "04":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank from ppj.pembayaran A INNER JOIN 
			ppj.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
       break;
	   case "05":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,D.nama,E.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank from parkir.pembayaran A 
			LEFT JOIN parkir.skpdkb C ON C.jns_surat=A.jns_surat and A.thn_pajak=C.thn_pajak and C.bln_pajak=A.bln_pajak and C.kd_obj_pajak=A.kd_obj_pajak and C.no_urut_surat=A.no_urut_surat
			LEFT JOIN parkir.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp D on D.kd_provinsi=b.kd_provinsi and D.kd_kota=b.kd_kota and D.kd_jns=b.kd_jns and D.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha E on E.kd_obj_pajak=b.kd_obj_pajak and E.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
       break;
	   case "06":
			$query = pg_query("Select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,D.nama,E.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank from hiburan.pembayaran A 
			LEFT JOIN hiburan.skpdkb C ON C.jns_surat=A.jns_surat and A.thn_pajak=C.thn_pajak and C.bln_pajak=A.bln_pajak and C.kd_obj_pajak=A.kd_obj_pajak and C.no_urut_surat=A.no_urut_surat
			LEFT JOIN hiburan.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp D on D.kd_provinsi=b.kd_provinsi and D.kd_kota=b.kd_kota and D.kd_jns=b.kd_jns and D.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha E on E.kd_obj_pajak=b.kd_obj_pajak and E.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
       break;
	   case "07":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank from hotel.pembayaran A INNER JOIN 
			hotel.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
       break;
	   case "08":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank from galianc.pembayaran A INNER JOIN 
			galianc.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
       break;
	   case "09":
			$query = pg_query("SELECT A.JNS_SURAT||'.'||A.THN_PAJAK||'.'||A.BLN_PAJAK||'.'||A.KD_OBJ_PAJAK||'.'||A.NO_URUT_SURAT AS NO_SURAT,C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg as npwpd,A.NAMA_WP AS NAMA,A.NOP,D.nm_keg_usaha,b.nama_sppt as nm_usaha,b.alamat_op as alamat_usaha,A.pokok_pajak,A.tgl_bayar,B.kd_propinsi||'.'||B.kd_dati2||'.'||B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_blok||'.'||B.no_urut||'.'||B.kd_jns_op as nop,A.tgl_bayar,A.no_bukti,A.nm_cab_bank FROM BPHTB.PEMBAYARAN A  
LEFT JOIN BPHTB.SKPDKB E ON 
A.JNS_SURAT=E.JNS_SURAT AND
A.THN_PAJAK=E.THN_PAJAK AND
A.BLN_PAJAK=E.BLN_PAJAK AND
A.KD_OBJ_PAJAK=E.KD_OBJ_PAJAK AND
A.NO_URUT_SURAT=E.NO_URUT_SURAT
LEFT JOIN BPHTB.SSPD B ON 
A.JNS_SURAT=B.JNS_SURAT AND
A.THN_PAJAK=B.THN_PAJAK AND
A.BLN_PAJAK=B.BLN_PAJAK AND
A.KD_OBJ_PAJAK=B.KD_OBJ_PAJAK AND
A.NO_URUT_SURAT=B.NO_URUT_SURAT
LEFT JOIN PUBLIC.WP C ON C.NIK=B.NIK
LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_sh
WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
       break;
	    case "10":
			$query = pg_query("select A.unit,A.pkb_pokok as pokok_pajak,A.pkb_denda,A.payment_point,A.uptd_transaksi from pkb.pembayaran_opsen A
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY payment_point ASC") or die('Query failed: ' . pg_last_error());
       break; 
	    case "11":
			$query = pg_query("select A.unit,A.bbnkb_pokok as pokok_pajak,A.bbnkb_denda,A.payment_point,A.uptd_transaksi from bbnkb.pembayaran_opsen A
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY payment_point ASC") or die('Query failed: ' . pg_last_error());
       break;  
	   default:
	   $query = pg_query("select c.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from bphtb.pembayaran A INNER JOIN 
			bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.keg_usaha C on C.kd_obj_pajak=b.kd_obj_pajak and C.kd_keg_usaha=b.kd_sh
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by c.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
	   break;
	   
}
?>
<script language="javascript">
$(document).ready(function () {
	
	$('#download-xls').click(function(){		
			var inputs = $('#filter-data').serializeArray();
			$.post('inc/to_xls.php', { data: inputs }, function(response){
    		if (!response.status) {
        		notif('Notifikasi','Error calling save','error');
        		return;
    		}
    		if (response.status != 'OK') {
        		notif('Notifikasi',response.status,'error');
        		return;
    		}
    		window.open('/print/list_laporan_realisasi_xls.php');
			});	
	});
	
	
});
</script>
<button id="download-xls" type="button" class="btn btn-dark">Download XLS</button>

<div class="x_content">
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                         <?php if($jns_objek < 10 ){ ?>
									    <thead>
											<tr class="headings">
											 <td colspan="12" align="center"><h3>DATA PEMBAYARAN <?php echo $row_obj['nm_obj_pajak']; ?> TANGGAL: <?php echo $tgl_awal; ?> s.d <?php echo $tgl_akhir; ?> </h3></td>
											 </tr>
                                            <tr class="headings">  
											    <th>NO </th>                                       
                                                <th>NO BAYAR </th>                                       
                                                <th>NOP </th>
												<th>NPWPD </th>
												<th>NAMA WAJIB PAJAK</th>
												<th>JENIS USAHA</th> 
												<th>NAMA USAHA/OBJEK</th>
												<th>ALAMAT USAHA/OBJEK</th>   
												<th>JUMLAH PAJAK</th>
												<th>TANGGAL BAYAR</th>  
												<th>NO BUKTI</th>    
												<th>CABANG</th>                                  	
                                            </tr>
                                        </thead><?php } else {  ?>
										 <thead>
											<tr class="headings">
											 <td colspan="6" align="center"><h3>DATA PEMBAYARAN <?php echo $row_obj['nm_obj_pajak']; ?> TANGGAL: <?php echo $tgl_awal; ?> s.d <?php echo $tgl_akhir; ?></h3></td>
											 </tr>
                                            <tr class="headings">  
											    <th>NO </th>                                       
                                                <th align="center">JUMLAH UNIT </th> 
												<th>PAYMEN POINT</th>
												<th>UPTD</th> 
												<th>POKOK DENDA </th>                                      
                                                <th>POKOK PAJAK </th>
											    <th>TOTAL  </th>
                                            </tr> 
                                        </thead> <?php  }?>
										<tbody>
<?php
$no = $offset;
if(pg_num_rows($query) != '0')
{
while ($row = pg_fetch_array($query))
	{
	$no++;
	$sub_total = $sub_total + $row['pokok_pajak'];
	
												if($jns_objek < 10 ){ ?>
												<tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td> 
												<td class=" "><?php echo $row['no_surat']; ?></td> 
												<td class=" "><?php echo $row['nop']; ?></td>  
												<td class=" "><?php echo $row['npwpd']; ?></td> 
												<td class=" "><?php echo $row['nama']; ?></td> 
												<td class=" "><?php echo $row['nm_keg_usaha']; ?></td> 
												<td class=" "><?php echo $row['nm_usaha']; ?></td> 
												<td class=" "><?php echo $row['alamat_usaha']; ?></td>                                            
                                                <td class=" "><?php echo number_format ($row['pokok_pajak']); ?></td> 
												<td class=" "><?php echo $row['tgl_bayar']; ?></td>   
												<td class=" "><?php echo $row['no_bukti']; ?></td>   
												<td class=" "><?php echo $row['nm_cab_bank']; ?></td>                                                 
                                                </tr>
										<?php  } else { ?>
												<tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td> 
												<td class=" ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['unit']; ?></td> 
												<td class=" "><?php echo $row['payment_point']; ?></td> 
												<td class=" "><?php echo $row['uptd_transaksi']; ?></td>  
												<td class=" "><?php echo  number_format($row['pkb_denda']); ?></td> 
												<td class=" "><?php echo  number_format($row['pokok_pajak']); ?></td> 
												<td class=" "><?php $total = $row['pkb_denda'] + $row['pokok_pajak'];
												echo  number_format($total); ?></td> 
                                                </tr>
										<?php } ?>   
<?php
}
}
//oci_free_statement($stid);
//oci_close($conn);
								if($jns_objek < 10 ){ ?>
										<tr>
											<?php
												
											echo "<td colspan=\"9\" align=\"center\" style=\"font-size:12px;\">Sub. Total</td>";
												
											?>											
											<td align="right" style="font-size:12px;"><?php echo number_format($sub_total); ?></td>
											<td></td>
											<td></td>
											<?php
												if($kd_obj_pajak == '02') {
													echo "<td class=\" \"></td>";
												}
											?>	
										</tr> 
										<tr>
											<?php
												
													echo "<td colspan=\"9\" align=\"center\" style=\"font-size:12px;\">Grand Total</td>";
												
											?>
											<td align="right" style="font-size:12px;"><?php echo number_format($grand_total); ?></td>
											<td></td>
											<td></td>
										</tr>    
										<?php } else { ?>
										<tr>
											<?php
												
											echo "<td colspan=\"4\" align=\"center\" style=\"font-size:12px;\">Sub. Total</td>";
												
											?>											
											<td align="right" style="font-size:12px;"><?php echo number_format($sub_total); ?></td>
											<td></td>
											<td></td>
											
										</tr> 
										<tr>
											<?php
												
													echo "<td colspan=\"4\" align=\"center\" style=\"font-size:12px;\">Grand Total</td>";
												
											?>
											<td align="right" style="font-size:12px;"><?php echo number_format($grand_total); ?></td>
											<td></td>
											<td></td>
										</tr>    
										<?php }?>
										 
</tbody>

                            </table>
                               <?php
$paging_paging = 30;
$max_paging_paging = ceil($num_pages / $paging_paging);
$paging_page_sekarang = ceil(($page+1) / $paging_paging);
echo "<center><ul class=\"pagination\">";
$page_start_paging = (($paging_page_sekarang*$paging_paging)-$paging_paging);
$pange_end_paging = (($paging_page_sekarang*$paging_paging)-1);
//echo "start:$page_start_paging End:$pange_end_paging";
$backward = $paging_page_sekarang - 1;
if($backward < 1) {
	echo "<li class=\"disabled\"><a href=\"#\"><span class=\"glyphicon glyphicon-backward\"></span></a></li>";
} else {
		$page_back = (($backward*$paging_paging)-$paging_paging);
		echo "<li><a href=\"#\" onClick=\"list_data($page_back)\"><span class=\"glyphicon glyphicon-backward\"></span></a></li>";
}
for($i=$page_start_paging;$i <= $pange_end_paging;$i++) {
	if($i > ($num_pages - 1)) {
		break;
	}
		if($i == ($page)){
			echo "<li class=\"active\"><a href=\"#\">$i</a></li>";	
		} else {
			echo "<li><a href=\"#\" onClick=\"list_data($i)\">$i</a></li>";
		}
}
$forward = $paging_page_sekarang + 1;
if($forward > $max_paging_paging) {
	echo "<li class=\"disabled\"><a href=\"#\"><span class=\"glyphicon glyphicon-forward\"></span></a></li>";	
} else {
	$page_next = (($forward*$paging_paging)-$paging_paging);
	echo "<li><a href=\"#\" onClick=\"list_data($page_next)\"><span class=\"glyphicon glyphicon-forward\"></span></a></li>";
}
echo "</ul>
</center>";
pg_free_result($query);
pg_close($dbconn);
}
else if ($jns_laporan == '01'){

switch ($jns_objek) {
       case "01":
			$query = pg_query("select d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from air_tanah.pembayaran A INNER JOIN 
			air_tanah.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN air_tanah.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "02":
			$query = pg_query("select d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from reklame.pembayaran A INNER JOIN 
			reklame.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN reklame.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "03":
			$query = pg_query("select d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from restoran.pembayaran A INNER JOIN 
			restoran.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN restoran.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "04":
			$query = pg_query("select d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from ppj.pembayaran A INNER JOIN 
			ppj.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN ppj.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "05":
			$query = pg_query("select d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from parkir.pembayaran A INNER JOIN 
			parkir.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN parkir.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "06":
			$query = pg_query("select d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from hiburan.pembayaran A INNER JOIN 
			hiburan.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN hiburan.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "07":
			$query = pg_query("select d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from hotel.pembayaran A INNER JOIN 
			hotel.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN hotel.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "08":
			$query = pg_query("select d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from galianc.pembayaran A INNER JOIN 
			galianc.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN galianc.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "09":
			$query = pg_query("select c.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from bphtb.pembayaran A LEFT JOIN 
			bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.keg_usaha C on C.kd_obj_pajak=b.kd_obj_pajak and C.kd_keg_usaha=b.kd_sh
			LEFT JOIN bphtb.skpdkb D on D.jns_surat=B.jns_surat and D.thn_pajak=B.thn_pajak and D.bln_pajak=B.bln_pajak and D.kd_obj_pajak=B.kd_obj_pajak and D.no_urut_surat=B.no_urut_surat 
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by c.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "10":
			$query = pg_query("select A.unit,A.pkb_pokok as pokok_pajak,A.pkb_denda,A.payment_point,A.uptd_transaksi from pkb.pembayaran_opsen A
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY payment_point ASC") or die('Query failed: ' . pg_last_error());
       break; 
	    case "11":
			$query = pg_query("select A.unit,A.bbnkb_pokok as pokok_pajak,A.bbnkb_denda,A.payment_point,A.uptd_transaksi from bbnkb.pembayaran_opsen A
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY payment_point ASC") or die('Query failed: ' . pg_last_error());
       break;  
	   default:
	   $query = pg_query("select c.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from bphtb.pembayaran A INNER JOIN 
			bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.keg_usaha C on C.kd_obj_pajak=b.kd_obj_pajak and C.kd_keg_usaha=b.kd_sh
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by c.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
	   break;
	   
}
?>
<div class="x_content">
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="2" align="center"><h3>DATA PEMBAYARAN <?php echo $row_obj['nm_obj_pajak']; ?> TANGGAL: <?php echo $tgl_awal; ?>&nbsp;s/d&nbsp;<?php echo $tgl_akhir; ?></h3></td>
											 </tr>
                                            <tr class="headings">                                         
                                                <th>KATEGORI </th>                                       
                                                <th>POKOK </th>
												                                           	
                                            </tr>
                                        </thead>
										<tbody>
<?php
$no = 0;
while ($row = pg_fetch_array($query))
	{
	$no++;
	$sub_total = $sub_total + $row['pokok_pajak'];
	$total=$total+$row['jumlah'];
?>
<tr class="even pointer">
                                                <td class=" "><?php echo $row['nm_keg_usaha']; ?></td>                                                
                                                <td class=" "><?php echo number_format ($row['jumlah']); ?></td>                                                
                                               
                                            </tr>
<?php
}
//oci_free_statement($stid);
//oci_close($conn);
?>
<tr class="even pointer">
<td class=" "><strong>TOTAL</strong></td>
<td class=" " ><strong><?php echo number_format($total); ?></strong></td>
</tr>
</tbody>

                                    </table>
                                </div>



<?php }
/* else {
//PAJAK AIR TANAH

$query_r_air_tanah = "SELECT SUM(pokok_pajak) AS r_abt FROM air_tanah.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_r_air_tanah = pg_query($query_r_air_tanah) or die('Query failed: ' . pg_last_error());
$data_r_air_tanah = pg_fetch_array($result_r_air_tanah);
$pokok_r_air_tanah = $data_r_air_tanah['r_abt'];
$query_air_tanah = "SELECT target FROM public.target WHERE thn_pajak='2024' AND kd_obj_pajak='01'";
$result_air_tanah = pg_query($query_air_tanah) or die('Query failed: ' . pg_last_error());
$data_air_tanah = pg_fetch_array($result_air_tanah);
$target_air_tanah = $data_air_tanah['target'];
$persen_air_tanah = round(($pokok_r_air_tanah/$target_air_tanah)*100);
pg_free_result($result_air_tanah);
////////////
//PAJAK REKLAME
$query_r_reklame = "SELECT SUM(pokok_pajak) AS r_reklame FROM reklame.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_r_reklame = pg_query($query_r_reklame) or die('Query failed: ' . pg_last_error());
$data_r_reklame = pg_fetch_array($result_r_reklame);
$pokok_r_reklame = $data_r_reklame['r_reklame'];
$query_reklame = "SELECT target FROM public.target WHERE thn_pajak='2024' AND kd_obj_pajak='02'";
$result_reklame = pg_query($query_reklame) or die('Query failed: ' . pg_last_error());
$data_reklame = pg_fetch_array($result_reklame);
$target_reklame = $data_reklame['target'];
$persen_reklame = round(($pokok_r_reklame/$target_reklame)*100);
pg_free_result($result_reklame);
////////////
//PAJAK RESTORAN
$query_r_restoran = "SELECT SUM(pokok_pajak) AS r_restoran FROM restoran.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_r_restoran = pg_query($query_r_restoran) or die('Query failed: ' . pg_last_error());
$data_r_restoran = pg_fetch_array($result_r_restoran);
$pokok_r_restoran = $data_r_restoran['r_restoran'];
$query_restoran = "SELECT target FROM public.target WHERE thn_pajak='2024' AND kd_obj_pajak='03'";
$result_restoran = pg_query($query_restoran) or die('Query failed: ' . pg_last_error());
$data_restoran = pg_fetch_array($result_restoran);
$target_restoran = $data_restoran['target'];
$persen_restoran = round(($pokok_r_restoran/$target_restoran)*100);
pg_free_result($result_restoran);
////////////
//PAJAK PARKIR
$query_r_parkir = "SELECT SUM(pokok_pajak) AS r_parkir FROM parkir.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_r_parkir = pg_query($query_r_parkir) or die('Query failed: ' . pg_last_error());
$data_r_parkir = pg_fetch_array($result_r_parkir);
$pokok_r_parkir = $data_r_parkir['r_parkir'];
$query_parkir = "SELECT target FROM public.target WHERE thn_pajak='2024' AND kd_obj_pajak='05'";
$result_parkir = pg_query($query_parkir) or die('Query failed: ' . pg_last_error());
$data_parkir = pg_fetch_array($result_parkir);
$target_parkir = $data_parkir['target'];
$persen_parkir = round(($pokok_r_parkir/$target_parkir)*100);
pg_free_result($result_parkir);
////////////
//PAJAK PPJ
$query_r_ppj = "SELECT SUM(pokok_pajak) AS r_ppj FROM ppj.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_r_ppj = pg_query($query_r_ppj) or die('Query failed: ' . pg_last_error());
$data_r_ppj = pg_fetch_array($result_r_ppj);
$pokok_r_ppj = $data_r_ppj['r_ppj'];
$query_ppj = "SELECT target FROM public.target WHERE thn_pajak='2024' AND kd_obj_pajak='04'";
$result_ppj = pg_query($query_ppj) or die('Query failed: ' . pg_last_error());
$data_ppj = pg_fetch_array($result_ppj);
$target_ppj = $data_ppj['target'];
$persen_ppj = round(($pokok_r_ppj/$target_ppj)*100);
pg_free_result($result_ppj);
////////////
//PAJAK HIBURAN
$query_r_hiburan = "SELECT SUM(pokok_pajak) AS r_hiburan FROM hiburan.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_r_hiburan = pg_query($query_r_hiburan) or die('Query failed: ' . pg_last_error());
$data_r_hiburan = pg_fetch_array($result_r_hiburan);
$pokok_r_hiburan = $data_r_hiburan['r_hiburan'];
$query_hiburan = "SELECT target FROM public.target WHERE thn_pajak='2024' AND kd_obj_pajak='06'";
$result_hiburan = pg_query($query_hiburan) or die('Query failed: ' . pg_last_error());
$data_hiburan = pg_fetch_array($result_hiburan);
$target_hiburan = $data_hiburan['target'];
$persen_hiburan = round(($pokok_r_hiburan/$target_hiburan)*100);
pg_free_result($result_hiburan);
////////////
//PAJAK HOTEL
$query_r_hotel = "SELECT SUM(pokok_pajak) AS r_hotel FROM hotel.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_r_hotel = pg_query($query_r_hotel) or die('Query failed: ' . pg_last_error());
$data_r_hotel = pg_fetch_array($result_r_hotel);
$pokok_r_hotel = $data_r_hotel['r_hotel'];
$query_hotel = "SELECT target FROM public.target WHERE thn_pajak='2024' AND kd_obj_pajak='07'";
$result_hotel = pg_query($query_hotel) or die('Query failed: ' . pg_last_error());
$data_hotel = pg_fetch_array($result_hotel);
$target_hotel = $data_hotel['target'];
$persen_hotel = round(($pokok_r_hotel/$target_hotel)*100);
pg_free_result($result_hotel);
////////////
//PAJAK GALIAN C
$query_r_galianc = "SELECT SUM(pokok_pajak) AS r_galianc FROM galianc.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_r_galianc = pg_query($query_r_galianc) or die('Query failed: ' . pg_last_error());
$data_r_galianc = pg_fetch_array($result_r_galianc);
$pokok_r_galianc = $data_r_galianc['r_galianc'];
$query_galianc = "SELECT target FROM public.target WHERE thn_pajak='2024' AND kd_obj_pajak='08'";
$result_galianc = pg_query($query_galianc) or die('Query failed: ' . pg_last_error());
$data_galianc = pg_fetch_array($result_galianc);
$target_galianc = $data_galianc['target'];
$persen_galianc = round(($pokok_r_galianc/$target_galianc)*100);
pg_free_result($result_galianc);
////////////
//PAJAK BPHTB
$query_r_bphtb = "SELECT SUM(pokok_pajak) AS r_bphtb FROM bphtb.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_r_bphtb = pg_query($query_r_bphtb) or die('Query failed: ' . pg_last_error());
$data_r_bphtb = pg_fetch_array($result_r_bphtb);
$pokok_r_bphtb = $data_r_bphtb['r_bphtb'];
$query_bphtb = "SELECT target FROM public.target WHERE thn_pajak='2024' AND kd_obj_pajak='09'";
$result_bphtb = pg_query($query_bphtb) or die('Query failed: ' . pg_last_error());
$data_bphtb = pg_fetch_array($result_bphtb);
$target_bphtb = $data_bphtb['target'];
$persen_bphtb = round(($pokok_r_bphtb/$target_bphtb)*100);
pg_free_result($result_bphtb);
////////////
$target_tahun=$target_bphtb+$target_galianc+$target_hotel+$target_hiburan+$target_ppj+$target_parkir+$target_restoran+$target_reklame+$target_air_tanah+target_pbb;
$realisasi_tahun=$pokok_r_bphtb+$pokok_r_galianc+$pokok_r_hotel+$pokok_r_hiburan+$pokok_r_ppj+$pokok_r_parkir+$pokok_r_restoran+$pokok_r_reklame+$pokok_r_air_tanah+$pokok_r_pbb;
$persen_tahun = round(($realisasi_tahun/$target_tahun)*100);

/////PBB
$pokok_r_pbb= 1603524196 ;
$target_pbb= 1369731278 ;
$persen_pbb=round(($pokok_r_pbb/$target_pbb)*100);
} ?>*/?>
<!-- <div class="x_content">
 table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="4" align="center"><h3>DATA PEMBAYARAN PAJAK DAERAH TANGGAL: <?phpecho $tgl_awal; ?>&nbsp;s/d&nbsp;<?phpecho $tgl_akhir; ?></h3></td>
											 </tr>
                                            <tr class="headings">                                         
                                                <th>JENIS PAJAK </th>                                       
                                                <th align="right"><div align="right">TARGET </div></th>
												<th align="right"><div align="right">REALISASI </div></th>
												<th><div align="center">PERSEN </div></th>                                           	
                                            </tr>
                                        </thead>
	<tr class="even pointer">
    <td class=" ">PAJAK AIR TANAH</td>                                                
    <td align="right"><?php// echo number_format($target_air_tanah); ?></td>
	<td align="right"><?php//echo number_format($pokok_r_air_tanah); ?></td>
	<td align="center"><?php// echo $persen_air_tanah; ?>&nbsp;%</td>
	</tr>
	<tr class="even pointer">
    <td class=" ">PAJAK REKLAME</td>                                                
    <td align="right"><?phpecho number_format($target_reklame); ?></td>
	<td align="right"><?phpecho number_format($pokok_r_reklame); ?></td>
	<td align="center"><?phpecho $persen_reklame; ?>&nbsp;%</td>
	</tr>
	<tr class="even pointer">
    <td class=" ">PAJAK RESTORAN</td>                                                
    <td align="right"><?phpecho number_format($target_restoran); ?></td>
	<td align="right"><?phpecho number_format($pokok_r_restoran); ?></td>
	<td align="center"><?phpecho $persen_restoran; ?>&nbsp;%</td>
	</tr>
	<tr class="even pointer">
    <td class=" ">PAJAK PARKIR</td>                                                
    <td align="right"><?phpecho number_format($target_parkir); ?></td>
	<td align="right"><?phpecho number_format($pokok_r_parkir); ?></td>
	<td align="center"><?phpecho $persen_parkir; ?>&nbsp;%</td>
	</tr>
	<tr class="even pointer">
    <td class=" ">PAJAK PENERANGAN JALAN</td>                                                
    <td align="right"><?phpecho number_format($target_ppj); ?></td>
	<td align="right"><?phpecho number_format($pokok_r_ppj); ?></td>
	<td align="center"><?phpecho $persen_ppj; ?>&nbsp;%</td>
	</tr>
	<tr class="even pointer">
    <td class=" ">PAJAK HIBURAN</td>                                                
    <td align="right"><?phpecho number_format($target_hiburan); ?></td>
	<td align="right"><?phpecho number_format($pokok_r_hiburan); ?></td>
	<td align="center"><?phpecho $persen_hiburan; ?>&nbsp;%</td>
	</tr>
	<tr class="even pointer">
    <td class=" ">PAJAK HOTEL</td>                                                
    <td align="right"><?phpecho number_format($target_hotel); ?></td>
	<td align="right"><?phpecho number_format($pokok_r_hotel); ?></td>
	<td align="center"><?phpecho $persen_hotel; ?>&nbsp;%</td>
	</tr>
	<tr class="even pointer">
    <td class=" ">PAJAK GALIAN C</td>                                                
    <td align="right"><?phpecho number_format($target_galianc); ?></td>
	<td align="right"><?phpecho number_format($pokok_r_galianc); ?></td>
	<td align="center"><?phpecho $persen_galianc; ?>&nbsp;%</td>
	</tr>
	<tr class="even pointer">
    <td class=" ">BPHTB</td>                                                
    <td align="right"><?phpecho number_format($target_bphtb); ?></td>
	<td align="right"><?phpecho number_format($pokok_r_bphtb); ?></td>
	<td align="center"><?phpecho $persen_bphtb; ?>&nbsp;%</td>
	</tr>
	<tr class="even pointer">
    <td class=" ">PBB</td>                                                
    <td align="right"><?phpecho number_format($target_pbb); ?></td>
	<td align="right"><?phpecho number_format($pokok_r_pbb); ?></td>
	<td align="center"><?phpecho $persen_pbb; ?>&nbsp;%</td>
	</tr>
	<tr class="even pointer">
    <td class=" "><b>TOTAL</b></td>                                                
    <td align="right"><b><?phpecho number_format($target_bphtb+$target_galianc+$target_hotel+$target_hiburan+$target_ppj+$target_parkir+$target_restoran+$target_reklame+$target_air_tanah+$target_pbb); ?></b></td>
	<td align="right"><b><?phpecho number_format($pokok_r_bphtb+$pokok_r_galianc+$pokok_r_hotel+$pokok_r_hiburan+$pokok_r_ppj+$pokok_r_parkir+$pokok_r_restoran+$pokok_r_reklame+$pokok_r_air_tanah+$pokok_r_pbb); ?></b></td>
	<td align="center"><b><?phpecho $persen_tahun; ?>&nbsp;%</b></td>
	</tr>
										
<tbody>
</table> -->