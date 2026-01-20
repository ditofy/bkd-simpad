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
			$.post( "modul/bphtb/list.php", { postdata: inputs, page: pg })
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
		$("#npwpd").val();
	});
	
	$('#tampilkan').click(function() {
		list_data(0);
	});
	
	$("#nop").inputmask();
	$("#npwpd").inputmask();
	$("#kd-ppat").select2("val", "");
	$("#kd-kecamatan").select2("val", "");
	$("#kd-transaksi").select2("val", "");
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">DATA PELAYANAN BPHTB</h4>
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
    <td style="vertical-align:middle" align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td style="vertical-align:middle" align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
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