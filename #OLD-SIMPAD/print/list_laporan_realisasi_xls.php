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
include $bdir."inc/db.inc.php";
//include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $bdir."inc/schema.php";
$data_ser = $_SESSION['data'];
unset($_SESSION['data']);
$data = unserialize(urldecode($data_ser));
$sub_total = 0;
$grand_total = 0;
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$tgl_awal = $arr_data['tgl-awal'];
$tgl_akhir = $arr_data['tgl-akhir'];
$jns_objek = $arr_data['jenis-objek'];
$jns_laporan = $arr_data['jenis-laporan'];
$total = 0;
$query_obj= pg_query("select nm_obj_pajak from public.obj_pajak where kd_obj_pajak='$jns_objek'")or die('Query failed: ' . pg_last_error());
$row_obj=pg_fetch_array($query_obj);
if ($jns_laporan == '02'){
switch ($jns_objek) {
       case "01":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank from air_tanah.pembayaran A INNER JOIN 
			air_tanah.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "02":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_reklame as nm_usaha,b.alamat_reklame as alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank from reklame.pembayaran A INNER JOIN 
			reklame.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC") or die('Query failed: ' . pg_last_error());
       break;
	  case "03":
$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,D.nama,E.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank,B.dasar_pengenaan_pajak from restoran.pembayaran A 
LEFT JOIN restoran.skpdkb C ON C.jns_surat=A.jns_surat and A.thn_pajak=C.thn_pajak and C.bln_pajak=A.bln_pajak and C.kd_obj_pajak=A.kd_obj_pajak and C.no_urut_surat=A.no_urut_surat
		LEFT JOIN restoran.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp D on D.kd_provinsi=b.kd_provinsi and D.kd_kota=b.kd_kota and D.kd_jns=b.kd_jns and D.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha E on E.kd_obj_pajak=b.kd_obj_pajak and E.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar") or die('Query failed: ' . pg_last_error());

       break;
	   case "04":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank,B.dasar_pengenaan_pajak from ppj.pembayaran A INNER JOIN 
			ppj.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "05":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,D.kd_provinsi||'.'||D.kd_kota||'.'||D.kd_jns||'.'||D.no_reg as npwpd,D.nama,E.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank,B.dasar_pengenaan_pajak from parkir.pembayaran A 
			LEFT JOIN parkir.skpdkb C ON C.jns_surat=A.jns_surat and A.thn_pajak=C.thn_pajak and C.bln_pajak=A.bln_pajak and C.kd_obj_pajak=A.kd_obj_pajak and C.no_urut_surat=A.no_urut_surat
			LEFT JOIN parkir.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp D on D.kd_provinsi=b.kd_provinsi and D.kd_kota=b.kd_kota and D.kd_jns=b.kd_jns and D.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha E on E.kd_obj_pajak=b.kd_obj_pajak and E.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC ") or die('Query failed: ' . pg_last_error());
       break;
	   case "06":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank,B.dasar_pengenaan_pajak from hiburan.pembayaran A INNER JOIN 
			hiburan.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "07":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank,B.dasar_pengenaan_pajak from hotel.pembayaran A INNER JOIN 
			hotel.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "08":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.tgl_bayar,A.tgl_bayar,A.no_bukti,A.nm_cab_bank from galianc.pembayaran A INNER JOIN 
			galianc.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	 case "09":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg as npwpd,C.nama,C.alamat,B.npop,B.luas_bumi_trk,B.luas_bng_trk,D.nm_keg_usaha,b.nama_sppt as nm_usaha,b.alamat_op as alamat_usaha,A.pokok_pajak,A.tgl_bayar,B.kd_propinsi||'.'||B.kd_dati2||'.'||B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_blok||'.'||B.no_urut||'.'||B.kd_jns_op as nop,F.nama_ppat,A.tgl_bayar,A.no_bukti,A.nm_cab_bank,C.nik,B.kelurahan_op, B.kecamatan_op from bphtb.pembayaran A  
LEFT JOIN bphtb.skpdkb E on A.jns_surat=E.jns_surat and A.thn_pajak=E.thn_pajak and A.bln_pajak=E.bln_pajak and A.kd_obj_pajak=E.kd_obj_pajak and A.no_urut_surat=E.no_urut_surat 
LEFT JOIN bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=B.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
LEFT JOIN public.wp C on c.nik=b.nik 
LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_sh
LEFT JOIN public.ppat F on F.id=b.id_ppat
WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY A.tgl_bayar ASC") or die('Query failed: ' . pg_last_error());
       break;
	   default:
	   $query = pg_query("select c.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from bphtb.pembayaran A INNER JOIN 
			bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.keg_usaha C on C.kd_obj_pajak=b.kd_obj_pajak and C.kd_keg_usaha=b.kd_sh
			WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' group by c.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
	   break;
	
}
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data Pajak.xls");
?>

<table border="1" style="font-size:12px;border:1px solid black;border-collapse:collapse;" align="center" cellpadding="2px" width="100%">
<tr class="headings">
	 <td colspan="10" align="center"><h3>DATA PEMBAYARAN <?php echo $row_obj['nm_obj_pajak']; ?> TANGGAL: <?php echo $tgl_awal; ?>&nbsp;s/d&nbsp;<?php echo $tgl_akhir; ?></h3></td>
</tr>                                       
											
                                            <tr class="headings">  
											    <th>NO </th>                                       
                                                <th>NO BAYAR </th>                                       
                                                <th>NOP </th>
												<th>NPWPD </th>
												<th>NIK </th>
												<th>NAMA WAJIB PAJAK</th>
												<th>ALAMAT WAJIB PAJAK</th>
												<th>JENIS USAHA</th> 
												<th>NAMA USAHA/OBJEK</th>
												<th>ALAMAT USAHA/OBJEK</th> 
												<?php
												if($jns_objek == '09') {
													echo "<th>KELURAHAN OBJEK</th>";
													
												}
												?>	 
												<?php
												if($jns_objek == '09') {
													echo "<th>KECAMATAN OBJEK</th>";
													
												}
												?>	 
												<th>DASAR PENGENAAN PAJAK</th>   
												<th>JUMLAH PAJAK</th> 
												<?php
												if($jns_objek == '09') {
													echo "<th>NPOP</th>";
													
												}
												?>	
												<?php
												if($jns_objek == '09') {
													echo "<th>LUAS BUMI</th>";
													
												}
												?>	
												<?php
												if($jns_objek == '09') {
													echo "<th>LUAS BANGUNAN</th>";
													
												}
												?>
												<?php
												if($jns_objek == '09') {
													echo "<th>PPAT</th>";
													
												}
												?>
												<th>NOMOR BUKTI</th> 
												<th>CABANG</th> 
												<th>TANGGAL BAYAR</th>                                 	
                                            </tr>
                                       
										<tbody>
<?php
$no = 0;
while ($row = pg_fetch_array($query))
	{
	$no++;
	$sub_total = $sub_total + $row['pokok_pajak'];
	$total=$total+$row['pokok_pajak'];
?>
<tr class="even pointer">
                                                 <td class=" "><?php echo $no; ?></td> 
												<td class=" "><?php echo $row['no_surat']; ?></td> 
												<td class=" "><?php echo $row['nop']; ?></td>  
												<td class=" "><?php echo $row['npwpd']; ?></td> 
												<td class=" "><?php echo $row['nik']; ?></td> 
												<td class=" "><?php echo $row['nama']; ?></td> 
												<td class=" "><?php echo $row['alamat']; ?></td> 
												<td class=" "><?php echo $row['nm_keg_usaha']; ?></td> 
												<td class=" "><?php echo $row['nm_usaha']; ?></td> 
												<td class=" "><?php echo $row['alamat_usaha']; ?></td> 
												<?php
												if($jns_objek == '09') {
													echo "<td class=\" \">".$row['kelurahan_op']."</td>";
												}
												?>	
												<?php
												if($jns_objek == '09') {
													echo "<td class=\" \">".$row['kecamatan_op']."</td>";
												}
												?>	
												<td class=" "><?php echo $row['dasar_pengenaan_pajak']; ?></td>                                            
                                                <td class=" "><?php echo $row['pokok_pajak']; ?></td> 
												<?php
												if($jns_objek == '09') {
													echo "<td class=\" \">".$row['npop']."</td>";
												}
												?>	
												 <?php
												if($jns_objek == '09') {
													echo "<td class=\" \">".$row['luas_bumi_trk']."</td>";
												}
												?>
												<?php
												if($jns_objek == '09') {
													echo "<td class=\" \">".$row['luas_bng_trk']."</td>";
												}
												?>
													<?php
												if($jns_objek == '09') {
													echo "<td class=\" \">".$row['nama_ppat']."</td>";
												}
												?>
												<td class=" "><?php echo $row['no_bukti']; ?></td> 
												<td class=" "><?php echo $row['nm_cab_bank']; ?></td>
												<td class=" "><?php echo $row['tgl_bayar']; ?></td>                                                
                                               
                                            </tr>
<?php
}
//oci_free_statement($stid);
//oci_close($conn);
?>
<tr class="even pointer">
<td class=" " colspan="8"><strong>TOTAL</strong></td>
<td class=" " align="center" ><strong><?php echo number_format($total); ?></strong></td><TD></TD>
</tr>
</tbody>

        
 <?php } ?>
							