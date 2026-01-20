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
	$('#list-inner').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");	
	var inputs = $('#filter-data').serializeArray();	
			$.post( "modul/retribusi/tampil_laporan_inner.php", { tgl_awal: $('#tgl-awal').val(),tgl_akhir: $('#tgl-akhir').val(), jns_objek: $('#jenis-objek').val(),jns_laporan: $('#jenis-laporan').val(),page: pg })
  						.done(function( data1 ) {
							$('#list-inner').html(data1);
  					});
};
$(document).ready(function () {
                $(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
                $(".select2_group").select2({});
				$("#tahun-pajak").select2("val", "");
				$("#tanda-tangan").select2("val", "");

	$('#tampilkan').click(function() {
		if($("#opd").val() != null) {
			list_data(0);
		} else {
			notif('Notifikasi','Pilih OPD','error');
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
			
			
			
			$('#cetak-lap-pajak').click(function(){
					if($('#tgl-awal').val() == ''  || ('#tgl-akhir').val == ''){
						$('#modal-cnt').html("<center><br><br><br>Silahkan Pilih Tanggal Awal <br><br></center>");	        				
    				} else {
						$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
						$.post( "modul/realisasi/gen_pdf_lap_pajak.php", {tgl_awal: $('#tgl-awal').val(),tgl_akhir: $('#tgl-akhir').val(),ttd: $('#tanda-tangan').val(),tahun: $('#tahun-pajak').val() }).done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
						});
					}
				});
			
			

});
			
			
				

</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">LAPORAN PENERIMAAN PER JENIS PAJAK DAERAH</h4>
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
                                <h3><small>LAPORAN PENERIMAAN PER MASING-MASING PAJAK DAERAH</small></h3>    
                                </div>
                                
                            </div>
<form id="frm-laporan-inner" name="frm-laporan-inner" data-parsley-validate class="form-horizontal form-label-left">
	

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
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tahun Pajak
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="tahun-pajak" id="tahun-pajak" class="select2_single" style="width:200px;">
    <?php 
  	$thn = date('Y')+1;
  while($thn >= '2019') {
  	if($thn == date('Y')) {
    	echo "<option selected=\"selected\" value=\"$thn\">$thn</option>";
	} else {
		echo "<option value=\"$thn\">$thn</option>";
	}
   	$thn--; 
   } 
   ?>
  </select>
        </div>
	</div>
	
	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pejabat </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tanda-tangan" id="tanda-tangan" class="select2_single" style="width:300px;">
			<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT A.nip,B.jabatan FROM public.tt A INNER JOIN public.user B ON B.nip=A.nip";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				echo "<option value=\"".$row['nip']."\">".$row['jabatan']."</option>";				
			}					
			?>
			</select>
    </div>
</div>
	
	
	
	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
		
        
			<button id="cetak-lap-pajak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>        </div>
    </div>
</form>
<div id="list-inner">

</div>
<!--<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="realisasi-tanggal">		
							 
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
<?php
pg_close($dbconn);
?>