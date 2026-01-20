<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">PIUTANG PBB</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="">
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
				<h2>Simulasi ReKelas Tanah PBB</h2>
			<div class="x_title">
			</div>
    				
<table width="100%" border="0" class="table table-striped" align="left">
<tr>
<td style="vertical-align:middle" align="right">Nilai Kenaikan Rekelas</td>
<td>
<div class="col-md-4 col-sm-6 col-xs-12 form-group has-feedback">
<input type="text" id="naik" name="naik" class="form-control col-md-7 col-xs-12" placeholder="Input Kenaikan Kelas">
</div>
</td></tr>
<tr>
<td style="vertical-align:middle" align="right">Persentase Ratio</td>
<td>
<div class="col-md-4 col-sm-6 col-xs-12 form-group has-feedback">
<input type="text" id="ratio" name="ratio" class="form-control col-md-7 col-xs-12" placeholder="Input Assesment Ratio">

</div>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<button type="button" class="btn btn-success" id="submit-tampilkan-simulasi">Tampilkan Data Rekelas</button>
</td>
</tr>
</table>
		<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="data-simulasi">		
							 
                            </div>
		
		</div>
	</div>
</div>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>
</div>
<script>
    $(document).ready(function () {
			
			$('#submit-tampilkan-simulasi').click(function(){
					if($('#naik').val() != '') {
						$('#data-simulasi').html("<img src='images/loading.gif' />");
						$("#submit-tampilkan-simulasi").attr("disabled", "disabled");
						$.post( "modul/bphtb/cari_simulasi.php", { naik: $('#naik').val(), ratio: $('#ratio').val()})
  						.done(function( data ) {
   						$('#data-simulasi').html(data);
							$("#submit-tampilkan-simulasi").removeAttr('disabled');
  						});
					} else {
						notif('Notifikasi','Silahkan Input Kenaikan Kelas','error');
					}
				});
        });
</script>