<?php
/*if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}*/
$bdir = $_GET['bdir'];
include_once $bdir."inc/db.inc.php";
$data_ser = $_GET['data'];
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
$query = pg_query("SELECT B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg AS npwpd, A.kd_propinsi||'.'||A.kd_dati2||'.'||A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_blok||'.'||A.no_urut||'.'||A.kd_jns_op AS nop,A.tahun||'.'||A.no_urut_p AS no_pelayanan,C.nm_transaksi,D.nama_ppat,A.*,B.* FROM $schema.pelayanan A INNER JOIN public.wp B ON B.nik=A.nik INNER JOIN public.TRANSAKSI C ON A.id_transaksi=C.id INNER JOIN public.PPAT D ON A.id_ppat=D.id WHERE B.kd_provinsi LIKE '%$kd_prop%' AND B.kd_kota LIKE '%$kd_kota%' AND B.kd_jns like '%$kd_jns%' AND B.no_reg LIKE '%$no_reg_wp%' AND A.kd_propinsi LIKE '%$kd_prov%' AND A.kd_dati2 LIKE '%$kd_dati2%' AND A.kd_kecamatan LIKE '%$kd_kec%' AND A.kd_kelurahan LIKE '%$kd_kel%' AND A.kd_blok LIKE '%$kd_blok%' AND A.no_urut LIKE '%$no_urut%' AND A.kd_jns_op LIKE '%$kd_jns_op%' AND B.nama LIKE '%$nm_wp%' AND A.id_ppat = '$kd_ppat' AND A.kd_kecamatan LIKE '%$kode_kecamatan%' AND A.kd_kelurahan LIKE '%$kode_kelurahan%' AND A.id_transaksi LIKE '%$kd_transaksi%' AND A.status_pembayaran LIKE '%$status_byr%' AND A.no_urut_p LIKE '%$no_urut_p%'") or die('Query failed: ' . pg_last_error());
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
                                                <th>Nama WP </th>
												<th>Alamat OP </th>
												<th>Jns Transaksi </th>
												<th>Tgl Terdaftar </th>
												<th>Harga Transaksi </th>
												<th>Pokok Pajak </th>
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
                                                <td class=" "><?php echo $row['no_pelayanan']; ?></td>
												<td class=" "><?php echo $row['nama']; ?></td>
												<td class=" "><?php echo $row['alamat_op']; ?></td>
												<td class=" "><?php echo $row['nm_transaksi']; ?></td>
												<td class=" "><?php echo $row['tgl_verifikasi']; ?></td>
												<td class=" "><?php echo number_format($row['harga_trk']); ?></td>
												<td class=" "><?php echo number_format($row['pokok_pajak_real']); ?></td>
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
