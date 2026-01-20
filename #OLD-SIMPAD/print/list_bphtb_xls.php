<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Silahkan Login');
	exit;
}
if (!array_key_exists('data', $_SESSION)) {
   die("Buka Dari Tombol Download"); 
}
$bdir = $_SESSION['base_dir'];
include $_SESSION['base_dir']."inc/db.orcl.inc.php";
include $_SESSION['base_dir']."inc/db.inc.php";

$data_ser = $_SESSION['data'];
unset($_SESSION['data']);
$data = unserialize(urldecode($data_ser));
$schema = "bphtb";
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$nop = strtoupper($arr_data['nop']);
list($kd_prov,$kd_dati2,$kd_kec,$kd_kel,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);

$nopel= strtoupper($arr_data['no-pel']);
list($thn_p,$no_urut_p) = explode(".",$nopel);

list($kd_prop,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$arr_data['npwpd']);
$kd_ppat_a = $arr_data['kd-ppat'];
$kd_transaksi = $arr_data['kd-transaksi'];
$nm_wp = strtoupper($arr_data['nm-wp']);
$kode_kecamatan_a= $arr_data['kd-kecamatan'];
$status_byr = $arr_data['status-byr'];
$tahun_pajak = $arr_data['tahun-pajak'];
$bulan_pajak = $arr_data['bln-pjk'];
if($kode_kecamatan_a != ""){
	list($kode_kecamatan,$nm_kecamatan) = explode(".",$kode_kecamatan_a);
}
$kode_kelurahan_a= pg_escape_string($arr_data['kd-kelurahan']);
if($kode_kelurahan_a != ""){
	list($kode_kelurahan,$nm_kelurahan) = explode(".",$kode_kelurahan_a);
}

if($kd_ppat_a != ""){
	list($kd_ppat,$nm_ppat) = explode(".",$arr_data['kd-ppat']);
}
$query = pg_query("SELECT B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg AS npwpd,A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_sspd,B.nik, A.kd_propinsi||'.'||A.kd_dati2||'.'||A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_blok||'.'||A.no_urut||'.'||A.kd_jns_op AS nop,A.tahun_p||'.'||A.no_urut_p AS no_pelayanan,C.nm_transaksi,D.nama_ppat,E.tgl_bayar,B.telp,A.*,B.* 
FROM $schema.sspd A 
INNER JOIN public.wp B ON B.nik=A.nik
INNER JOIN public.TRANSAKSI C ON A.id_transaksi=C.id 
INNER JOIN public.PPAT D ON A.id_ppat=D.id LEFT JOIN bphtb.pembayaran E ON A.jns_surat=E.jns_surat AND A.thn_pajak=E.thn_pajak AND A.bln_pajak=E.bln_pajak AND A.kd_obj_pajak=E.kd_obj_pajak AND A.no_urut_surat=E.no_urut_surat 
WHERE B.kd_provinsi LIKE '%$kd_prop%' AND B.kd_kota LIKE '%$kd_kota%' AND B.kd_jns like '%$kd_jns%' AND B.no_reg LIKE '%$no_reg_wp%' AND A.kd_propinsi LIKE '%$kd_prov%' AND A.kd_dati2 LIKE '%$kd_dati2%' AND A.kd_kecamatan LIKE '%$kd_kec%' AND A.kd_kelurahan LIKE '%$kd_kel%' AND A.kd_blok LIKE '%$kd_blok%' AND A.no_urut LIKE '%$no_urut%' AND A.kd_jns_op LIKE '%$kd_jns_op%' AND B.nama LIKE '%$nm_wp%' AND A.kd_kecamatan LIKE '%$kode_kecamatan%' AND A.kd_kelurahan LIKE '%$kode_kelurahan%' AND A.id_transaksi LIKE '%$kd_transaksi%' AND CAST(A.id_ppat AS TEXT) like '$kd_ppat%' AND A.tahun_p LIKE '%$thn_p%' AND A.no_urut_p LIKE '%$no_urut_p%' AND A.status_pembayaran LIKE '%$status_byr%'  AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.thn_pajak='$tahun_pajak'") or die('Query failed: ' . pg_last_error());

$xlsfile = "bphtb_".date("d-m-Y").".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$xlsfile");
?>
<style type="text/css">
table, tr, td, th, tbody, thead, tfoot {
    page-break-inside: avoid !important;
}
</style>
<center>DATA OBJEK BPHTB</center>
<br />
Filter Data : [<?php echo "NOP : ".$arr_data['nop']."] [NPWPD : ".$arr_data['npwpd']."] [PPAT : ".$nm_ppat."] [JENIS-TRANSAKSI : ".$nm_kecamatan."] [NM-WP : ".$arr_data['nm-wp']."] [KECAMATAN : ".$nm_kecamatan."] [KELURAHAN : ".$nm_kelurahan; ?>]
<table border="1" style="font-size:12px;border:1px solid black;border-collapse:collapse;" align="center" cellpadding="2px" width="100%">
                                         <thead>
                                            <tr class="headings">                                         
                                                <th>No </th>
                                                <th>NPWPD </th>
                                                <th>NOP </th>
                                                <th>No Pelayanan </th>
												<th>NIK </th>
                                                <th>Nama WP </th>
												<th>Alamat WP </th>
												<th>Kelurahan WP </th>
												<th>Kecamatan WP </th>
												<th>Kota WP </th>
												<th>No Seritikat</th>
												<th>Alamat OP </th>
												<th>Telp </th>
												<th>Jns Transaksi </th>
												<th>Luas Bumi </th>
												<th>Luas Bangunan </th>
												<th>Tgl Validasi</th>
												<th>Harga Transaksi </th>
												<th>NPOPTKP </th>
												<th>Pengurangan</th>
												<th>Jumlah Pengurangan</th>
												<th>Pokok Pajak </th>
												<th>PPAT </th>
												<th>Tanggal Bayar </th>
												<th>Keterangan </th>                                              
                                            </tr>
                                        </thead>
										<tbody>
<?php
$no = 0;
if(pg_num_rows($query) != '0')
{
	while ($row = pg_fetch_array($query))
	{
	$no++;
?>                                        
                                             <tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td>
                                                <td class=" "><?php echo $row['npwpd']; ?></td>
                                                <td class=" "><?php echo $row['nop']; ?></td>
                                                <td class=" ">&nbsp;<?php echo $row['no_pelayanan']; ?></td>
												 <td class=" ">&nbsp;<?php echo $row['nik']; ?></td>
												<td class=" "><?php echo $row['nama']; ?></td>
												<td class=" "><?php echo $row['alamat']; ?></td>
												<td class=" "><?php echo $row['kelurahan']; ?></td>
												<td class=" "><?php echo $row['kecamatan']; ?></td>
												<td class=" "><?php echo $row['kota']; ?></td>
												<td class=" "><?php echo $row['sertifikat']; ?></td>
												<td class=" "><?php echo $row['alamat_op']; ?></td>
												<td class=" ">&nbsp;<?php echo $row['telp']; ?></td>
												<td class=" "><?php echo $row['nm_transaksi']; ?></td>
												<td class=" "><?php echo $row['luas_bumi_trk']; ?></td>
												<td class=" "><?php echo $row['luas_bng_trk']; ?></td>
												<td class=" "><?php echo $row['tgl_validasi']; ?></td>
												<td class=" "><?php echo $row['harga_trk']; ?></td>
												<td class=" "><?php echo $row['npoptkp']; ?></td>
												<td class=" "><?php echo $row['pengurangan']; ?></td>
												<td class=" "><?php echo $row['t_pengurangan']; ?></td>
												<td class=" "><?php echo $row['pokok_pajak_real']; ?></td>
													<td class=" "><?php echo $row['nama_ppat']; ?></td>
													<td class=" "><?php echo $row['tgl_bayar']; ?></td>
												<td class=" "><?php echo $row['ket']; ?></td>                                           
                                            </tr>
<?php
	}
}
?>                                            
                                        </tbody>										
</table>
<?php
pg_free_result($query);
pg_close($dbconn);
//include_once $bdir."print/ttd.php";
?>
