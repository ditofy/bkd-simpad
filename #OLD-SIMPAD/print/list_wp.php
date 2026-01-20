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
$schema = "public";
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
list($kd_prov,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$arr_data['npwpd']);
$nm_wp = strtoupper($arr_data['nm-wp']);
$query = pg_query("SELECT A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg AS npwpd,A.* FROM $schema.wp A WHERE A.kd_provinsi LIKE '%$kd_prov%' AND A.kd_kota LIKE '%$kd_kota%' AND A.kd_jns LIKE '%$kd_jns%' AND A.no_reg LIKE '%$no_reg_wp%' AND A.nama LIKE '%$nm_wp%' ORDER BY A.kd_provinsi,A.kd_kota,A.kd_jns,A.no_reg") or die('Query failed: ' . pg_last_error());
?>
<style type="text/css">
table, tr, td, th, tbody, thead, tfoot {
    page-break-inside: avoid !important;
}
</style>
<center>DATA WAJIB PAJAK KOTA PAYAKUMBUH</center>
<br />
Filter Data : [<?php echo "NPWPD : ".$arr_data['npwpd']." NM-WP : ".$arr_data['nm-wp']; ?>]
<table border="1" style="font-size:12px;border:1px solid black;border-collapse:collapse;" align="center" cellpadding="2px" width="100%">
                                        <thead>
                                            <tr class="headings">                                         
                                                <th>No </th>
                                                <th>NPWPD </th>
												<th>NIK </th>
                                                <th>Nama WP </th>
												<th>Alamat WP </th>
												<th>No Telp </th>
												<th width="80px">Tgl Terdaftar </th>                                            
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
												<td class=" "><?php echo $row['nama']; ?></td>
												<td class=" "><?php echo $row['alamat']; ?></td>
												<td class=" ">&nbsp;<?php echo $row['telp']; ?></td>
												<td class=" "><?php if (!empty($row['tgl_daftar'])){ echo $row['tgl_daftar']; }?></td>                                            </tr>
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
