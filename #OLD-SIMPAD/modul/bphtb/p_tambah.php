<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg_wp.inc.php";
include_once $_SESSION['base_dir']."inc/db.orcl.inc.php";
$schema = "bphtb";
$data = $_REQUEST['postdata'];
$arr_data = array();

foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}


$nip = $_SESSION['nip'];

//// INSERT WAJIB PAJAK /////////////

$nik=$arr_data['nik'];
$nama_wp=strtoupper($arr_data['nama-wp']);
$alamat_wp=strtoupper($arr_data['alamat']);
$telp=$arr_data['no-telp'];
$kelurahan=strtoupper($arr_data['kelurahan']);
$kecamatan=strtoupper($arr_data['kecamatan']);
$kota=strtoupper($arr_data['kota']);
$npwp=strtoupper($arr_data['npwp']);

$query = "SELECT TO_NUMBER(MAX(no_reg),'999999') AS no_max FROM public.wp WHERE kd_jns = '03'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$data = pg_fetch_array($result);
$no_reg_wp = no_reg_wp($data['no_max']+1);

$cek_nik = "SELECT * FROM public.wp where nik='$nik'";
$cek_nik_result = pg_query($cek_nik) or die ('Query failed: ' . pg_last_error());
$num_cek=pg_num_rows($cek_nik_result);


if ($num_cek == 0 ){
$insert_nik = "INSERT INTO public.wp(kd_provinsi,kd_kota,kd_jns,no_reg,nik,nama,alamat,telp,tgl_daftar,nip_pendaftar,kelurahan,kecamatan,kota,npwp,status)
VALUES('13','76','03','$no_reg_wp','$nik','$nama_wp','$alamat_wp','$telp',TO_DATE('$tgl_verifikasi','DD-MM-YYYY'),'$nip','$kelurahan','$kecamatan','$kota','$npwp','1')";
$nik_result = pg_query($insert_nik) or die('Query failed: ' . pg_last_error());
pg_free_result($nik_result);
}
else {
$update_nik ="UPDATE public.wp set nama='$nama_wp',alamat='$alamat_wp',telp='$telp',kelurahan='$kelurahan',kecamatan='$kecamatan',kota='$kota',npwp='$npwp' WHERE nik='$nik'";
$update_nik_result = pg_query($update_nik) or die ('Query failed: ' . pg_last_error());
//pg_free_result($update_nik_result);
}

//// INSERT OBJEK PAJAK /////////

$nop = strtoupper($arr_data['nop']);
list($kd_prov,$kd_dati2,$kd_kec,$kd_kel,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);
$nama_sppt=pg_escape_string($arr_data['nama-sppt']);
$letak_op=pg_escape_string($arr_data['letak-op']);
$nomor_op=pg_escape_string($arr_data['nomor-op']);
$rt_op=pg_escape_string($arr_data['rt-op']);
$rw_op=pg_escape_string($arr_data['rw-op']);
$nama_kel=pg_escape_string($arr_data['nama-kel']);
$nama_kec=pg_escape_string($arr_data['nama-kec']);
$njop_bumi=$arr_data['njop-bumi'];
$njop_bangunan=$arr_data['njop-bangunan'];
$luas_bumi=$arr_data['luas-bumi'];
$luas_bangunan=$arr_data['luas-bangunan'];
$ket=pg_escape_string($arr_data['ket']);

$kd_ppat=pg_escape_string($arr_data['kd-ppat']);
$kd_transaksi=pg_escape_string($arr_data['kd-transaksi']);
$luas_bumi_transaksi=$arr_data['luas-bumi-transaksi'];
$luas_bng_transaksi=$arr_data['luas-bng-transaksi'];
$nomor_sertifikat=pg_escape_string($arr_data['nomor-sertifikat']);
$akumulasi=$arr_data['akumulasi'];
$harga_trk_new=$arr_data['harga-trk'];
//$harga_trk_new=str_replace(".", "", $harga_trk);
$harga_trk= str_replace(".", "", $harga_trk_new);
$pengurangan=$arr_data['pengurangan'];
$status=pg_escape_string($arr_data['status']);
$kd_sertifikat=pg_escape_string($arr_data['kd-sh']);
$id_pln=pg_escape_string($arr_data['id-pln']);
$tgl_verifikasi=pg_escape_string($arr_data['tgl-verifikasi']);
$tgl_selesai=pg_escape_string($arr_data['tgl-selesai']);

$thn_pelayanan=date('Y');
$cek_pelayanan ="SELECT * FROM $schema.pelayanan where no_urut_p ='$no_pelayanan' and tahun='$thn_pelayanan'";
$cek_pelayanan_result = pg_query($cek_pelayanan);
$num_cek_pelayanan =pg_num_rows($cek_pelayanan_result);
//pg_free_result($cek_pelayanan_result);

$njop_bumi_awal=$luas_bumi_transaksi*$nj_op_bumi;
$njop_bng_awal=$luas_bng_transaksi*$nj_op_bangunan;
$njop=$njop_bumi_awal+$njop_bng_awal;


$cek_npoptkp ="SELECT * FROM public.transaksi where id = '$kd_transaksi' order by id asc";
$cek_npoptkp_result=pg_query($cek_npoptkp) or die ('Query failed: ' . pg_last_error());
$cek_npoptkp_hasil=pg_fetch_array($cek_npoptkp_result);
$npoptkp=$cek_npoptkp_hasil['npoptkp'];
//pg_free_result($cek_npoptkp_result);
///menghitung NPOP
if($harga_trk > $njop){
$npop=$harga_trk;
}
else{
$npop=$njop;
}
//menghitung NPOPTKP
if($harga_trk < $npoptkp){
$npopkp=0;
}
else{
$npopkp=$npop-$npoptkp;
}


$pokok_pajak_real=(0.05*$npopkp);
if($pengurangan == 0){
$pengurangan_t = $pengurangan*$pokok_pajak;
$pokok_pajak=$pokok_pajak_real;
}
else if($pengurangan == 0.25)
{
$pengurangan_t = $pengurangan*$pokok_pajak_real;
$pokok_pajak=(0.25*$pokok_pajak_real);
}
else
{
$pengurangan_t = $pengurangan*$pokok_pajak_real;
$pokok_pajak=(0.5*$pokok_pajak_real);
}



$sapi=$_REQUEST['lang'];
$chk="";  
foreach($sapi as $chks)  
   {  
     $chk .= $chks.",";  
  }  
  
  //$arr_data_syarat = array();

$cek_no_pelayanan = "SELECT TO_NUMBER(MAX(no_urut_p),'999999') AS no_pelayanan_max FROM $schema.pelayanan WHERE tahun= '$thn_pelayanan'";
$cek_no_pelayanan_result = pg_query($cek_no_pelayanan) or die('Query failed: ' . pg_last_error());
$data_pelayanan_max = pg_fetch_array($cek_no_pelayanan_result);
$no_urut_pelayanan = no_reg($data_pelayanan_max['no_pelayanan_max']+1);
//pg_free_result($cek_no_pelayanan_result);

$qrcode=md5("BKD-PYK.$thn_pelayanan.".".$no_urut_pelayanan");
/////Query Cek Id PLN
$cek_pln = "SELECT * FROM $schema.pelayanan WHERE id_pln = '$id_pln' AND tahun='2025'";
$cek_pln_result = pg_query($cek_pln) or die('Query failed: ' . pg_last_error());
$num_pln=pg_num_rows($cek_pln_result);
//if($num_cek_pelayanan == 0 ){
if($luas_bng_transaksi > 0  and $num_pln > 0 ) {
echo "Objek Bangunan Ada Tapi ID PLN Tidak Diisi Atau Memasukkan Nomor Id PLN Yang Sudah Ada $id_pln ....!!";
}
else {
$insert_pelayanan = "INSERT INTO $schema.pelayanan(tahun,no_urut_p,nik,kd_propinsi,kd_dati2,kd_kecamatan,kd_kelurahan,kd_blok,no_urut,kd_jns_op,alamat_op,nomor_op,rt_op,rw_op,njop_bumi,njop_bng,luas_bumi,luas_bng,nama_sppt,luas_bumi_trk,luas_bng_trk,id_transaksi,akumulasi,sertifikat,npop,npoptkp,npopkp,pokok_pajak,status,harga_trk,kelurahan_op,kecamatan_op,tgl_verifikasi,kd_obj_pajak,pengurangan,t_pengurangan,pokok_pajak_real,syarat,nip_p,tgl_selesai,ket,id_ppat,qrcode_p,kd_sertifikat,id_pln) VALUES ('$thn_pelayanan','$no_urut_pelayanan','$nik','$kd_prov','$kd_dati2','$kd_kec','$kd_kel','$kd_blok','$no_urut','$kd_jns_op','$letak_op','$nomor_op','$rt_op','$rw_op',$njop_bumi,$njop_bangunan,$luas_bumi,$luas_bangunan,'$nama_sppt',$luas_bumi_transaksi,$luas_bng_transaksi,'$kd_transaksi',$akumulasi,'$nomor_sertifikat',$npop,$npoptkp,$npopkp,$pokok_pajak,'1',$harga_trk,'$nama_kel','$nama_kec',TO_DATE('$tgl_verifikasi','DD-MM-YYYY'),'09',$pengurangan_t,$pengurangan,$pokok_pajak_real,'$chk','$nip',TO_DATE('$tgl_selesai','DD-MM-YYYY'),'$ket',$kd_ppat,'$qrcode','$kd_sh','$id_pln')";
$insert_pelayanan_result = pg_query($insert_pelayanan) or die('Query failed: ' . pg_last_error());
}
//pg_free_result($insert_pelayanan_result);
if($insert_pelayanan_result){
//////INSERT PLN
/////// INSERT DATA PLN /////////////////////////
$pln = oci_parse($conn,"select * FROM PBB.REF_PLN WHERE 
KD_KECAMATAN = '$kd_kec' AND
KD_KELURAHAN = '$kd_kel' AND
KD_BLOK = '$kd_blok' AND
NO_URUT='$no_urut' AND
KD_JNS_OP='$kd_jns_op'");
oci_execute($pln);
$hasil_pln = oci_fetch_array($pln, OCI_ASSOC);
$jum=oci_num_rows($pln);

if($jum == 0) {
$queryInsert = "INSERT INTO PBB.REF_PLN
(
    NO_PEL_PLN,
	KD_PROPINSI,
    KD_DATI2,
	KD_KECAMATAN,
	KD_KELURAHAN,
	KD_BLOK,
	NO_URUT,
	KD_JNS_OP
) 
VALUES
(
    '$id_pln',
	'13', 
    '76',
	'$kd_kec',
	'$kd_kel',
	'$kd_blok',
	'$no_urut',
	'$kd_jns_op' 
)";
$stidInsert = oci_parse($conn, $queryInsert);	
$u=oci_execute($stidInsert);	
	if ($u)
	{
	echo "sukses";
	}
	/*echo "SKP BELUM ADA UNTUK TAHUN ".$thn." BULAN ".$bln;
	pg_free_result($tampil);
	pg_close($dbconn);
	exit;*/
}
//$pisah= explode("-",$tgl_verifikasi);
//$thn=$pisah[2];
//$bln=$pisah[1];
//$tgl=$pisah[0];
//$insert_sspd = "INSERT INTO $schema.sspd(jns_surat,thn_pajak,bln_pajak,no_urut_surat,tahun_p,no_urut_p,status,status_pembayaran,tgl_verifikasi,kd_obj_pajak,qrcode) VALUES ('04','$thn_pelayanan','$bln','$no_urut_pelayanan','$thn_pelayanan','$no_urut_pelayanan','1','0',TO_DATE('$tgl_verifikasi','DD-MM-YYYY'),'09','$qrcode')";
//$insert_sspd_result = pg_query($insert_sspd) or die('Query failed: ' . pg_last_error());
//pg_free_result($insert_sspd_result);
echo "Objek BPHTB berhasil simpan dengan Nomor Pelayanan : ".$thn_pelayanan.".".$no_urut_pelayanan;
}
//}

pg_close($dbconn);

?>
