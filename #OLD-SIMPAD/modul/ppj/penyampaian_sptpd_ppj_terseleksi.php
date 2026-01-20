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
				
	$('#tanggal-penyampaian').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
    });
				
	$('#jenis-pajak').change(function(){
		$( "#nop" ).autocomplete('option', 'source', "modul/bill/get_nop_sptpd.php?obj="+$('#jenis-pajak').val());
	});
	
	$('#cetak').click(function(){
		$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
			$.post( "modul/bill/gen_pdf_sptpd.php", { no_sptpd: $('#no-sptpd').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});
	
	$('#nop').autocomplete({
      			source: "modul/bill/get_nop_sptpd.php?obj="+$('#jenis-pajak').val(),
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#nop").attr("readonly", "readonly");
				$.ajax({
                	type: "GET",
                	url: "modul/bill/get_detail_objek.php",
                	data: 'search='+ui.item.value,
               		success: function(data){
						var obj = jQuery.parseJSON(data);
						$('#npwpd').val(obj[0].npwpd);
						$('#nik').val(obj[0].nik);
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);
						$('#nama-usaha').val(obj[0].nm_usaha);
						$('#alamat-usaha').val(obj[0].alamat_usaha);											
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});	
	
	$('#proses-sptpd-restoran').click(function(){
		if( $('#nop').val() == '' || $('#npwpd').val() == '' || $('#tarif').val() == null || $('#dasar-pengenaan-pajak').val() == '' || $('#tanggal-penyampaian').val() == '' ) {
			notif('Notifikasi','Isi Semua Data','error');
		} else {
			$("#proses-sptpd-restoran").attr("disabled", "disabled");
			var inputs = $('#frm-restoran-sptpd').serializeArray();
					$.post( "modul/ppj/penyampaian_p_simpan_sptpd.php", { postdata: inputs })
  						.done(function( data1 ) {
							var obj = jQuery.parseJSON(data1);							
							if( obj.status == "ok") {
								notif('Notifikasi','SPTPD Berhasil Disimpan No : '+obj.no_sptpd,'success');
								$("#no-sptpd").val(obj.no_sptpd);
								$("#cetak").removeAttr('disabled');								
							} else {
								notif('Notifikasi',obj.error_msg,'error');	
							}
							//$('#reset').click();														
  					});
		}
	});
	
	$('#resetfrm').click(function(){
		$('#frm-restoran-sptpd')[0].reset();
		$("#no-skp").val('');		
		//$("#bulan-pajak").select2("val", "");	
		$("#proses-sptpd-restoran").removeAttr('disabled');
		$("#nop").removeAttr('readonly');
		$("#cetak").attr("disabled", "disabled");
		$("#bulan-pajak").select2('val', $("#bulan-pajak").val());
		$("#jenis-pajak").select2('val', $("#jenis-pajak").val());
		//alert($("#bulan-pajak").val());
	});
				
	//$("#bulan-pajak").select2("val", "");
	$("#dasar-pengenaan-pajak").numeric();
	$("#cetak").attr("disabled", "disabled");
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">SPTPD</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>				
<form id="frm-restoran-sptpd" name="frm-restoran-sptpd" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SPTPD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-sptpd" name="no-sptpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor SPTPD" readonly="readonly">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tahun Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tahun-pajak" id="tahun-pajak" class="select2_single" style="width:200px">
			<option value= <?php 
  	$thn = date('Y')+1;
  while($thn >= '2018') {
  	if($thn == date('Y')) {
    	echo "<option selected=\"selected\" value=\"$thn\">$thn</option>";
	} else {
		echo "<option value=\"$thn\">$thn</option>";
	}
   	$thn--; 
   } 
   ?></option>
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
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="jenis-pajak" id="jenis-pajak" class="select2_single" style="width:200px">
			<option value="01" >PAJAK AIR TANAH</option>
			<option value="03">PAJAK RESTORAN</option>			
			<option value="04" selected="selected">PAJAK PENERANGAN JALAN</option>
			<option value="05">PAJAK PARKIR</option>
			<option value="06">PAJAK HIBURAN</option>
			<option value="07" >PAJAK HOTEL</option>
			<option value="08">PAJAK MINERAL BUKAN LOGAM DAN BATUAN</option>
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
    	<input type="text" id="nama-usaha" name="nama-usaha" class="form-control col-md-7 col-xs-12" placeholder="Nama Usaha" readonly="readonly">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Usaha</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="alamat-usaha" name="alamat-usaha" class="form-control col-md-7 col-xs-12" placeholder="Alamat Usaha" readonly="readonly">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tarif</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tarif" id="tarif" class="select2_single" style="width:200px">
		<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT * FROM public.tarif WHERE kd_obj_pajak='05' ORDER BY kd_obj_pajak,kd_tarif";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				$persen_tarif = $row['tarif'] * 100;
				echo "<option value=\"".$row['tarif']."\">".$persen_tarif."%</option>";				
			}
			pg_free_result($tampil);
			pg_close($dbconn);
		?>
		</select>
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Dasar Pengenaan Pajak</label>
    	<div class="col-md-3 col-sm-6 col-xs-12">
    		<input type="text" id="dasar-pengenaan-pajak" name="dasar-pengenaan-pajak" class="form-control col-md-7 col-xs-12" placeholder="Dasar Pengenaan Pajak">
    	</div>
</div>

<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Penyampaian</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tanggal-penyampaian" name="tanggal-penyampaian" class="form-control has-feedback-left" placeholder="Tanggal Penyampaian" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
</div>	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-sptpd-restoran" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>	
			<button id="cetak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SPTPD</button>		
        </div>
    </div>
</form>