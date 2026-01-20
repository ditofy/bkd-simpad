<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.numeric.min.js"></script>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script>
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
				
	$("#nomor").inputmask();			
	
	
	
	$('#proses-skpdkb-bphtb').click(function(){
		if( $('#nomor').val() == null || $('#ketetapan').val() == '' || $('#pajak-baru').val() == '' || $('#dasar-pajak').val() == '' || $('#pajak-setor').val() == '' || $('#tanda-tangan').val() == null || $('#tanggal-penetapan').val() == '' ) {
			notif('Notifikasi','Isi Semua Data','error');
		} else {
			$("#proses-skpdkb-bphtb").attr("disabled", "disabled");
			var inputs = $('#frm-skpdkb').serializeArray();
					$.post( "modul/skpdkb/penetapan_p_simpan_skpdkb.php", { postdata: inputs })
  						.done(function( data1 ) {
							var obj = jQuery.parseJSON(data1);							
							if( obj.status == "ok") {
								notif('Notifikasi','SKPDKB Berhasil Disimpan No : '+obj.no_skpdkb,'success');
								$("#no-skpdkb").val(obj.no_skpdkb);
								$("#cetak").removeAttr('disabled');								
							} else {
								notif('Notifikasi',obj.error_msg,'error');	
							}
							//$('#reset').click();														
  					});
		}
	});
	
	$('#resetfrm').click(function(){
		$('#frm-restoran-skp')[0].reset();
		$("#no-skp").val('');
		$("#tanda-tangan").select2("val", "");
		//$("#bulan-pajak").select2("val", "");	
		$("#cetak").attr("disabled", "disabled");
		$("#proses-skp-restoran").removeAttr('disabled');
		$("#nop").removeAttr('readonly');
	});
	
	$('#tanggal-penetapan').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
    });
	
	$('#cetak').click(function(){
		$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
			$.post( "modul/skpdkb/gen_pdf_skpdkb.php", { no_skpdkb: $('#no-skpdkb').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});
				
	//$("#bulan-pajak").select2("val", "");
	$("#tanda-tangan").select2("val", "");
	$("#ketetapan").numeric();
	$("#cetak").attr("disabled", "disabled");
	$("#tanggal-penetapan").inputmask();
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">SKPD</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>				
<form id="frm-skpdkb" name="frm-skpdkb" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SKPDKB</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-skpdkb" name="no-skpdkb" class="form-control col-md-7 col-xs-12" placeholder="Nomor SKPDKB" readonly="readonly">
    </div>
</div>



<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NO SKP/SPTPD/SSPD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nomor" name="nomor" class="form-control col-md-7 col-xs-12" placeholder="Nomor SKP/SPTPD/SSPD" data-inputmask="'mask': '99.9999.99.99.9999'">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP</label>
    	<div class="col-md-3 col-sm-6 col-xs-12">
    		<input type="text" id="nama-wp" name="nama-wp" class="form-control col-md-7 col-xs-12" placeholder="Nama Wajib Pajak">
    	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat WP</label>
    	<div class="col-md-3 col-sm-6 col-xs-12">
    		<input type="text" id="alamat-wp" name="alamat-wp" class="form-control col-md-7 col-xs-12" placeholder="Alamat Wajib Pajak">
    	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Dasar Pengenaan Pajak</label>
    	<div class="col-md-3 col-sm-6 col-xs-12">
    		<input type="text" id="dasar-pajak" name="dasar-pajak" class="form-control col-md-7 col-xs-12" placeholder="Dasar Pengenaan Pajak Yang Baru">
    	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pajak Terutang</label>
    	<div class="col-md-3 col-sm-6 col-xs-12">
    		<input type="text" id="pajak-baru" name="pajak-baru" class="form-control col-md-7 col-xs-12" placeholder="Pajak Terutang Yang Baru">
    	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pajak Yg Telah Di Setor</label>
    	<div class="col-md-3 col-sm-6 col-xs-12">
    		<input type="text" id="pajak-setor" name="pajak-setor" class="form-control col-md-7 col-xs-12" placeholder="Pajak Yang Telah Di Setor">
    	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pejabat </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tanda-tangan" id="tanda-tangan" class="select2_single">
			<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT A.nip,B.jabatan FROM public.tt A INNER JOIN public.user B ON B.nip=A.nip";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				echo "<option value=\"".$row['nip']."\">".$row['jabatan']."</option>";				
			}
			pg_free_result($tampil);
			pg_close($dbconn);
			?>
			</select>
    </div>
</div>
<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Penetapan</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tanggal-penetapan" name="tanggal-penetapan" class="form-control has-feedback-left" placeholder="Tanggal Penetapan" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
</div>
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-skpdkb-bphtb" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>			
			<button id="cetak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SKPDKB</button>
        </div>
    </div>
</form>