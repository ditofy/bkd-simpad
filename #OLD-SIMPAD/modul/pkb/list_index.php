<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
?>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script type="text/javascript">
function get_kel(kd) {
	if(kd != ""){
	$.ajax({
                	type: "GET",
                	url: "inc/get_kel.php",
                	data: 'kd='+kd,
               		success: function(data){
						$('#kd-kelurahan').html(data);
						$("#kd-kelurahan").select2("val", "");
                	}
            });	
	}		
};
function list_data(pg) {
	$('#list-pkb').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");	
	var inputs = $('#filter-data').serializeArray();	
			$.post( "modul/pkb/list.php", { postdata: inputs, page: pg })
  						.done(function( data1 ) {
							$('#list-pkb').html(data1);
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
		$("#jenis-kd").select2("val", "");
		$("#kd-kecamatan").select2("val", "");
		
	});
	
	$('#tampilkan').click(function() {
		list_data(0);
	});
	
	$("#nop").inputmask();
	$("#npwpd").inputmask();
	$("#jenis-kd").select2("val", "");
	$("#kd-kecamatan").select2("val", "");

});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">DATA RESTORAN</h4>
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
    <td style="vertical-align:middle" align="right">TNKB</td>
    <td><input type="text" id="tnkb" name="tnkb" class="form-control col-md-7 col-xs-12" placeholder="Nomor Kenderaan Bermotor" data-inputmask="'mask': '99.99.99.999999'"></td>
    <td style="vertical-align:middle" align="right">MERK / TYPE</td>
    <td><input type="text" id="merk" name="merk" class="form-control col-md-7 col-xs-12" placeholder="Merk / Type Kenderaan"></td>
    <td style="vertical-align:middle" align="right">ALAMAT </td>
    <td><input type="text" id="alamat" name="alamat" class="form-control col-md-7 col-xs-12" placeholder="Alamat Kenderaan"></td>
  </tr>
  <tr>
    <td style="vertical-align:middle" align="right">NAMA WP	 </td>
    <td><input type="text" id="nm-wp" name="nm-wp" class="form-control col-md-7 col-xs-12" placeholder="Nama WP"></td>
    <td style="vertical-align:middle" align="right">JENIS KENDERAAN </td>
    <td>
		<select name="jenis-kd" id="jenis-kd" class="select2_single" style="width:200px">
		<option value="SEPEDA MOTOR">Sepeda Motor</option>
		<option value="NON SEPEDA MOTOR">Non Sepeda Motor</option>
		</select>	</td>
    <td style="vertical-align:middle" align="right">KECAMATAN</td>
    <td>
	<select name="kd-kecamatan" id="kd-kecamatan" class="select2_single" onChange="get_kel($('#kd-kecamatan').val());">
		<?php 
			$sql = "SELECT kd_kecamatan,nm_kecamatan FROM public.kecamatan";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
			
				echo "<option value=\"".$row['kd_kecamatan']."\">".$row['nm_kecamatan']."</option>";
			}
			pg_free_result($tampil);
		?>
		</select>	</td>
  </tr>
  <tr>
    <td style="vertical-align:middle" align="right">KELURAHAN</td>
    <td>
	<select name="kd-kelurahan" id="kd-kelurahan" class="select2_single" style="width:300px">
	</select>	</td>
   
   
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
<div id="list-pkb">

</div>
<?php
pg_close($dbconn);
?>