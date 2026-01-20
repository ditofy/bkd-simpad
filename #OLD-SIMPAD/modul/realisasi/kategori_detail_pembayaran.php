<link href="css/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">
<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
function selisih($jam_keluar) {
$jam_selesai=date("H:i:s",mktime(date("H",strtotime($jam_keluar))-0,date("i",strtotime($jam_keluar))-0,date("s",strtotime($jam_keluar)),0,0,0));
return $jam_selesai;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$tgl_bayar = pg_escape_string($_REQUEST['tgl_bayar']);
$jns_obj= pg_escape_string($_REQUEST['jns_obj']);
$jns_surat= pg_escape_string($_REQUEST['jns_surat']);
$schema = $list_schema[$arr_data['jns-obj']];
$total_pokok = 0;
$total_denda = 0;
$total = 0;
$query_obj= pg_query("select nm_obj_pajak from public.obj_pajak where kd_obj_pajak='$jns_rincian'")or die('Query failed: ' . pg_last_error());
$row_obj=pg_fetch_array($query_obj);

switch ($jns_surat) {
  case "01":
  
    switch ($jns_obj) {
       case "01":
			$query = pg_query("select d.rek_apbd,d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from air_tanah.pembayaran A INNER JOIN 
			air_tanah.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN air_tanah.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' group by d.rek_apbd,d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "02":
			$query = pg_query("select d.rek_apbd,d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from reklame.pembayaran A INNER JOIN 
			reklame.skp B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN reklame.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' group by d.rek_apbd,d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	              }
  
  break;
  
  case "02":
      switch ($jns_obj) {
	   case "03":
			$query = pg_query("select d.rek_apbd,d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from restoran.pembayaran A INNER JOIN 
			restoran.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN restoran.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' group by d.rek_apbd,d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "04":
			$query = pg_query("select d.rek_apbd,d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from ppj.pembayaran A INNER JOIN 
			ppj.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN ppj.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' group by d.rek_apbd,d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "05":
			$query = pg_query("select d.rek_apbd,d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from parkir.pembayaran A INNER JOIN 
			parkir.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN parkir.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' group by d.rek_apbd,d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "06":
			$query = pg_query("select d.rek_apbd,d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from hiburan.pembayaran A INNER JOIN 
			hiburan.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN hiburan.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' group by d.rek_apbd,d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "07":
			$query = pg_query("select d.rek_apbd,d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from hotel.pembayaran A INNER JOIN 
			hotel.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN hotel.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' group by d.rek_apbd,d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "08":
			$query = pg_query("select d.rek_apbd,d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from galianc.pembayaran A INNER JOIN 
			galianc.sptpd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN galianc.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' group by d.rek_apbd,d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	      }
	 break;  
	 
	case "04"; 
	  switch ($jns_obj) {
	   case "09":
			$query = pg_query("select c.rek_apbd,c.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from bphtb.pembayaran A INNER JOIN 
			bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.keg_usaha C on C.kd_obj_pajak=b.kd_obj_pajak and C.kd_keg_usaha=b.kd_sh
			WHERE A.tgl_bayar='$tgl_bayar' group by c.rek_apbd,c.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break; 
	                    } 
	 break; 
	 
	 case "05"; 
	  switch ($jns_obj) {
	  case "03":
			$query = pg_query("select d.rek_apbd,d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from restoran.pembayaran A INNER JOIN 
			restoran.skpdkb B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN restoran.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' group by d.rek_apbd,d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "05":
			$query = pg_query("select d.rek_apbd,d.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from parkir.pembayaran A INNER JOIN 
			parkir.skpdkb B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN parkir.dat_obj_pajak C on c.kd_kecamatan=b.kd_kecamatan and c.kd_kelurahan=b.kd_kelurahan and c.kd_obj_pajak=b.kd_obj_pajak and c.kd_keg_usaha=b.kd_keg_usaha and c.no_reg=b.no_reg
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=c.kd_obj_pajak and d.kd_keg_usaha=c.kd_keg_usaha
			WHERE A.tgl_bayar='$tgl_bayar' group by d.rek_apbd,d.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break;
	   case "09":
			$query = pg_query("select c.rek_apbd,c.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from bphtb.pembayaran A INNER JOIN 
			bphtb.skpdkb B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.keg_usaha C on C.kd_obj_pajak=b.kd_obj_pajak and C.kd_keg_usaha=b.kd_sh
			WHERE A.tgl_bayar='$tgl_bayar' group by c.rek_apbd,c.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
       break; 
	                    } 
	 break; 
	  
	   default:
	   $query = pg_query("select c.rek_apbd,c.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from bphtb.pembayaran A INNER JOIN 
			bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.keg_usaha C on C.kd_obj_pajak=b.kd_obj_pajak and C.kd_keg_usaha=b.kd_sh
			WHERE A.tgl_bayar='$tgl_bayar' group by d.rek_apbd,c.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
	   break;
	   
}
?>
<div class="x_content">
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="3" align="center"><h3>DATA PEMBAYARAN <?php echo $row_obj['nm_obj_pajak']; ?> TANGGAL: <?php echo $tgl_bayar; ?></h3></td>
											 </tr>
                                            <tr class="headings"> 
											 <th>REK APBD </th>                                         
                                                <th>KATEGORI </th>                                       
                                                <th>POKOK </th>
												                                           	
                                            </tr>
                                        </thead>
										<tbody>
<?php
$no = 0;
while ($row = pg_fetch_array($query))
	{
	$no++;
	$sub_total = $sub_total + $row['pokok_pajak'];
	$total=$total+$row['jumlah'];
?>
<tr class="even pointer">
                                                <td class=" "><?php echo $row['rek_apbd']; ?></td>  
												<td class=" "><?php echo $row['nm_keg_usaha']; ?></td>                                                
                                                <td class=" "><?php echo number_format ($row['jumlah']); ?></td>                                                
                                               
                                            </tr>
<?php
}
//oci_free_statement($stid);
//oci_close($conn);
?>
<tr class="even pointer">
<td class=" " colspan="2"><strong>TOTAL</strong></td>
<td class=" " ><strong><?php echo number_format($total); ?></strong></td>
</tr>
</tbody>

                                    </table>
                                </div>
