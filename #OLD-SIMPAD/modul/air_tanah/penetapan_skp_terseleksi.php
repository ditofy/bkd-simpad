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
	
	$('#nop').autocomplete({
      			source: "modul/air_tanah/get_nop_skp.php",
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#nop").attr("readonly", "readonly");
				$.ajax({
                	type: "GET",
                	url: "modul/air_tanah/get_detail_objek.php",
                	data: 'search='+ui.item.value,
               		success: function(data){
						var obj = jQuery.parseJSON(data);
						$('#npwpd').val(obj[0].npwpd);
						$('#nik').val(obj[0].nik);
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);
						$('#nama-usaha').val(obj[0].nm_usaha);
						$('#alamat-usaha').val(obj[0].alamat_usaha);
						$('#ketetapan').val(obj[0].ketetapan);											
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});	
	
	$('#proses-skp-restoran').click(function(){
		if( $('#nop').val() == '' || $('#npwpd').val() == '' || $('#ketetapan').val() == '' || $('#tanda-tangan').val() == null || $('#tanggal-penetapan').val() == '' ) {
			notif('Notifikasi','Isi Semua Data','error');
		} else {
			$("#proses-skp-restoran").attr("disabled", "disabled");
			var inputs = $('#frm-restoran-skp').serializeArray();
					$.post( "modul/air_tanah/penetapan_p_simpan_skp.php", { postdata: inputs })
  						.done(function( data1 ) {
							var obj = jQuery.parseJSON(data1);							
							if( obj.status == "ok") {
								notif('Notifikasi','SKP Berhasil Disimpan No : '+obj.no_skp,'success');
								$("#no-skp").val(obj.no_skp);
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
			$.post( "modul/air_tanah/gen_pdf_skp.php", { no_skp: $('#no-skp').val() })
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
<form id="frm-restoran-skp" name="frm-restoran-skp" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SKP</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-skp" name="no-skp" class="form-control col-md-7 col-xs-12" placeholder="Nomor SKP" readonly="readonly">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tahun Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tahun-pajak" id="tahun-pajak" class="select2_single" style="width:200px">
			<option value="2022">2022</option>
			<option value="2023">2023</option>
			<option value="2024">2024</option>
			<option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
		</select>
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Bulan Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="bulan-pajak" id="bulan-pajak" class="select2_single" style="width:200px">
			<option value="01" <?php if(date('m') == '01') echo "selected=\"selected\"";?>>Januari</option>
			<option value="02" <?php if(date('m') == '02') echo "selected=\"selected\"";?>>Februari</option>
			<option value="03" <?php if(date('m') == '03') echo "selected=\"selected\"";?>>Maret</option>
			<option value="04" <?php if(date('m') == '04') echo "selected=\"selected\"";?>>April</option>
			<option value="05" <?php if(date('m') == '05') echo "selected=\"selected\"";?>>Mei</option>
			<option value="06" <?php if(date('m') == '06') echo "selected=\"selected\"";?>>Juni</option>
			<option value="07" <?php if(date('m') == '07') echo "selected=\"selected\"";?>>Juli</option>
			<option value="08" <?php if(date('m') == '08') echo "selected=\"selected\"";?>>Agustus</option>
			<option value="09" <?php if(date('m') == '09') echo "selected=\"selected\"";?>>September</option>
			<option value="10" <?php if(date('m') == '10') echo "selected=\"selected\"";?>>Oktober</option>
			<option value="11" <?php if(date('m') == '11') echo "selected=\"selected\"";?>>November</option>
			<option value="12" <?php if(date('m') == '12') echo "selected=\"selected\"";?>>Desember</option>
		</select>
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NOP</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPWPD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak Daerah" readonly="readonly">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nama-wp" name="nama-wp" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Nama Wajib Pajak">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="alamat" name="alamat" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Alamat Wajib Pajak">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Usaha</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nama-usaha" name="nama-usaha" class="form-control col-md-7 col-xs-12" placeholder="Nama Reklame" readonly="readonly">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Usaha</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="alamat-usaha" name="alamat-usaha" class="form-control col-md-7 col-xs-12" placeholder="Alamat Reklame" readonly="readonly">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ketetapan</label>
    	<div class="col-md-3 col-sm-6 col-xs-12">
    		<input type="text" id="ketetapan" name="ketetapan" class="form-control col-md-7 col-xs-12" placeholder="Ketetapan Pajak">
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
        	<button id="proses-skp-restoran" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>			
			<button id="cetak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SKP</button>
        </div>
    </div>
</form>