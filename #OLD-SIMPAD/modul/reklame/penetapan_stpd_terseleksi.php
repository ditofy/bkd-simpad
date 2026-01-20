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
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
	
	$('#nop').autocomplete({
      			source: "modul/reklame/get_nop_tagihan.php",
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#nop").attr("readonly", "readonly");
				$.ajax({
                	type: "GET",
                	url: "modul/reklame/get_detail_objek_tagihan.php",
                	data: 'search='+ui.item.value,
               		success: function(data){
						var obj = jQuery.parseJSON(data);
						$('#npwpd').val(obj[0].npwpd);
						$('#nik').val(obj[0].nik);
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);
						$('#nama-usaha').val(obj[0].nm_reklame);
						$('#alamat-usaha').val(obj[0].alamat_reklame);
						$('#panjang').val(obj[0].p);
						$('#lebar').val(obj[0].l);
						$('#sisi').val(obj[0].s);
						$('#jumlah').val(obj[0].jlh);
						$('#tmt-awal').val(obj[0].tmt_aw);
						$('#tmt-akhir').val(obj[0].tmt_ak);
						$('#tarif').val(obj[0].tarif);	
						$('#pokok-pajak').val(obj[0].pokok_pajak);	
						$('#lama-pasang').val(obj[0].lama_pasang);												
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});	
	
	$("#tmt-awal").inputmask();
	$("#tmt-akhir").inputmask();
	$("#tgl-penetapan").inputmask();		
	$('#tgl-penetapan').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
	
	$('#proses-stpd-reklame').click(function(){
		if( $('#nop').val() == '' || $('#npwpd').val() == '' || $('#tarif').val() == null || $('#lama-pasang').val() == '' || $('#tmt-awal').val() == '' || $('#tmt-akhir').val() == '' || $('#tanda-tangan').val() == null || $('#tgl-penetapan').val() == '' ) {
			notif('Notifikasi','Isi Semua Data','error');
		} else {
			$("#proses-stpd-reklame").attr("disabled", "disabled");
			var inputs = $('#frm-reklame-stpd').serializeArray();
					$.post( "modul/reklame/penetapan_p_simpan_stpd.php", { postdata: inputs })
  						.done(function( data1 ) {
							var obj = jQuery.parseJSON(data1);							
								if( obj.status == "ok") {
								notif('Notifikasi','STPD Berhasil Disimpan No : '+obj.no_skp,'success');
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
		$('#frm-reklame-stpd')[0].reset();
		$("#no-stpd").val('');		
		$("#tanda-tangan").select2("val", "");
		$("#cetak").attr("disabled", "disabled");
		$("#proses-stpd-reklame").removeAttr('disabled');
		$("#nop").removeAttr('readonly');
	});
	
	$('#cetak').click(function(){
		$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
			$.post( "modul/reklame/gen_pdf_stpd.php", { no_skp: $('#no-stpd').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});
			
	
	$("#tanda-tangan").select2("val", "");
	$("#cetak").attr("disabled", "disabled");
	$("#lama-pasang").numeric();
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">STPD</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
								
<form id="frm-reklame-stpd" name="frm-reklame-stpd" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No STPD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-stpd" name="no-stpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor STPD" readonly="readonly">
    </div>
</div>



<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NOP</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak">
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
        	<input type="text" id="tgl-penetapan" name="tgl-penetapan" class="form-control has-feedback-left" placeholder="Tanggal Penetapan" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
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
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Reklame</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nama-usaha" name="nama-usaha" class="form-control col-md-7 col-xs-12" placeholder="Nama Reklame" readonly="readonly">
    </div>


</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Reklame</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="alamat-usaha" name="alamat-usaha" class="form-control col-md-7 col-xs-12" placeholder="Alamat Reklame" readonly="readonly">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ukuran</label>
    	<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="panjang" name="panjang" class="form-control col-md-7 col-xs-12" placeholder="Pjg" readonly="readonly">
    	</div>
		<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="lebar" name="lebar" class="form-control col-md-7 col-xs-12" placeholder="Lebar" readonly="readonly">
    	</div>
		<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="sisi" name="sisi" class="form-control col-md-7 col-xs-12" placeholder="Sisi" readonly="readonly">
    	</div>
		<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="jumlah" name="jumlah" class="form-control col-md-7 col-xs-12" placeholder="Jumlah" readonly="readonly">
    	</div>
    </div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tarif</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="tarif" name="tarif" class="form-control col-md-7 col-xs-12" placeholder="Tarif" readonly="readonly">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pokok Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="pokok-pajak" name="pokok-pajak" class="form-control col-md-7 col-xs-12" placeholder="Pokok Pajak" readonly="readonly">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Lama Pemasangan</label>
    	<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="lama-pasang" name="lama-pasang" class="form-control col-md-7 col-xs-12" placeholder="Lama" readonly="readonly">
    	</div>
</div>
<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">TMT Awal</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tmt-awal" name="tmt-awal" class="form-control has-feedback-left" placeholder="TMT Awal" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'" readonly="readonly">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">TMT Akhir</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tmt-akhir" name="tmt-akhir" class="form-control has-feedback-left" placeholder="TMT Akhir" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'" readonly="readonly">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>	
	</div>

	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-stpd-reklame" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>
			<button id="cetak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak STPD</button>			
        </div>
    </div>
</form>