<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg_wp.inc.php";
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

$cek_nik = "SELECT * FROM public.wp where nik='$nik'";
$cek_nik_result = pg_query($cek_nik) or die ('Query failed: ' . pg_last_error());
$num_cek=pg_num_rows($cek_nik_result);
pg_free_result($cek_nik_result);

$jns_wp = substr($arr_data['jenis-wp'],0,2);
$cek_max = "SELECT TO_NUMBER(MAX(no_reg),'999999') AS no_max FROM public.wp WHERE kd_jns = '$jns_wp'";
$cek_max_result = pg_query($cek_max) or die('Query failed: ' . pg_last_error());
$data_max = pg_fetch_array($cek_max_result);
$no_reg_wp = no_reg_wp($data_max['no_max']+1);
pg_free_result($cek_max_result);

$tgl_verifikasi=pg_escape_string($arr_data['tgl-verifikasi']);

if ($num_cek == 0 ){
$insert_nik = "INSERT INTO public.wp(kd_provinsi,kd_kota,kd_jns,no_reg,nik,nama,alamat,telp,tgl_daftar,nip_pendaftar,kelurahan,kecamatan,kota)
VALUES('13','76','$jns_wp','$no_reg_wp','$nik','$nama_wp','$alamat_wp','$telp',TO_DATE('$tgl_verifikasi','DD-MM-YYYY'),'$nip','$kelurahan','$kecamatan','$kota')";
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
$no_p= strtoupper($arr_data['no-pelayanan']);
list($tahun,$no_urut_p) = explode(".",$no_p);
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

$kd_ppat=pg_escape_string($arr_data['kd-ppat']);
$kd_transaksi=pg_escape_string($arr_data['kd-transaksi']);
$luas_bumi_transaksi=$arr_data['luas-bumi-transaksi'];
$luas_bng_transaksi=$arr_data['luas-bng-transaksi'];
$nomor_sertifikat=pg_escape_string($arr_data['nomor-sertifikat']);
$akumulasi=$arr_data['akumulasi'];
$harga_trk_new=$arr_data['harga-trk'];
//$harga_trk_new=str_replace(".", "", $harga_trk);
$harga_trk= str_replace(",", "", $harga_trk_new);
$pengurangan=$arr_data['pengurangan'];
$status=pg_escape_string($arr_data['status']);
$kd_sertifikat=pg_escape_string($arr_data['kd-sh']);

$njop_bumi_awal=$luas_bumi_transaksi*$njop_bumi;
$njop_bng_awal=$luas_bng_transaksi*$njop_bangunan;
$njop=$njop_bumi_awal+$njop_bng_awal;

if($harga_trk > $njop){
$npop=$harga_trk;
}
else{
$npop=$njop;
}

$cek_npoptkp ="SELECT * FROM public.transaksi where id = '$kd_transaksi' order by id asc";
$cek_npoptkp_result=pg_query($cek_npoptkp) or die ('Query failed: ' . pg_last_error());
$cek_npoptkp_hasil=pg_fetch_array($cek_npoptkp_result);
$npoptkp=$cek_npoptkp_hasil['npoptkp'];
pg_free_result($cek_npoptkp_result);

$npopkp=$npop-$npoptkp;
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

$update_pelayanan = "UPDATE $schema.pelayanan SET nik='$nik',kd_propinsi='$kd_prov',kd_dati2='$kd_dati2',kd_kecamatan='$kd_kec',kd_kelurahan='$kd_kel',kd_blok='$kd_blok',no_urut='$no_urut',kd_jns_op='$kd_jns_op',alamat_op='$letak_op',nomor_op='$nomor_op',rt_op='$rt_op',rw_op='$rw_op',njop_bumi=$njop_bumi,njop_bng=$njop_bangunan,luas_bumi=$luas_bumi,luas_bng=$luas_bangunan,nama_sppt='$nama_sppt',luas_bumi_trk=$luas_bumi_transaksi,luas_bng_trk=$luas_bng_transaksi,id_transaksi='$kd_transaksi',id_ppat='$kd_ppat',akumulasi=$akumulasi,sertifikat='$nomor_sertifikat',npop=$npop,npoptkp=$npoptkp,npopkp=$npopkp,pokok_pajak=$pokok_pajak,harga_trk=$harga_trk,kelurahan_op='$nama_kel',kecamatan_op='$nama_kec',tgl_verifikasi=TO_DATE('$tgl_verifikasi','DD-MM-YYYY'),status='$status',t_pengurangan=$pengurangan,pengurangan=$pengurangan_t,pokok_pajak_real=$pokok_pajak_real,kd_sertifikat='$kd_sertifikat' WHERE no_urut_p ='$no_urut_p' and tahun='$tahun'";
$update_pelayanan_result = pg_query($update_pelayanan) or die('Query failed: ' . pg_last_error());
pg_free_result($update_pelayanan_result);
if($update_pelayanan_result){
/*$cek_no_surat = "SELECT TO_NUMBER(MAX(no_urut_surat),'999999') AS no_surat_max FROM $schema.sspd WHERE thn_pajak = '$thn_pelayanan' and bln_pajak=''";
$cek_no_surat_result = pg_query($cek_no_surat) or die('Query failed: ' . pg_last_error());
$data_surat_max = pg_fetch_array($cek_no_surat_result);
$no_urut_surat = no_reg($data_surat_max['no_surat_max']+1);
pg_free_result($cek_no_surat_result);*/ 
/*$tanggal=date($tgl_verifikasi,'DD-MM-YYYY');
//$bln_pajak=date('m',$tanggal);
$update_sspd = "UPDATE $schema.sspd SET tahun_p = '$thn_pelayanan',no_urut_p = '$no_pelayanan',status,status_bayar,tgl_verifikasi) VALUES ('04','$thn_pelayanan','$bln_pajak',,'$thn_pelayanan','$no_pelayanan','1','0',TO_DATE('$tgl_verifikasi','DD-MM-YYYY'))";
$insert_sspd_result = pg_query($insert_sspd) or die('Query failed: ' . pg_last_error());
pg_free_result($insert_sspd_result);*/
echo "Objek BPHTB berhasil Di Update dengan Nomor Pelayanan : ".$tahun.".".$no_urut_p;

}

pg_close($dbconn);

?>
