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
include_once $bdir."inc/schema.php";
$data_ser = $_SESSION['data'];
unset($_SESSION['data']);
$data = unserialize(urldecode($data_ser));
//if (!isset($_SESSION)) { session_start(); }
//include_once $_SESSION['base_dir']."inc/auth.inc.php";


//$data = unserialize(urldecode($data_ser));
$sub_total = 0;
$grand_total = 0;
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$schema = $list_schema[$arr_data['kd-obj-pajak']];
$kd_obj_pajak = $arr_data['kd-obj-pajak'];
$nop = $arr_data['nop'];
$npwpd = $arr_data['npwpd'];

$no_surat = $arr_data['no-surat'];
$nm_usaha = $arr_data['nm-usaha'];
$nm_wp = strtoupper($arr_data['nm-wp']);
$kd_keg_usaha = $arr_data['keg-usaha'];
$tahun_pajak = $arr_data['tahun-pajak'];
$bulan_pajak = $arr_data['bln-pjk'];
$status_byr = $arr_data['status-byr'];

$num_results = pg_query("SELECT COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total FROM $schema.sptpd A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp 
LEFT JOIN $schema.pembayaran C ON
A.thn_pajak=C.thn_pajak AND A.bln_pajak=C.bln_pajak AND A.kd_obj_pajak=C.kd_obj_pajak AND A.no_urut_surat=C.no_urut_surat
WHERE A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg LIKE '%$nop%' AND A.kd_keg_usaha LIKE '%$kd_keg_usaha%' AND A.nm_usaha LIKE '%$nm_usaha%' AND A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp LIKE '%$npwpd%' AND B.nama LIKE '%$nm_wp%' AND A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat LIKE '%$no_surat%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.thn_pajak='$tahun_pajak' AND A.status_pembayaran LIKE '%$status_byr%' AND A.status_pembayaran != '2'") or die('Query failed: ' . pg_last_error());

$numrows=pg_fetch_array($num_results);
$grand_total = $numrows['g_total'];
pg_free_result($num_results);

$query = pg_query("SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,A.nm_usaha,A.alamat_usaha,B.nama,B.alamat,A.pokok_pajak,to_char(A.tgl_penyampaian, 'DD-MM-YYYY') AS tgl_tetap,(CASE WHEN A.status_pembayaran ='1' THEN 'Sudah Bayar' ELSE 'BelumBayar' END) AS status_bayar,C.tgl_bayar, A.bln_pajak||'-'||A.thn_pajak AS masa,A.dasar_pengenaan_pajak FROM $schema.sptpd A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp
LEFT JOIN $schema.pembayaran C ON
A.thn_pajak=C.thn_pajak AND A.bln_pajak=C.bln_pajak AND A.kd_obj_pajak=C.kd_obj_pajak AND A.no_urut_surat=C.no_urut_surat
WHERE A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg LIKE '%$nop%' AND A.kd_keg_usaha LIKE '%$kd_keg_usaha%' AND A.nm_usaha LIKE '%$nm_usaha%' AND A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp LIKE '%$npwpd%' AND B.nama LIKE '%$nm_wp%' AND A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat LIKE '%$no_surat%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.thn_pajak='$tahun_pajak' AND A.status_pembayaran LIKE '%$status_byr%' AND A.status_pembayaran != '2'") or die('Query failed: ' . pg_last_error());
$xlsfile = "sptpd_".$schema."_".date("d-m-Y").".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$xlsfile");
?>
<style type="text/css">
table, tr, td, th, tbody, thead, tfoot {
    page-break-inside: avoid !important;
}
</style>
<center>DATA SURAT PEMBERITAHUAN TAGIHAN PAJAK DAERAH
<br>PAJAK <?php echo $nm_pajak[$kd_obj_pajak]; ?>
</center>
<br />
Filter Data : [<?php echo "NOP : ".$nop."] [NPWPD : ".$npwpd."] [NM-OBJEK : ".$nm_usaha."] [KEG-USAHA : ".$kd_keg_usaha."] [NM-WP : ".$nm_wp."]  [BULAN : ".strtoupper($bulan_s[$bulan_pajak])."] [TAHUN : ".$tahun_pajak; ?>]
<table border="1" style="font-size:12px;border:1px solid black;border-collapse:collapse;" align="center" cellpadding="2px" width="100%">
                                        <thead>
                                            <tr class="headings">                                         
                                                <th>No </th>
                                                <th>NO SSPD  </th>
												<th>Masa Pajak  </th>
                                                <th>NOP </th>
                                                <th>Nama Objek </th>
												<?php
												if($kd_obj_pajak == '02') {
													echo "<th>Ukuran </th>";
													echo "<th>Tarif </th>";
												}
												?>	
                                                <th>Alamat Objek </th>
                                                <th>Nama WP </th>
												<th>Alamat WP </th>
												<th>Dasar Pengenaan Pajak </th>
												<th>Pokok Pajak </th>												
												<th>Tgl Penetapan </th>	
												<?php
												if($kd_obj_pajak == '02') {
													echo "<th>Tmt Awal </th>";
												}
												?>											
												
												<th>Tanggal Bayar</th>                                             
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
	$sub_total = $sub_total + $row['pokok_pajak'];
	$w=$row['tgl_bayar'];
?>                                        
                                            <tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td>
                                                <td class=" "><?php echo $row['no_surat']; ?></td>
												<td class=" "><?php echo $row['masa']; ?></td>
                                                <td class=" "><?php echo $row['nop']; ?></td>
                                                <td class=" "><?php echo $row['nm_usaha']; ?></td>
												<?php
												if($kd_obj_pajak == '02') {
													echo "<td class=\" \">".$row['panjang']."m x ".$row['lebar']."m x ".$row['sisi']." sisi x ".$row['banyak']." buah"."</td>";
													echo "<td class=\" \">".number_format($row['tarif'])."</td>";
												}
												?>
												<td class=" "><?php echo $row['alamat_usaha']; ?></td>
												<td class=" "><?php echo $row['nama']; ?></td>
												<td class=" "><?php echo $row['alamat']; ?></td>	
												<td class=" "><?php echo $row['dasar_pengenaan_pajak']; ?></td>																							
												<td class=" " align="right"><?php echo $row['pokok_pajak']; ?></td>
												<td class=" "><?php echo $row['tgl_tetap']; ?></td>
												<?php
												if($kd_obj_pajak == '02') {
													echo "<td class=\" \">".$row['tmt_ak']."</td>";
												}
												?>	
												
												<td><?php 
												if ($w){
												echo date('d-M-Y',strtotime($w)); }
												else{
												}  ?></td>                                              
                                            </tr>
<?php
	}
}
?>                                      
										<tr>
											<?php
												if($kd_obj_pajak == '02') {
													echo "<td colspan=\"9\" align=\"center\" style=\"font-size:12px;\">Grand Total</td>";
												} else {
													echo "<td colspan=\"7\" align=\"center\" style=\"font-size:12px;\">Grand Total</td>";
												}
											?>
											<td align="right"><?php echo number_format($grand_total); ?></td>
											<td></td>
											<td></td>
											<?php
												if($kd_obj_pajak == '02') {
													echo "<td class=\" \"></td>";
												}
											?>	
										</tr>        
                                        </tbody>	
																		
</table><br/><br />
<table width="100%"><tr><td width="50%"></td><td align="center"> </td></tr>
<?php 
//$jab=pg_query("select * from public.user where jabatan='KEPALA UPTB PAJAK DAERAH'");
//$u=pg_fetch_array($jab);
?>
<tr><td width="50%"></td><td align="center"><?php //echo  $u['jabatan']; ?></td></tr>
<tr height="45"><td width="50%"></td><td align="center"></td></tr>
<tr><td width="50%"></td><td align="center"><?php // echo $u['nama']; ?></td></tr>
<tr><td width="50%"></td><td align="center"><?php //echo $u['nip']; ?></td></tr>
</table>
<?php
pg_free_result($query);
pg_close($dbconn);
?>