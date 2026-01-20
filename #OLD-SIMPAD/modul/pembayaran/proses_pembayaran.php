<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
//include_once $_SESSION['base_dir']."inc/db.mssql.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$error = false;
$no_surat = $_REQUEST['no_surat'];
list($kd_jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_surat) = explode(".",$no_surat);
$pokok_pajak = $_REQUEST['pokok_pajak'];
$denda = $_REQUEST['denda'];
$tgl_bayar = $_REQUEST['tgl_bayar'];
$no_bukti = $_REQUEST['no_bukti'];
$pemungut =$_REQUEST['nip_pemungut'];
$schema = $list_schema[$kd_obj_pajak];
$table = $jns_surat[$kd_jns_surat];
$no_setor=$_REQUEST['no_setor'];
$tgl_setor=$_REQUEST['tgl_setor'];
$nip = $_SESSION['nip'];
$nama_wp=$_REQUEST['namawp'];
$nama_obj=pg_escape_string($_REQUEST['nmobj']);
$alamat_wp=pg_escape_string($_REQUEST['alamatwp']);
$mtgkey=pg_escape_string($_REQUEST['mtgkey']);
$notbp=$kd_jns_surat."".$thn_pajak."".$bln_pajak."".$kd_obj_pajak.""."$no_urut_surat"."/TBP/02/2020";
$nosts=$kd_jns_surat."".$thn_pajak."".$bln_pajak."".$kd_obj_pajak.""."$no_urut_surat"."/STS/02/2020";
//$tgl=date("$tgl_bayar","Y-m-d h:i:sa");
//$old_date ='2020-01-10';
$tgl = date('Y-m-d H:i:s', strtotime($tgl_bayar));
//
$query_cek = pg_query(" SELECT C.jns_surat||'.'||C.thn_pajak||'.'||C.bln_pajak||'.'||C.kd_obj_pajak||'.'||C.no_urut_surat AS no_surat,A.nm_reklame as nm_usaha,A.kd_kecamatan,A.kd_kelurahan,A.kd_obj_pajak,A.kd_keg_usaha,A.no_reg,A.alamat_reklame as alamat_usaha,B.nama,B.alamat,B.telp,C.pokok_pajak,B.kd_provinsi,B.kd_kota,B.kd_jns,B.no_reg as reg_wp,C.pokok_pajak,C.bln_pajak,C.thn_pajak,C.nm_reklame as nm_obj,C.alamat_reklame as alamat_obj FROM reklame.dat_obj_pajak A 
LEFT JOIN reklame.skp C ON A.kd_kecamatan=C.kd_kecamatan AND A.kd_kelurahan=C.kd_kelurahan AND A.kd_obj_pajak=C.kd_obj_pajak AND A.kd_keg_usaha=C.kd_keg_usaha AND A.no_reg=C.no_reg
INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp
WHERE  C.jns_surat='$kd_jns_surat' AND C.thn_pajak='$thn_pajak' AND C.bln_pajak='$bln_pajak' AND C.kd_obj_pajak='$kd_obj_pajak' AND C.no_urut_surat='$no_urut_surat'") or die('Query failed: ' . pg_last_error());

//
$query = "SELECT MAX(pembayaran_ke) AS no_max FROM $schema.pembayaran WHERE jns_surat='$kd_jns_surat' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat'";
$result = pg_query($query);
$data=pg_fetch_array($result);
//$alamat=$data['alamat'];

if($result) {
	$error = false;
} else {
	$error = true;
	goto error_handler;
}
$data = pg_fetch_array($result);
$pembayaran_ke = $data['no_max']+1;
pg_free_result($result);

$query_insert = "INSERT INTO $schema.pembayaran(jns_surat,thn_pajak,bln_pajak,kd_obj_pajak,no_urut_surat,pembayaran_ke,pokok_pajak,denda,penyimpan,tgl_bayar,no_bukti,pemungut,no_setor,tgl_setor) VALUES('$kd_jns_surat','$thn_pajak','$bln_pajak','$kd_obj_pajak','$no_urut_surat',$pembayaran_ke,$pokok_pajak,$denda,'$nip',TO_DATE('$tgl_bayar','DD-MM-YYYY'),'$no_bukti','$pemungut','$no_setor',TO_DATE('$tgl_setor','DD-MM-YYYY'))";
$result = pg_query($query_insert);
//INSERT KE SIPKD 
/*$tbp = "INSERT INTO TBP (UNITKEY,NOTBP,KEYBEND1,KDSTATUS,KEYBEND2,IDXKODE,TGLTBP,PENYETOR,ALAMAT,URAITBP,TGLVALID) VALUES ('83_','$notbp','229_','63','229_',1,'$tgl','$nama_wp','$alamat_wp','$nama_obj','$tgl')";
$res = mssql_query($tbp);
mssql_free_result($res);
//
$tbpdet = "INSERT INTO TBPDETD (MTGKEY,NOJETRA,UNITKEY,NOTBP,NILAI) VALUES ('$mtgkey','11','83_','$notbp','$pokok_pajak')";
$resdet = mssql_query($tbpdet);
mssql_free_result($resdet);
//
$sts = "INSERT INTO STS (UNITKEY,NOSTS,KEYBEND1,KDSTATUS,IDXKODE,KEYBEND2,IDXTTD,NOBBANTU,TGLSTS,URAIAN,TGLVALID) VALUES ('83_','$nosts','229_','63',1,'229_','','02','$tgl','$nama_obj','$tgl')";
$ressts = mssql_query($sts);
mssql_free_result($ressts);
//
$rkm = "INSERT INTO RKMDETD (MTGKEY,UNITKEY,NOSTS,NOJETRA,NILAI) VALUES ('$mtgkey','83_','$nosts','11','$pokok_pajak')";
$resrkm = mssql_query($rkm);
mssql_free_result($resrkm);
//
$tbsts = "INSERT INTO TBPSTS (UNITKEY,NOTBP,NOSTS) VALUES ('83_','$notbp','$nosts')";
$restbsts = mssql_query($tbsts);
//
mssql_free_result($restbsts); */
if($result) {
	$error = false;
} else {
	$error = true;
	goto error_handler;
}
pg_free_result($result);

$query_update = "UPDATE $schema.$table SET status_pembayaran='1' WHERE jns_surat='$kd_jns_surat' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat'";
if($tabel == '03'){
$query_updates = "UPDATE $schema.skp SET status_pembayaran='1' WHERE jns_surat='$kd_jns_surat' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat'";
$results = pg_query($query_updates);
}
if($tabel == '04'){
$query_updates = "UPDATE $schema.sspd SET status_pembayaran='1' WHERE jns_surat='$kd_jns_surat' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat'";

$results = pg_query($query_updates);
}
if($tabel == '05'){
$query_updates = "UPDATE $schema.skpdkb SET status_pembayaran='1' WHERE jns_surat='$kd_jns_surat' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat'";

$results = pg_query($query_updates);
}
$result = pg_query($query_update);
if($result) {
	$error = false;
} else {
	$error = true;
	goto error_handler;
}
$status = array(
	"status" => "ok",
	"msg" => "Pembayaran No. Surat ".$no_surat." Berhasil Di Simpan",
);

echo json_encode($status);
pg_free_result($result);
pg_close($dbconn);

error_handler :
	if ($error == true) {
		$status = array(
			'status' => 'error',
			'error_msg' => pg_last_error(),
		);
		echo json_encode($status);
		pg_free_result($result);
		pg_close($dbconn);
	}
?>