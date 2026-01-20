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
$jns_surat = pg_escape_string($_REQUEST['jns_surat']);
$jns_obj = pg_escape_string($_REQUEST['jns_obj']);
$schema = $list_schema[$jns_obj];
$total_pokok = 0;
$total_denda = 0;
$total = 0;
$query_obj= pg_query("select nm_obj_pajak from public.obj_pajak where kd_obj_pajak='$jns_obj'")or die('Query failed: ' . pg_last_error());
$row_obj=pg_fetch_array($query_obj);

switch ($jns_surat){
///////////////SKPD///////////////
case "01":
	switch ($jns_obj) {
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
	   case "10":
			$query = pg_query("select A.unit,A.pkb_pokok as pokok_pajak,A.pkb_denda,A.payment_point,A.uptd_transaksi from pkb.pembayaran_opsen A
			WHERE tgl_bayar='$tgl_bayar' ORDER BY payment_point ASC") or die('Query failed: ' . pg_last_error());
       break; 
	    case "11":
			$query = pg_query("select A.unit,A.bbnkb_pokok as pokok_pajak,A.bbnkb_denda,A.payment_point,A.uptd_transaksi from bbnkb.pembayaran_opsen A
			WHERE tgl_bayar='$tgl_bayar' ORDER BY payment_point ASC") or die('Query failed: ' . pg_last_error());
       break;  
	             }
       break;
	   
///////////SPTPD//////////////
case "02":
	 switch($jns_obj) { 
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
	         }
	break;
	
//////////SSPD///////////////
case "04":	
	 	  switch($jns_obj) {
	   case "09":
			$query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg as npwpd,C.nama,D.nm_keg_usaha,b.nama_sppt as nm_usaha,b.alamat_op as alamat_usaha,A.pokok_pajak,B.kd_propinsi||'.'||B.kd_dati2||'.'||B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_blok||'.'||B.no_urut||'.'||B.kd_jns_op as nop from bphtb.pembayaran A INNER JOIN 
			bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.wp C on c.nik=b.nik 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=b.kd_obj_pajak and d.kd_keg_usaha=b.kd_sh
			WHERE A.tgl_bayar='$tgl_bayar' ORDER BY D.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	                  }
	break;	
////////SKPDKB//////////////				  
case "05":	
	switch($jns_obj) {
		 case "09":
$query = pg_query("SELECT A.JNS_SURAT||'.'||A.THN_PAJAK||'.'||A.BLN_PAJAK||'.'||A.KD_OBJ_PAJAK||'.'||A.NO_URUT_SURAT AS NO_SURAT,B.KD_PROPINSI||'.'||B.KD_DATI2||'.'||B.KD_KECAMATAN||'.'||B.KD_KELURAHAN||'.'||B.KD_BLOK||'.'||B.NO_URUT||'.'||B.KD_JNS_OP AS nop,C.KD_PROVINSI||'.'||C.KD_KOTA||'.'||C.KD_JNS||'.'||C.NO_REG as NPWPD,C.NAMA,B.NAMA_SPPT AS NM_USAHA,E.NM_KEG_USAHA,B.ALAMAT_OP||' '||B.NOMOR_OP||' '||B.RT_OP||' '||B.RW_OP||' '||B.KELURAHAN_OP AS ALAMAT_USAHA,A.THN_PAJAK,A.BLN_PAJAK,D.NM_OBJ_PAJAK,A.POKOK_PAJAK,E.NAMA_ALIAS,A.STATUS_PEMBAYARAN,F.TGL_BAYAR as TGL_REKAM FROM $schema.skpdkb A 
INNER JOIN $schema.SSPD B on 
A.JNS_SURAT_PARENT =B.JNS_SURAT AND 
A.THN_PAJAK_PARENT=B.THN_PAJAK AND
A.BLN_PAJAK_PARENT =B.BLN_PAJAK AND
A.KD_OBJ_PAJAK_PARENT=B.KD_OBJ_PAJAK AND
A.NO_URUT_SURAT_PARENT=B.NO_URUT_SURAT
LEFT JOIN PUBLIC.WP C ON 
A.NIK=C.NIK
LEFT JOIN PUBLIC.OBJ_PAJAK D ON 
D.KD_OBJ_PAJAK=A.KD_OBJ_PAJAK
LEFT JOIN PUBLIC.KEG_USAHA E ON
E.KD_KEG_USAHA=A.KD_KEG_USAHA AND 
E.KD_OBJ_PAJAK=A.KD_OBJ_PAJAK
LEFT JOIN $schema.pembayaran F ON 
F.JNS_SURAT=A.JNS_SURAT AND
F.THN_PAJAK=A.THN_PAJAK AND
F.BLN_PAJAK=A.BLN_PAJAK AND 
F.KD_OBJ_PAJAK=A.KD_OBJ_PAJAK AND 
F.NO_URUT_SURAT=A.NO_URUT_SURAT
WHERE F.TGL_BAYAR='$tgl_bayar' ORDER BY E.nm_keg_usaha ASC") or die('Query failed: ' . pg_last_error());
       break;
	   default:
$query = pg_query("SELECT A.JNS_SURAT||'.'||A.THN_PAJAK||'.'||A.BLN_PAJAK||'.'||A.KD_OBJ_PAJAK||'.'||A.NO_URUT_SURAT AS NO_SURAT,B.KD_KECAMATAN||'.'||B.KD_KELURAHAN||'.'||B.KD_OBJ_PAJAK||'.'||B.KD_KEG_USAHA||'.'||B.NO_REG AS NOP,C.KD_PROVINSI||'.'||C.KD_KOTA||'.'||C.KD_JNS||'.'||C.NO_REG AS NPWPD,C.NAMA,B.NM_USAHA,E.NM_KEG_USAHA,B.ALAMAT_USAHA,A.THN_PAJAK,A.BLN_PAJAK,D.NM_OBJ_PAJAK,A.POKOK_PAJAK,E.NAMA_ALIAS,A.STATUS_PEMBAYARAN,F.TGL_BAYAR AS TGL_REKAM from $schema.skpdkb A LEFT JOIN 
$schema.SPTPD B ON
A.JNS_SURAT_PARENT =B.JNS_SURAT AND 
A.THN_PAJAK_PARENT=B.THN_PAJAK AND
A.BLN_PAJAK_PARENT =B.BLN_PAJAK AND
A.KD_OBJ_PAJAK_PARENT=B.KD_OBJ_PAJAK AND
A.NO_URUT_SURAT_PARENT=B.NO_URUT_SURAT
LEFT JOIN PUBLIC.WP C ON
A.NIK=C.NIK
LEFT JOIN PUBLIC.OBJ_PAJAK D ON
A.KD_OBJ_PAJAK=D.KD_OBJ_PAJAK
LEFT JOIN PUBLIC.KEG_USAHA E ON 
B.KD_KEG_USAHA=E.KD_KEG_USAHA AND
B.KD_OBJ_PAJAK=E.KD_OBJ_PAJAK
LEFT JOIN $schema.PEMBAYARAN F ON
A.JNS_SURAT=F.JNS_SURAT AND
A.THN_PAJAK=F.THN_PAJAK AND
A.BLN_PAJAK=F.BLN_PAJAK AND 
A.KD_OBJ_PAJAK=F.KD_OBJ_PAJAK AND 
A.NO_URUT_SURAT=F.NO_URUT_SURAT
WHERE F.TGL_BAYAR='$tgl_bayar'  ORDER BY E.NM_KEG_USAHA ASC") or die('Query failed: ' . pg_last_error());
       break;
	  
	         }
	break;				  
//////DEFAULT////////////	   
default:
$query = pg_query("select c.nm_keg_usaha,sum(a.pokok_pajak) as jumlah from bphtb.pembayaran A INNER JOIN bphtb.sspd B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat LEFT JOIN public.keg_usaha C on C.kd_obj_pajak=b.kd_obj_pajak and C.kd_keg_usaha=b.kd_sh
WHERE A.tgl_bayar='$tgl_bayar' group by c.nm_keg_usaha") or die('Query failed: ' . pg_last_error());
break;
	   
}
?>
<div class="x_content">
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                     <?php if($jns_obj < 10 ){ ?>
									    <thead>
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
                                        </thead> <?php } else {  ?>
										 <thead>
											<tr class="headings">
											 <td colspan="7" align="center"><h3>DATA PEMBAYARAN <?php echo $row_obj['nm_obj_pajak']; ?> TANGGAL: <?php echo $tgl_bayar; ?></h3></td>
											 </tr>
                                            <tr class="headings">  
											    <th>NO </th>                                       
                                                <th align="center">JUMLAH UNIT </th> 
												<th>PAYMEN POINT</th>
												<th>UPTD</th> 
												<th>POKOK DENDA </th>                                      
                                                <th>POKOK PAJAK </th>
												<th>TOTAL </th>
                                            </tr> 
                                        </thead> <?php  }?>
										
										<tbody>
<?php
$no = 0;
while ($row = pg_fetch_array($query))
	{
	$no++;
	$sub_total = $sub_total + $row['pokok_pajak'];
	$total=$total+$row['pokok_pajak']; 
	$total_unit =$total_unit +$row['unit'];
	$total_denda=$total_denda + $row['pkb_denda'];
	
												if($jns_obj < 10 ){ ?>
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
												 <?php  } else { ?>
												 <tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td> 
												<td class=" ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['unit']; ?></td> 
												<td class=" "><?php echo $row['payment_point']; ?></td> 
												<td class=" "><?php echo $row['uptd_transaksi']; ?></td>  
												<td class=" "><?php echo  number_format($row['pkb_denda']); ?></td> 
												<td class=" "><?php echo  number_format($row['pokok_pajak']); ?></td> 
												<td class=" "><?php $total = $row['pkb_denda'] + $row['pokok_pajak'];
												echo  number_format($total); ?></td>
												
                                                                                               
                                                 </tr>
												 <?php } ?>
<?php
}
//oci_free_statement($stid);
//oci_close($conn);
if($jns_obj < 10 ){ ?>
<tr class="even pointer">
<td class=" " colspan="8"><strong>TOTAL</strong></td>
<td class=" " ><strong><?php echo number_format($total); ?></strong></td>
</tr>
<?php } else { ?>
<tr class="even pointer">
<td class=" " ><strong>TOTAL</strong></td>
<td class=" " ><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($total_unit); ?></strong></td>
<td class=" " colspan="2"><strong></strong></td>
<td class=" " ><strong><?php echo number_format($total_denda); ?></strong></td>
<td class=" " ><strong><?php echo number_format($sub_total); ?></strong></td>
</tr>
<?php }?>
</tbody>

                                    </table>
                                </div>
