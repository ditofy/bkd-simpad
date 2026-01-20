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
	$('#list-nik').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");	
	var inputs = $('#filter-data').serializeArray();	
			$.post( "modul/bphtb/list_nik.php", { postdata: inputs, page: pg })
  						.done(function( data1 ) {
							$('#list-nik').html(data1);
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
		
	});
	
	$('#tampilkan').click(function() {
		list_data(0);
	});
$('#proses-daftar-bphtb').click(function(){
			
			var lang = [];
			 $("input[name='syarat']:checked").each(function(){
            lang.push(this.value);
        });

	$("#proses-update-pbb-massal").attr("disabled", "disabled");
	var inputs = $('#frm-update-pbb-massal').serializeArray();
					$.post( "modul/bphtb/p_update_pbb_massal.php", { postdata: inputs,lang:lang })
  						.done(function( data1 ) {
   							notif('Notifikasi',data1,'success');
							$('#resetfrm').click();
							$("#proses-update-pbb-massal").removeAttr('disabled');
  					});
	
});	
	
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">DATA WAJIB PAJAK PBB</h4>
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
<form id="frm-update-pbb-massal" name="frm-update-pbb-massal" data-parsley-validate class="form-horizontal form-label-left">
	<table width="100%" border="0" class="table table-striped">
	<tr>
	<td colspan="6">Filter Data</td>
	</tr>
  <tr>
   
   <td style="vertical-align:middle" align="right">Nama WP </td>
    <td><input type="text" id="nm-wp" name="nm-wp" class="form-control col-md-7 col-xs-12" placeholder="Nama WP"></td>
  </tr>
  <tr>
   
   <td style="vertical-align:middle" align="right">NIK </td>
    <td><input type="text" id="nik-wp" name="nik-wp" class="form-control col-md-7 col-xs-12" placeholder="NIK WP"></td>
  </tr>
  

  <tr>
  <tr>
    <td colspan="6" align="center" style="vertical-align:middle">
	<button id="tampilkan" type="button" class="btn btn-success">Tampilkan</button>
	<button id="proses-daftar-bphtb" type="button" class="btn btn-warning">Update Data</button>
	<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
	</td>
    </tr>
</table>
</form>

</div>
<div id="list-nik">

</div>
<?php
pg_close($dbconn);
?>