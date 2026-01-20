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
	$("#nop").select2("val", "");
	$("#tanda-tangan").select2("val", "");	
				
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
			$.post( "modul/galianc/gen_pdf_sptpd.php", { no_sptpd: $('#no-sptpd').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});
	
	
	$('#npwpd').autocomplete({
      			source: "inc/get_npwpd.php",
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#npwpd").attr("readonly", "readonly");
				$.ajax({
                	type: "GET",
                	url: "inc/get_detail_wp.php",
                	data: 'search='+ui.item.value,
               		success: function(data){
						var obj = jQuery.parseJSON(data);
						$('#nik').val(obj[0].nik);
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});			
	
	
	$('#nop').autocomplete({
      			source: "modul/galianc/get_nop.php",
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#nop").attr("readonly", "readonly");
				$.ajax({
                	type: "GET",
                	url: "modul/galianc/get_detail_objek.php",
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
	
	$('#proses-sptpd-galianc').click(function(){
		if( $('#nop').val() == '' || $('#npwpd').val() == '' || $('#nama-usaha').val() == '' || $('#alamat-usaha').val() == '' || $('#tanggal-penyampaian').val() == '' || $('#v-kerikil').val() == '' || $('#v-pasir-pasang').val() == '' || $('#v-pasir-urug').val() == '' || $('#v-sirtu').val() == '' || $('#tanda-tangan').val() == '' ) {
			notif('Notifikasi','Isi Semua Data','error');
		} else {
			$("#proses-sptpd-galianc").attr("disabled", "disabled");
			var inputs = $('#frm-galianc-sptpd').serializeArray();
					$.post( "modul/galianc/penyampaian_p_simpan_sptpd.php", { postdata: inputs })
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
<form id="frm-galianc-sptpd" name="frm-galianc-sptpd" data-parsley-validate class="form-horizontal form-label-left">
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
			<option value="<?php 
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
    	<select name="jenis-pajak" id="jenis-pajak" class="select2_single" style="width:400px">
			<option value="01" >PAJAK AIR TANAH</option>
			<option value="03">PAJAK RESTORAN</option>			
			<option value="04">PAJAK PENERANGAN JALAN</option>
			<option value="05" >PAJAK PARKIR</option>
			<option value="06">PAJAK HIBURAN</option>
			<option value="07" >PAJAK HOTEL</option>
			<option value="08" selected="selected">PAJAK MINERAL BUKAN LOGAM DAN BATUAN</option>
		</select>
    </div>
</div>


<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPWPD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak Daerah">
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
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Objek Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="nop" id="nop" class="select2_single" style="width:500px">
		<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT * FROM galianc.dat_obj_pajak WHERE kd_obj_pajak='08' ORDER BY kd_keg_usaha ASC";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				
				echo "<option value=\"".$row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_obj_pajak'].".".$row['kd_keg_usaha'].".".$row['no_reg']."\">".$row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_obj_pajak'].".".$row['kd_keg_usaha'].".".$row['no_reg']."&nbsp;&nbsp;---&nbsp;&nbsp;".$row['nm_usaha'].".</option>";				
			}
			pg_free_result($tampil);
			//pg_close($dbconn);
		?>
		</select>
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pekerjaan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nama-usaha" name="nama-usaha" class="form-control col-md-7 col-xs-12" placeholder="Nama Usaha" >
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Pekerjaan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="alamat-usaha" name="alamat-usaha" class="form-control col-md-7 col-xs-12" placeholder="Alamat Usaha" >
    </div>
	
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Objek 1</label>
    <div class="col-md-3 col-sm-3 col-xs-6">
    	<input type="text" id="kerikil" name="kerikil" class="form-control col-md-7 col-xs-12" placeholder="Batu Kerikil" readonly="readonly">
    </div>
	<div class="col-md-3 col-sm-3 col-xs-6">
    	<input type="text" id="v-kerikil" name="v-kerikil" class="form-control col-md-7 col-xs-12" placeholder="Volume Batu Kerikil / M3">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Objek 2</label>
    	<div class="col-md-3 col-sm-3 col-xs-6">
    	<input type="text" id="pasir-pasang" name="pasir-pasang" class="form-control col-md-7 col-xs-12" placeholder="Pasir Pasang" readonly="readonly">
    </div>
	<div class="col-md-3 col-sm-3 col-xs-6">
    	<input type="text" id="v-pasir-pasang" name="v-pasir-pasang" class="form-control col-md-7 col-xs-12" placeholder="Volume Pasir Pasang / M3">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Objek 3</label>
    	<div class="col-md-3 col-sm-3 col-xs-6">
    	<input type="text" id="pasir-urug" name="pasir-urug" class="form-control col-md-7 col-xs-12" placeholder="Pasir Urug" readonly="readonly">
    </div>
	<div class="col-md-3 col-sm-3 col-xs-6">
    	<input type="text" id="v-pasir-urug" name="v-pasir-urug" class="form-control col-md-7 col-xs-12" placeholder="Volume Pasir Urug / M3">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Objek 4</label>
    	<div class="col-md-3 col-sm-3 col-xs-6">
    	<input type="text" id="sirtu" name="sirtu" class="form-control col-md-7 col-xs-12" placeholder="Sirtu" readonly="readonly">
    </div>
	<div class="col-md-3 col-sm-3 col-xs-6">
    	<input type="text" id="v-sirtu" name="v-sirtu" class="form-control col-md-7 col-xs-12" placeholder="Volume Sirtu / M3">
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
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pejabat </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tanda-tangan" id="tanda-tangan" class="select2_single"  style="width:400px">
			<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql_a = pg_query("SELECT A.nip,B.jabatan FROM public.tt2 A INNER JOIN public.user B ON B.nip=A.nip");
			//$tampil_a = pg_query($sql_a) or die('Query failed: ' . pg_last_error());
			while ($row_a = pg_fetch_array($sql_a))
			{
				echo "<option value=\"".$row_a['nip']."\">".$row_a['jabatan']."</option>";				
			}
			pg_free_result($sql_a);
			pg_close($dbconn);
			?>
			</select>
    </div>
</div>
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-sptpd-galianc" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>	
			<button id="cetak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SPTPD</button>		
        </div>
    </div>
</form>