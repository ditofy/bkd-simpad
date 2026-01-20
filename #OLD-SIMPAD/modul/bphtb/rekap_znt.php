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
				<h2>Data ZNT PBB</h2>
			<div class="x_title">
			</div>
    				
<table width="100%" border="0" class="table table-striped" align="left">
<tr>
<td style="vertical-align:middle" align="right">Kecamatan</td>
<td>
<div class="col-md-4 col-sm-6 col-xs-12 form-group has-feedback">
<select name="kd-kecamatan" id="kd-kecamatan" class="select2_single" style="width:300px;" onchange="get_kel($('#kd-kecamatan').val());">
<option value="010">PAYAKUMBUH BARAT</option>
<option value="020">PAYAKUMBUH TIMUR</option>
<option value="030">PAYAKUMBUH UTARA</option>
<option value="040">PAYAKUMBUH SELATAN</option>
<option value="050">LAMPOSI TIGO NAGARI</option>
</select>
</div>
</td></tr>
<tr>
<td style="vertical-align:middle" align="right">Kelurahan</td>
<td>
<div class="col-md-4 col-sm-6 col-xs-12 form-group has-feedback">
<select name="kd-kelurahan" id="kd-kelurahan" class="select2_single" style="width:300px;">
</select>
</div>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<button type="button" class="btn btn-success" id="submit-tampilkan-znt">Tampilkan Data ZNT</button>
</td>
</tr>
</table>
		<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="data-znt">		
							 
                            </div>
		
		</div>
	</div>
</div>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>
</div>
<script>
function get_kel(kd) {
	if(kd != ""){
		$.post( "modul/bphtb/get_kelurahan.php", { kd_kec: kd })
  						.done(function( data1 ) {
							$('#kd-kelurahan').html(data1);
							$("#kd-kelurahan").select2("val", "");
  					});
	}
};
        $(document).ready(function () {
			
			$(".select2_single").select2({
               placeholder: "Pilih",
               allowClear: false
                });
			$("#kd-kecamatan").select2("val", "");	
			$('#tgl-piutang').daterangepicker({
					format: 'DD-MM-YYYY',
                	singleDatePicker: true,
                	calender_style: "picker_1"
            		}, function (start, end, label) {
                		console.log(start.toISOString(), end.toISOString(), label);
            	});
			$('#submit-tampilkan-znt').click(function(){
					if($('#kd-kecamatan').val() != '') {
						$('#data-znt').html("<img src='images/loading.gif' />");
						$("#submit-tampilkan-znt").attr("disabled", "disabled");
						$.post( "modul/bphtb/cari_znt.php", { kd_kecamatan: $('#kd-kecamatan').val(), kd_kelurahan: $('#kd-kelurahan').val()})
  						.done(function( data ) {
   						$('#data-znt').html(data);
							$("#submit-tampilkan-znt").removeAttr('disabled');
  						});
					} else {
						notif('Notifikasi','Silahkan Pilih Kecamatan','error');
					}
				});
        });
</script>