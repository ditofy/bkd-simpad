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
include_once $bdir."inc/db.inc.php";
$data_ser = $_SESSION['data'];
$data = unserialize(urldecode($data_ser));
$schema = "hotel";
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$arr_data['nop']);
list($kd_prov,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$arr_data['npwpd']);
$nm_usaha = $arr_data['nm-usaha'];
$nm_wp = strtoupper($arr_data['nm-wp']);
if($kd_keg_usaha == ""){
	list($kd_keg_usaha,$nm_keg_usaha) = explode(".",$arr_data['keg-usaha']);
}
if($kd_kecamatan == ""){
	list($kd_kecamatan,$nm_kecamatan) = explode(".",$arr_data['kd-kecamatan']);
}
if($kd_kelurahan == ""){
	$kd_kelurahan = substr($arr_data['kd-kelurahan'],0,2);
	$nm_kel = substr($arr_data['kd-kelurahan'],5,strlen($arr_data['kd-kelurahan']));
}
$query = pg_query("SELECT A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd, A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.jenis_penetapan,CASE WHEN jenis_penetapan='01' THEN 'SKP' WHEN jenis_penetapan='02' THEN 'SELF ASSESSMENT' ELSE '' END AS jns_p,A.*,B.* FROM $schema.dat_obj_pajak A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp WHERE A.kd_kecamatan LIKE '%$kd_kecamatan%' AND A.kd_kelurahan LIKE '%$kd_kelurahan%' AND A.kd_obj_pajak LIKE '%$kd_obj_pajak%' AND A.kd_keg_usaha LIKE '%$kd_keg_usaha%' AND A.no_reg LIKE '%$no_reg%' AND A.nm_usaha LIKE '%$nm_usaha%' AND A.kd_provinsi LIKE '%$kd_prov%' AND A.kd_kota LIKE '%$kd_kota%' AND A.kd_jns LIKE '%$kd_jns%' AND A.no_reg_wp LIKE '%$no_reg_wp%' AND B.nama LIKE '%$nm_wp%' AND A.status_pajak='1' ORDER BY A.id") or die('Query failed: ' . pg_last_error());
$xlsfile = "hotel_".date("d-m-Y").".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$xlsfile");
?>
<style type="text/css">
table, tr, td, th, tbody, thead, tfoot {
    page-break-inside: avoid !important;
}
</style>
<center>DATA OBJEK PAJAK HOTEL</center>
<br />
Filter Data : [<?php echo "NOP : ".$arr_data['nop']."] [NPWPD : ".$arr_data['npwpd']."] [NM-USAHA : ".$arr_data['nm-usaha']."] [KEG-USAHA : ".$nm_keg_usaha."] [NM-WP : ".$arr_data['nm-wp']."] [KECAMATAN : ".$nm_kecamatan."] [KELURAHAN : ".$nm_kel; ?>]
<table border="1" style="font-size:12px;border:1px solid black;border-collapse:collapse;" align="center" cellpadding="2px" width="100%">
                                        <thead>
                                            <tr class="headings">                                         
                                                <th>No </th>
                                                <th>NPWPD </th>
												<th>NIK </th>
                                                <th>NOP </th>
                                                <th>Nama Usaha </th>
                                                <th>Alamat Usaha </th>
                                                <th>Nama WP </th>
												<th>Alamat WP </th>
												<th>No Telp </th>
												<th>Jns Penetapan </th>
												<th>Tgl Terdaftar </th>
												<th>Ketetapan </th>
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
												<td class=" ">&nbsp;<?php echo $row['nik']; ?></td>
                                                <td class=" "><?php echo $row['nop']; ?></td>
                                                <td class=" "><?php echo $row['nm_usaha']; ?></td>
												<td class=" "><?php echo $row['alamat_usaha']; ?></td>
												<td class=" "><?php echo $row['nama']; ?></td>
												<td class=" "><?php echo $row['alamat']; ?></td>
												<td class=" ">&nbsp;<?php echo $row['telp']; ?></td>
												<td class=" "><?php if (!empty($row['jns_p'])) {echo $row['jns_p'];} ?></td>
												<td class=" "><?php echo $row['tgl_daftar']; ?></td>
												<td class=" "><?php echo number_format($row['ketetapan']); ?></td>
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
