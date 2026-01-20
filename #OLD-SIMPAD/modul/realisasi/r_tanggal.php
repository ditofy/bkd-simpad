<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script>
function list_data(pg) {
	$('#list-restoran').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");	
	var inputs = $('#filter-data').serializeArray();	
			$.post( "modul/realisasi/kategori_tanggal_detail_pembayaran.php", { postdata: inputs,page: pg })
  						.done(function( data1 ) {
							$('#list-restoran').html(data1);
  					});
};
$(document).ready(function () {
                $(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
                $(".select2_group").select2({});
               
				$("#jenis-objek").select2("val", "");
				$("#jenis-laporan").select2("val", "");
				
					
				
	$('#tampilkan').click(function() {
		if($("#jenis-objek").val() != null) {
			list_data(0);
		} else {
			notif('Notifikasi','Pilih Jenis Pajak','error');
		}
	});
			
			
			$('#tgl-awal').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#tgl-akhir').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#cetak-tanggal').click(function(){
					if($('#tgl-awal').val() == ''){
						$('#modal-cnt').html("<center><br><br><br>Silahkan Pilih Tanggal Pembayaran<br><br></center>");	        				
    				} else {
						$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
						$.post( "modul/realisasi/gen_pdf_ctt_pem_tanggal.php", {tgl_awal: $('#tgl-awal').val(),tgl_akhir: $('#tgl-akhir').val(), jns_objek: $('#jenis-objek').val(),jns_laporan: $('#jenis-laporan').val() }).done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
						});
					}
				});
			
			
			
			
		/*	$('#submit-detail-tanggal').click(function(){
					if($('#tgl-awal').val() != '') {
						$('#realisasi-tanggal').html("<img src='images/loading.gif' />");
						$("#submit-detail-tanggal").attr("disabled", "disabled");
						$.post( "modul/realisasi/kategori_tanggal_detail_pembayaran.php", { tgl_awal: $('#tgl-awal').val(),tgl_akhir: $('#tgl-akhir').val(), jns_objek: $('#jenis-objek').val(),jns_laporan: $('#jenis-laporan').val() })
  						.done(function( data ) {
   							$('#realisasi-tanggal').html(data);
							$("#submit-detail-tanggal").removeAttr('disabled');
  						});
					} else {
						notif('Notifikasi','Silahkan Pilih Tanggal','error');
					}
				});*/
});
			
			
				

</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">REALISASI PAJAK</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        
							<div class="row x_title">
                                <div class="col-md-6">
                                <h3><small>DETAIL PENERIMAAN PAJAK PER HARI</small></h3>    
                                </div>
                                
                            </div>
<form id="filter-data" name="filter-data" data-parsley-validate class="form-horizontal form-label-left">
	

	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal </label>
		<div class="col-md-3 col-sm-3 col-xs-6">
        	<input type="text" id="tgl-awal" name="tgl-awal" class="form-control has-feedback-left" placeholder="Tanggal Awal" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	<div class="col-md-3 col-sm-3 col-xs-6">
        	<input type="text" id="tgl-akhir" name="tgl-akhir" class="form-control has-feedback-left" placeholder="Tanggal Akhir" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Pajak
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="jenis-objek" id="jenis-objek" class="select2_single" style="width:300px">
			<?php 
			$sql = "SELECT * FROM public.obj_pajak ORDER BY kd_obj_pajak";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
			
				echo "<option value=\"".$row['kd_obj_pajak']."\">".$row['nm_obj_pajak']."</option>";
			}
			pg_free_result($tampil);
			?>
			</select>
        </div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Laporan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<select name="jenis-laporan" id="jenis-laporan" class="select2_single" style="width:200px">
				<option value="01">01 - PER SUB JENIS PAJAK</option>
				<option value="02">02 - PER RINCIAN</option>
				<option value="03">03 - SELURUH PAJAK</option>
			</select>
    	</div>
    </div>
	
	
	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
		<button id="tampilkan" type="button" class="btn btn-success">Tampilkan</button>
        
			<button id="cetak-tanggal" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button> 
			
    </div>
</form>
<div id="list-restoran">

</div>
<!--<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="realisasi-tanggal">		
							 
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
<?php
pg_close($dbconn);
?>