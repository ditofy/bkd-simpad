<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/db.orcl.inc.php";
?>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script type="text/javascript">
function get_kel(kd) {
	if(kd != ""){
	$.ajax({
                	type: "GET",
                	url: "inc/get_kel_pbb.php",
                	data: 'kd='+kd,
               		success: function(data){
						$('#kd-kelurahan').html(data);
						$("#kd-kelurahan").select2("val", "");
                	}
            });	
	}		
};
function list_data(pg) {
	$('#list-bphtb').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");	
	var inputs = $('#filter-data').serializeArray();	
			$.post( "modul/bphtb/list_bphtb.php", { postdata: inputs, page: pg })
  						.done(function( data1 ) {
							$('#list-bphtb').html(data1);
  					});
};
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
				
	$('#resetfrm').click(function(event) {
		event.preventDefault();
		$('#filter-data')[0].reset();
		$("#kd-ppat").select2("val", "");
		$("#kd-transaksi").select2("val", "");
		$("#kd-kecamatan").select2("val", "");
		$("#kd-kelurahan").select2("val", "");
		$("#status-byr").select2("val", "");
		$("#bln-pjk").select2("val", "");
		$("#npwpd").val();
	});
	
	$('#tampilkan').click(function() {
		list_data(0);
	});
	
	$("#nop").inputmask();
	$("#npwpd").inputmask();
	$("#no-pel").inputmask();
	$("#kd-ppat").select2("val", "");
	$("#kd-kecamatan").select2("val", "");
	$("#kd-transaksi").select2("val", "");
	$("#status-byr").select2("val", "");
	$("#bln-pjk").select2("val", "");
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">DATA VALIDASI BPHTB</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="x_content">
<form id="filter-data" name="filter-data">
	<table width="100%" border="0" class="table table-striped">
	<tr>
	<td colspan="6">Filter Data</td>
	</tr>
  <tr>
    <td style="vertical-align:middle" align="right">NPWPD</td>
    <td><input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak Daerah" data-inputmask="'mask': '99.99.99.999999'"></td>
    <td style="vertical-align:middle" align="right">NOP</td>
    <td><input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak" data-inputmask="'mask': '99.99.999.999.999.9999.9'"></td>
   <td style="vertical-align:middle" align="right">Nama WP </td>
    <td><input type="text" id="nm-wp" name="nm-wp" class="form-control col-md-7 col-xs-12" placeholder="Nama WP"></td>
  </tr>
  <tr>
    
    <td style="vertical-align:middle" align="right">PPAT </td>
    <td>
		<select name="kd-ppat" id="kd-ppat" class="select2_single" style="width:200px">
		<?php 
			$sql = "SELECT * FROM public.ppat";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
			
				echo "<option value=\"".$row['id'].".".$row['nama_ppat']."\">".$row['nama_ppat']."</option>";
			}
			pg_free_result($tampil);
			?>
		</select>	</td>
    <td style="vertical-align:middle" align="right">Kecamatan</td>
    <td>
	<select name="kd-kecamatan" id="kd-kecamatan" class="select2_single" onchange="get_kel($('#kd-kecamatan').val());">
		<?php 
		   $stid = oci_parse($conn,"SELECT KD_KECAMATAN,NM_KECAMATAN FROM PBB.REF_KECAMATAN ORDER BY KD_KECAMATAN ASC");
			oci_execute($stid);
			while ($row = oci_fetch_array($stid, OCI_ASSOC))
			{
			
				echo "<option value=\"".$row['KD_KECAMATAN'].".".$row['NM_KECAMATAN']."\">".$row['NM_KECAMATAN']."</option>";
			}
			oci_free_statement($stid);
			oci_close($conn);
		?>
		</select>	</td>
		 <td style="vertical-align:middle" align="right">Kelurahan</td>
    <td>
	<select name="kd-kelurahan" id="kd-kelurahan" class="select2_single" style="width:200px">
	</select>	</td>
  </tr>
  <tr>
    <td style="vertical-align:middle" align="right">Jenis Transaksi</td>
    <td>
	<select name="kd-transaksi" id="kd-transaksi" class="select2_single" style="width:300px">
			<?php 
			$sql = "SELECT * FROM public.transaksi";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
			
				echo "<option value=\"".$row['id']."\">".$row['nm_transaksi']."</option>";
			}
			pg_free_result($tampil);
			?>
			</select>	</td>
    <td style="vertical-align:middle" align="right">No Pelayanan&nbsp;</td>
    <td><input type="text" id="no-pel" name="no-pel" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pelayanan" data-inputmask="'mask': '9999.9999'"></td>
    <td style="vertical-align:middle" align="right">Pokok Pajak </td>
    <td>&nbsp;<input type="text" id="pokok-pajak" name="pokok-pajak" class="form-control col-md-7 col-xs-12" placeholder="Pokok Pajak"></td>
  </tr>
  <tr> <td style="vertical-align:middle" align="left">Status Pembayaran</td><td><select name="status-byr" id="status-byr" class="select2_single" style="width:200px">
			<option value="0">Belum Bayar</option>
			<option value="1">Sudah Bayar</option>
			<option value="2">Batal</option>
			
		</select></td>
		<td style="vertical-align:middle" align="right">Tahun</td>
    <td><select name="tahun-pajak" id="tahun-pajak" class="select2_single" style="width:60px;">
    <?php 
  	$thn = date('Y')+1;
  while($thn >= '2017') {
  	if($thn == date('Y')) {
    	echo "<option selected=\"selected\" value=\"$thn\">$thn</option>";
	} else {
		echo "<option value=\"$thn\">$thn</option>";
	}
   	$thn--; 
   } 
   ?>
  </select></td>
    <td style="vertical-align:middle" align="right">Bulan</td>
    <td><select name="bln-pjk" id="bln-pjk" class="select2_single" style="width:100px">
			<option value="01">Januari</option>
			<option value="02">Februari</option>
			<option value="03">Maret</option>
			<option value="04">April</option>
			<option value="05">Mei</option>
			<option value="06">Juni</option>
			<option value="07">Juli</option>
			<option value="08">Agustus</option>
			<option value="09">September</option>
			<option value="10">Oktober</option>
			<option value="11">November</option>
			<option value="12">Desember</option>
		</select></td>
		</tr>
  <tr>
  <tr>
    <td colspan="6" align="center" style="vertical-align:middle">
	<button id="tampilkan" type="button" class="btn btn-success">Tampilkan</button>
	<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
	</td>
    </tr>
</table>
</form>
</div>
<div id="list-bphtb">

</div>
<?php
pg_close($dbconn);
?>