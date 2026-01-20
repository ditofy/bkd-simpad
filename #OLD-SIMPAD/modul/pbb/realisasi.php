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
				<h4 class="modal-title" id="myModalLabel">REALISASI PBB</h4>
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
				<h2>Realisasi</h2>
			<div class="x_title">
			</div>
    				 <div class="form-group col-md-2">
  <label for="thn-pajak">Tahun Pajak:</label>
  <select class="form-control" id="thn-pajak" name="thn-pajak">
    <?php 
  $thn = date('Y');
  while($thn >= '2014') {
  ?>
    <option><?php echo $thn; ?></option>
	<?php $thn--; } ?>	
  </select>

</div>
<div class="form-group col-md-4">
  <label for="kecamatan">Kecamatan:</label>
  <select class="form-control" id="kecamatan" name="kecamatan">
  <option>13.76.Kota Payakumbuh</option>
  <option>13.76.010.Payakumbuh Barat</option>
  <option>13.76.020.Payakumbuh Timur</option>
  <option>13.76.030.Payakumbuh Utara</option>
  <option>13.76.040.Payakumbuh Selatan</option>
  <option>13.76.050.Lampasi Tigo Nagari</option>
  </select>
</div>

		<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="det-realisasi">		
					cetak	 
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
			$('#det-realisasi').html("<img src='images/loading.gif' />");
			$.post( "modul/pbb/ajax_realisasi.php", { thnpajak: $('#thn-pajak').val(), kecamatan: $('#kecamatan').val() })
  						.done(function( data ) {
   							$('#det-realisasi').html(data);
  						});
			$('#thn-pajak').change(function(){
				$('#det-realisasi').html("<img src='images/loading.gif' />");
				$.post( "modul/pbb/ajax_realisasi.php", { thnpajak: $('#thn-pajak').val(), kecamatan: $('#kecamatan').val() })
  						.done(function( data ) {
   							$('#det-realisasi').html(data);
  						});	
			});
			$('#kecamatan').change(function(){
				$('#det-realisasi').html("<img src='images/loading.gif' />");
				$.post( "modul/pbb/ajax_realisasi.php", { thnpajak: $('#thn-pajak').val(), kecamatan: $('#kecamatan').val() })
  						.done(function( data ) {
   							$('#det-realisasi').html(data);
  						});	
			});
        });
</script>