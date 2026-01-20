<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg_wp.inc.php";
//include_once $_SESSION['base_dir']."inc/va.inc.php";
$schema = "bphtb";
$data = $_REQUEST['postdata'];
$arr_data = array();

foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}


$nip = $_SESSION['nip'];

//// INSERT WAJIB PAJAK /////////////

/*$nik=$arr_data['nik'];
$nama_wp=strtoupper($arr_data['nama-wp']);
$alamat_wp=strtoupper($arr_data['alamat']);
$telp=$arr_data['no-telp'];
$kelurahan=strtoupper($arr_data['kelurahan']);
$kecamatan=strtoupper($arr_data['kecamatan']);
$kota=strtoupper($arr_data['kota']);

$cek_nik = "SELECT * FROM public.wp where nik='$nik'";
$cek_nik_result = pg_query($cek_nik) or die ('Query failed: ' . pg_last_error());
$num_cek=pg_num_rows($cek_nik_result);
pg_free_result($cek_nik_result);

$jns_wp = substr($arr_data['jenis-wp'],0,2);
$cek_max = "SELECT TO_NUMBER(MAX(no_reg),'999999') AS no_max FROM public.wp WHERE kd_jns = '03'";
$cek_max_result = pg_query($cek_max) or die('Query failed: ' . pg_last_error());
$data_max = pg_fetch_array($cek_max_result);
$no_reg_wp = no_reg_wp($data_max['no_max']+1);
pg_free_result($cek_max_result);

$tgl_verifikasi=$arr_data['tgl-verifikasi'];
$tgl_selesai=$arr_data['tgl-selesai'];

if ($num_cek == 0 ){
$insert_nik = "INSERT INTO public.wp(kd_provinsi,kd_kota,kd_jns,no_reg,nik,nama,alamat,telp,tgl_daftar,nip_pendaftar,kelurahan,kecamatan,kota)
VALUES('13','76','03','$no_reg_wp','$nik','$nama_wp','$alamat_wp','$telp',TO_DATE('$tgl_verifikasi','DD-MM-YYYY'),'$nip','$kelurahan','$kecamatan','$kota')";
$nik_result = pg_query($insert_nik) or die('Query failed: ' . pg_last_error());
pg_free_result($nik_result);
}
else {
$update_nik ="UPDATE public.wp set nama='$nama_wp',alamat='$alamat_wp',telp='$telp',kelurahan='$kelurahan',kecamatan='$kecamatan',kota='$kota' WHERE nik='$nik'";
$update_nik_result = pg_query($update_nik) or die ('Query failed: ' . pg_last_error());
pg_free_result($update_nik_result);
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

$nj_op_bumi=$jnop_bumi/$luas_bumi;
$nj_op_bng=$njop_bangunan/$luas_bangunan;

$kd_ppat=$arr_data['kd-ppat'];
$kd_transaksi=pg_escape_string($arr_data['kd-transaksi']);
$luas_bumi_transaksi=$arr_data['luas-bumi-transaksi'];
$luas_bng_transaksi=$arr_data['luas-bng-transaksi'];
$nomor_sertifikat=pg_escape_string($arr_data['nomor-sertifikat']);
$akumulasi=$arr_data['akumulasi'];
$harga_trk=$arr_data['harga-trk'];
$pengurangan=$arr_data['pengurangan'];
//$no_pelayanan=pg_escape_string($arr_data['no-pelayanan']);
*/

$nopela = $arr_data['no-pela'];
list($thn,$no_urut_p) = explode(".",$nopela);
$thn_pelayanan=date('Y');
$dasar_pajak=$arr_data['harga-trk-val'];
$harga_trk= str_replace(".", "", $dasar_pajak);
$nip_p=$arr_data['ttd'];

$pengurangan=$arr_data['t-pengurangan'];
$tgl_validasi=$arr_data['tgl-validasi'];
$cek_pelayanan ="SELECT * FROM $schema.pelayanan where no_urut_p ='$no_urut_p' and tahun='$thn' ";
$cek_pelayanan_result = pg_query($cek_pelayanan);
$num_cek_pelayanan =pg_num_rows($cek_pelayanan_result);
$data_p=pg_fetch_array($cek_pelayanan_result);
//pg_free_result($cek_pelayanan_result);

$nik=$data_p['nik'];
$kd_propinsi=$data_p['kd_propinsi'];
$kd_dati2=$data_p['kd_dati2'];
$kd_kecamatan=$data_p['kd_kecamatan'];
$kd_kelurahan=$data_p['kd_kelurahan'];
$kd_blok=$data_p['kd_blok'];
$no_urut=$data_p['no_urut'];
$kd_jns_op=$data_p['kd_jns_op'];
$alamat_op=$data_p['alamat_op'];
$nomor_op=$data_p['nomor_op'];
$rt_op=$data_p['rt_op'];
$rw_op=$data_p['rw_op'];
$njop_bumi=$data_p['njop_bumi'];
$njop_bangunan=$data_p['njop_bng'];
$luas_bumi=$data_p['luas_bumi'];
$luas_bangunan=$data_p['luas_bng'];
$nama_sppt=$data_p['nama_sppt'];
$luas_bumi_trk=$data_p['luas_bumi_trk'];
$luas_bng_trk=$data_p['luas_bng_trk'];
$kel_op=$data_p['kelurahan_op'];
$kec_op=$data_p['kecamatan_op'];
$id_transaksi=$data_p['id_transaksi'];
$akumulasi=$data_p['akumulasi'];
$sertifikat=$data_p['sertifikat'];
$tgl_pelayanan=$data_p['tgl_verifikasi'];
$ket=$data_p['ket'];
$npoptkp=$data_p['npoptkp'];
//$pengurangan=$data_p['t_pengurangan'];
$id_ppat=$data_p['id_ppat'];
$harga_trk_awal=$data_p['harga_trk'];
$kd_sh=$data_p['kd_sertifikat'];

$njop_bumi_awal=$luas_bumi_transaksi*$nj_op_bumi;
$njop_bng_awal=$luas_bng_transaksi*$nj_op_bangunan;
$njop=$njop_bumi_awal+$njop_bng_awal;
///CEK NAMA WP
$cek_nama_wp ="SELECT nama FROM public.wp where nik='$nik'";
$cek_nama_wp_result=pg_query($cek_nama_wp) or die ('Query failed: ' . pg_last_error());
$data_wp = pg_fetch_array($cek_nama_wp_result);
$nama_wp= $data_wp['nama'];
/// CEK NPOPTKP
$cek_npoptkp ="SELECT * FROM bphtb.sspd where nik='$nik' and thn_pajak >='2024' and status_pembayaran < '2' ";
$cek_npoptkp_result=pg_query($cek_npoptkp) or die ('Query failed: ' . pg_last_error());
$sum_npoptkp=pg_num_rows($cek_npoptkp_result);
if($sum_npoptkp > 0){
$npoptkp_n=0;
}
else {
$npoptkp_n=$npoptkp;
}
pg_free_result($cek_npoptkp_result);
//$cek_npoptkp_hasil=pg_fetch_array($cek_npoptkp_result);
//$npoptkp=$cek_npoptkp_hasil['npoptkp'];
//pg_free_result($cek_npoptkp_result);
///menghitung NPOP
if($harga_trk > $njop){
$npop=$harga_trk;
}
else{
$npop=$njop;
}
//menghitung NPOPTKP
if($harga_trk < $npoptkp_n){
$npopkp=0;
}
else{
$npopkp=$npop-$npoptkp_n;
}


$pokok_pajak=(0.05*$npopkp);

if($pengurangan == 0){
$pengurangan_t = $pengurangan*$pokok_pajak;
$pokok_pajak_real_old=$pokok_pajak;
$pokok_pajak_real=round($pokok_pajak_real_old);
}
else if($pengurangan == 0.25)
{
$pengurangan_t = $pengurangan*$pokok_pajak;
$pokok_pajak_t=(0.25*$pokok_pajak);
$pokok_pajak_real_old=$pokok_pajak-$pokok_pajak_t;
$pokok_pajak_real=round($pokok_pajak_real_old);
}
else if($pengurangan == 0.5)
{
$pengurangan_t = $pengurangan*$pokok_pajak;
$pokok_pajak_t=(0.5*$pokok_pajak);
$pokok_pajak_real_old=$pokok_pajak-$pokok_pajak_t;
$pokok_pajak_real=round($pokok_pajak_real_old);

}
else 
{
$pengurangan_t = $pengurangan*$pokok_pajak;
$pokok_pajak_t=(1*$pokok_pajak);
$pokok_pajak_real_old=$pokok_pajak-$pokok_pajak_t;
$pokok_pajak_real=round($pokok_pajak_real_old);

}




$sapi=$_REQUEST['lang'];
$chk="";  
foreach($sapi as $chks)  
   {  
     $chk .= $chks.",";  
  }  
  
  
$tgl_jatuh_tempo = date('Y-m-d', strtotime('+20 days', strtotime($tgl_validasi)));
  //$arr_data_syarat = array();
$pisah= explode("-",$tgl_validasi);
$thn_pisah=$pisah[2];
$bln_pisah=$pisah[1];
$tgl_pisah=$pisah[0];
$cek_no_sspd= "SELECT TO_NUMBER(MAX(no_urut_surat),'999999') AS no_surat_max FROM $schema.sspd WHERE thn_pajak= '$thn_pisah' AND bln_pajak='$bln_pisah'";
$cek_no_sspd_result = pg_query($cek_no_sspd) or die('Query failed: ' . pg_last_error());
$data_sspd_max = pg_fetch_array($cek_no_sspd_result);
$no_urut_sspd = no_reg($data_sspd_max['no_surat_max']+1);
pg_free_result($cek_no_sspd_result);

$qrcode=md5("BKD-PYK.$thn_pisah.".".$no_urut_sspd");
$cek_sspd ="SELECT * FROM $schema.sspd where no_urut_p ='$no_urut_p' and tahun_p='$thn'";
$cek_sspd_result = pg_query($cek_sspd);
$num_cek_sspd =pg_num_rows($cek_sspd_result);
$kd_obj="09";
//$nops = "04.".$thn_pisah.".".$bln_pisah.".".$kd_obj.".".$no_urut_sspd;
//deleteVaPjd($nops);
//$va = getVaPjd($nops, $nama_wp, $pokok_pajak_real);

////
if($_SESSION['pemungut'] == 3){
if($num_cek_sspd > 0 ){
echo "Nomor Pelayanan Ini Sudah Pernah Di Validasi ";
}else{
$insert_sspd = "INSERT INTO $schema.sspd(jns_surat,thn_pajak,bln_pajak,no_urut_surat,no_urut_p,status,status_pembayaran,tgl_pelayanan,nip_pejabat,kd_obj_pajak,tahun_p,qrcode,nik,kd_propinsi,kd_dati2,kd_kecamatan,kd_kelurahan,kd_blok,no_urut,kd_jns_op,alamat_op,nomor_op,rt_op,rw_op,njop_bumi,njop_bng,luas_bumi,luas_bng,nama_sppt,luas_bumi_trk,luas_bng_trk,id_transaksi,akumulasi,sertifikat,npop,npoptkp,npopkp,pokok_pajak,ket,harga_trk,kelurahan_op,kecamatan_op,tgl_validasi,pengurangan,t_pengurangan,pokok_pajak_real,nip_perekam,id_ppat,harga_trk_awal,tgl_jatuh_tempo,kd_sh) VALUES ('04','$thn_pisah','$bln_pisah','$no_urut_sspd','$no_urut_p','0','0','$tgl_pelayanan','$nip_p','09','$thn','$qrcode','$nik','$kd_propinsi','$kd_dati2','$kd_kecamatan','$kd_kelurahan','$kd_blok','$no_urut','$kd_jns_op','$alamat_op','$nomor_op','$rt_op','$rw_op','$njop_bumi','$njop_bangunan','$luas_bumi','$luas_bangunan','$nama_sppt','$luas_bumi_trk','$luas_bng_trk','$id_transaksi','$akumulasi','$sertifikat',$npop,$npoptkp_n,$npopkp,$pokok_pajak,'$ket',$harga_trk,'$kel_op','$kec_op',TO_DATE('$tgl_validasi','DD-MM-YYYY'),$pengurangan,$pengurangan_t,$pokok_pajak_real,'$nip',$id_ppat,$harga_trk_awal,'$tgl_jatuh_tempo','02')";
$insert_sspd_result = pg_query($insert_sspd) or die('Query failed: ' . pg_last_error());
pg_free_result($insert_sspd_result);
if($insert_sspd_result){
$update_status= "UPDATE $schema.pelayanan set status='2' where tahun='$thn' and no_urut_p='$no_urut_p'";
$update_status_result= pg_query($update_status);
$jns_surat="04";
$kd_obj="09";
echo "Objek BPHTB berhasil simpan dengan Nomor SSPD : ".$jns_surat.".".$thn_pisah.".".$bln_pisah.".".$kd_obj.".".$no_urut_sspd;
}
}
}//tutup status pemungut 3

if($_SESSION['pemungut'] == 2){
if($num_cek_sspd > 0 ){
$update_sspd = "UPDATE $schema.sspd SET npop=$npop, npoptkp=$npoptkp_n, npopkp=$npopkp, pokok_pajak=$pokok_pajak, pengurangan=$pengurangan, t_pengurangan=$pengurangan_t, pokok_pajak_real=$pokok_pajak_real, tgl_validasi=TO_DATE('$tgl_validasi','DD-MM-YYYY'), nip_perekam='$nip', nip_pejabat='$nip_p' WHERE no_urut_p ='$no_urut_p' and tahun_p='$thn' ";
$update_sspd_result = pg_query($update_sspd) or die('Query failed: ' . pg_last_error());
pg_free_result($update_sspd_result);
if($update_sspd_result){
$update_status= "UPDATE $schema.pelayanan set status='2' where tahun='$thn' and no_urut_p='$no_urut_p'";
$update_status_result= pg_query($update_status);
echo "Update Data BPHTB Berhasil";
}
}else{
$insert_sspd = "INSERT INTO $schema.sspd(jns_surat,thn_pajak,bln_pajak,no_urut_surat,no_urut_p,status,status_pembayaran,tgl_pelayanan,nip_pejabat,kd_obj_pajak,tahun_p,qrcode,nik,kd_propinsi,kd_dati2,kd_kecamatan,kd_kelurahan,kd_blok,no_urut,kd_jns_op,alamat_op,nomor_op,rt_op,rw_op,njop_bumi,njop_bng,luas_bumi,luas_bng,nama_sppt,luas_bumi_trk,luas_bng_trk,id_transaksi,akumulasi,sertifikat,npop,npoptkp,npopkp,pokok_pajak,ket,harga_trk,kelurahan_op,kecamatan_op,tgl_validasi,pengurangan,t_pengurangan,pokok_pajak_real,nip_perekam,id_ppat,harga_trk_awal,tgl_jatuh_tempo,kd_sh) VALUES ('04','$thn_pisah','$bln_pisah','$no_urut_sspd','$no_urut_p','0','0','$tgl_pelayanan','$nip_p','09','$thn','$qrcode','$nik','$kd_propinsi','$kd_dati2','$kd_kecamatan','$kd_kelurahan','$kd_blok','$no_urut','$kd_jns_op','$alamat_op','$nomor_op','$rt_op','$rw_op','$njop_bumi','$njop_bangunan','$luas_bumi','$luas_bangunan','$nama_sppt','$luas_bumi_trk','$luas_bng_trk','$id_transaksi','$akumulasi','$sertifikat',$npop,$npoptkp_n,$npopkp,$pokok_pajak,'$ket',$harga_trk,'$kel_op','$kec_op',TO_DATE('$tgl_validasi','DD-MM-YYYY'),$pengurangan,$pengurangan_t,$pokok_pajak_real,'$nip',$id_ppat,$harga_trk_awal,'$tgl_jatuh_tempo','02')";
$insert_sspd_result = pg_query($insert_sspd) or die('Query failed: ' . pg_last_error());
pg_free_result($insert_sspd_result);
if($insert_sspd_result){
$update_status= "UPDATE $schema.pelayanan set status='2' where tahun='$thn' and no_urut_p='$no_urut_p'";
$update_status_result= pg_query($update_status);
$jns_surat="04";
$kd_obj="09";
echo "Objek BPHTB berhasil simpan dengan Nomor SSPD : ".$jns_surat.".".$thn_pisah.".".$bln_pisah.".".$kd_obj.".".$no_urut_sspd;
}
}


}//tutup status pemungut 2*/
pg_free_result($cek_nama_wp_result);
pg_close($dbconn);

?>
