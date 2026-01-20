
<style type="text/css">
table, tr, td, th, tbody, thead, tfoot {
    page-break-inside: avoid !important;
	font-family: "Verdana";
}
</style>
<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
function selisih($jam_keluar) {
$jam_selesai=date("H:i:s",mktime(date("H",strtotime($jam_keluar))-0,date("i",strtotime($jam_keluar))-0,date("s",strtotime($jam_keluar)),0,0,0));
return $jam_selesai;
}
$bdir = pg_escape_string($_GET['bdir']);
include $bdir."inc/db.inc.php";
//include_once $_SESSION['base_dir']."inc/db.inc.php";
//include_once $_SESSION['base_dir']."inc/schema.php";
$tgl_bayar = pg_escape_string($_GET['tgl-pembayaran']);
$jns_rincian = pg_escape_string($_GET['jns-rincian']);
$schema = $list_schema[$arr_data['jns-obj']];
//$total_pokok = 0;
//$total_denda = 0;
$total = 0;
$query_obj= pg_query("select nm_obj_pajak from public.obj_pajak where kd_obj_pajak='$jns_rincian'")or die('Query failed: ' . pg_last_error());
$row_obj=pg_fetch_array($query_obj);

switch ($jns_rincian) {
       case "01":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop from air_tanah.pembayaran A INNER JOIN 
			air_tanah.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "02":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_reklame as nm_usaha,b.alamat_reklame as alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop from reklame.pembayaran A INNER JOIN 
			reklame.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "03":
$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop from restoran.pembayaran A INNER JOIN 
			restoran.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "04":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop from ppj.pembayaran A INNER JOIN 
			ppj.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "05":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop from parkir.pembayaran A INNER JOIN 
			parkir.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "06":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop from hiburan.pembayaran A INNER JOIN 
			hiburan.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "07":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop from hotel.pembayaran A INNER JOIN 
			hotel.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "08":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp as npwpd,C.nama,D.nm_keg_usaha,b.nm_usaha,b.alamat_usaha,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_obj_pajak||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop from galianc.pembayaran A INNER JOIN 
			galianc.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.kd_provinsi=b.kd_provinsi and c.kd_kota=b.kd_kota and c.kd_jns=b.kd_jns and c.no_reg=b.no_reg_wp 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   case "09":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg as npwpd,C.nama,D.nm_keg_usaha,b.nama_sppt as nm_usaha,b.alamat_op as alamat_usaha,A.pokok_pajak,B.kd_propinsi||'.'||B.kd_dati2||'.'||B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_blok||'.'||B.no_urut||'.'||B.kd_jns_op as nop from bphtb.pembayaran A INNER JOIN 
			bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.nik=b.nik 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_sh
			WHERE A.tgl_bayar='$tgl_bayar' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   default:
	   $query = pg_query("select c.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from bphtb.pembayaran A INNER JOIN 
			bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.keg_usaha C on C.kd_obj_pajak=b.kd_obj_pajak and C.kd_keg_usaha=b.kd_sh
			WHERE A.tgl_bayar='$tgl_bayar' group by c.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
	   break;
	   
}
?>

<table border="1" style="font-size:12px;border:1px solid black;border-collapse:collapse;" align="center" cellpadding="2px" width="100%">
<tr class="headings">
	 <td colspan="9" align="center"><h3>DATA PEMBAYARAN <?php echo $row_obj['nm_obj_pajak']; ?> TANGGAL: <?php echo $tgl_bayar; ?></h3></td>
</tr>                                       
											
                                            <tr class="headings">  
											    <th>NO </th>                                       
                                                <th>NO BAYAR </th>                                       
                                                <th>NOP </th>
												<th>NPWPD </th>
												<th>NAMA WAJIB PAJAK</th>
												<th>JENIS USAHA</th> 
												<th>NAMA USAHA/OBJEK</th>
												<th>ALAMAT USAHA/OBJEK</th>   
												<th>JUMLAH PAJAK</th>                                     	
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
												<td class=" "><?php echo $row['nama']; ?></td> 
												<td class=" "><?php echo $row['nm_keg_usaha']; ?></td> 
												<td class=" "><?php echo $row['nm_usaha']; ?></td> 
												<td class=" "><?php echo $row['alamat_usaha']; ?></td>                                            
                                                <td class=" "><?php echo number_format ($row['pokok_pajak']); ?></td>                                                 
                                               
                                            </tr>
<?php
}
//oci_free_statement($stid);
//oci_close($conn);
?>
<tr class="even pointer">
<td class=" " colspan="8"><strong>TOTAL</strong></td>
<td class=" " align="center" ><strong><?php echo number_format($total); ?></strong></td>
</tr>
</tbody>

                                    </table>
    <table class="style1">
<tr><td>&nbsp;</td></tr>
<tr><td><span class="style3">Dicetak Oleh:</span></td>
</tr>

<tr><td><?php

$nama = pg_escape_string($_GET['nama']);
 echo $nama; ?></td></tr>
 <tr><td><?php
$nip = pg_escape_string($_GET['nip']);

 echo "Nip. &nbsp;$nip "; ?></td></tr>
 <tr><td><?php
$tg = date("d-m-Y h:i:s");

 echo "$tg"; ?></td></tr></table>                            