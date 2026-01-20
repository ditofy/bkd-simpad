<?php
$bdir = $_GET['bdir'];
include_once $bdir."inc/db.inc.php";
include_once $bdir."inc/schema.php";
$data_ser = $_GET['data'];

$data = unserialize(urldecode($data_ser));
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$schema = 'bill';
$nop = $arr_data['nop'];
$npwpd = $arr_data['npwpd'];
$nm_usaha = $arr_data['nm-usaha'];
$nm_wp = strtoupper($arr_data['nm-wp']);
$seri_bill = $arr_data['seri-bill'];
$query = pg_query("SELECT DISTINCT(A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg) AS NOP,B.nm_usaha,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp AS npwpd,C.nama,B.alamat_usaha,C.alamat FROM $schema.dat_bill A "
        . "INNER JOIN restoran.dat_obj_pajak B ON B.kd_kecamatan=A.kd_kecamatan AND B.kd_kelurahan=A.kd_kelurahan AND B.kd_obj_pajak=A.kd_obj_pajak AND B.kd_keg_usaha=A.kd_keg_usaha AND B.no_reg=A.no_reg "
        . "INNER JOIN public.wp C ON C.kd_provinsi=B.kd_provinsi AND C.kd_kota=B.kd_kota AND C.kd_jns=B.kd_jns AND C.no_reg=B.no_reg_wp "
        . "WHERE A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg LIKE '%$nop%' "
        . "AND B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp LIKE '%$npwpd%' "
        . "AND B.nm_usaha LIKE '%$nm_usaha%' "
        . "AND C.nama LIKE '%$nm_wp%' "
        . "AND A.seri = '$seri_bill'") or die('Query failed: ' . pg_last_error());
?>
<style type="text/css">
table, tr, td, th, tbody, thead, tfoot {
    page-break-inside: avoid !important;
}
</style>
<center>DATA PENYERAHAN BILL
</center>
<br />
Filter Data : [<?php echo "NOP : ".$nop."] [NPWPD : ".$npwpd."] [NM-OBJEK : ".$nm_usaha."] [NM-WP : ".$nm_wp."] [SERI-BILL : ".$seri_bill."]"; ?>
<table border="1" style="font-size:12px;border:1px solid black;border-collapse:collapse;" align="center" cellpadding="2px" width="100%">
    
        <tr class="headings">                                         
            <td>No </td>
            <td>NOP </td>
            <td>NPWPD </td>
            <td>Nama Usaha / Alamat </td>
            <td>Nama WP / Alamat</td>
            <td width="20%">Data Bill </td>
          
        </tr>
    
    <tbody>
<?php
$no = $offset;
if(pg_num_rows($query) != '0')
{
	while ($row = pg_fetch_array($query))
	{
	$no++;	
?>                                        
        <tr class="even pointer">
            <td class=" "><?php echo $no; ?></td>
            <td class=" "><?php echo $row['nop']; ?></td>
            <td class=" "><?php echo $row['npwpd']; ?></td>
            <td class=" "><?php echo $row['nm_usaha']."<br>".$row['alamat_usaha']; ?></td>
            <td class=" "><?php echo $row['nama']."<br>".$row['alamat']; ?></td>
            <?php
                $n_o_p = $row['nop'];
                $q_dat_bill = pg_query("SELECT seri,no_bill,buku,to_char(tgl_penyerahan, 'DD-MM-YYYY') AS tgl_serah FROM bill.dat_bill "
                        . "where kd_kecamatan||'.'||kd_kelurahan||'.'||kd_obj_pajak||'.'||kd_keg_usaha||'.'||no_reg = '$n_o_p' "
                        . "AND seri='$seri_bill' AND buku <> '' ORDER BY no_bill") or die('Query failed: ' . pg_last_error());
                echo "<td>";                               
                while ($row_bill = pg_fetch_array($q_dat_bill))
                {                        
                    if($row_bill['buku'] == 'AW') {
                        echo "SERI ".$row_bill['seri']." ".$row_bill['no_bill']." - ";
                    } else {
                        echo $row_bill['no_bill']." (".$row_bill['tgl_serah'].")<br>";
                    }                                          
                }                       
                echo "</td>";
                pg_free_result($q_dat_bill);
            ?>
			<?php
                $n_o_p_e = $row['nop'];                
                $q_dat_bilss = pg_query("SELECT seri,no_bill,buku,to_char(tgl_penyerahan, 'DD-MM-YYYY') AS tgl_serah FROM bill.dat_bill "
                        . "where kd_kecamatan||'.'||kd_kelurahan||'.'||kd_obj_pajak||'.'||kd_keg_usaha||'.'||no_reg = '$n_o_p_e' "
                        . "AND seri='$seri_bill' AND status_pengembalian='1' ORDER BY no_bill") or die('Query failed: ' . pg_last_error());
                echo "<td width= auto>";                               
                while ($row_bills = pg_fetch_array($q_dat_bilss))
                {                        
                   echo "$row_bills[no_bill]";
				   if ($row_bills['buku'] == 'AK'){
				   echo "<br/>";}
				   else{
				    echo "&nbsp;,&nbsp;";
				   }                                  
                }                          
                echo "</td>";
                pg_free_result($q_dat_bill);
            ?>
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
?>