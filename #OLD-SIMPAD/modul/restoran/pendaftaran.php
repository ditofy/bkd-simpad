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
function get_kel(kd) {
	if(kd != ""){
	$.ajax({
                	type: "GET",
                	url: "inc/get_kel.php",
                	data: 'kd='+kd,
               		success: function(data){
						$('#kd-kelurahan').html(data);
						$("#kd-kelurahan").select2("val", "");
                	}
            });	
	}		
};

$(document).ready(function () {
                $(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
                $(".select2_group").select2({});
                $(".select2_multiple").select2({
                    maximumSelectionLength: 4,
                    placeholder: "With Max Selection limit 4",
                    allowClear: true
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
			
			$('#resetfrm').click(function(){
				event.preventDefault();
				$('#frm-daftar-resto')[0].reset();
				$("#npwpd").removeAttr('readonly');
				$("#kd-kecamatan").select2("val", "");
				$("#kd-kelurahan").select2("val", "");
				$("#kd-keg-usaha").select2("val", "");
				$("#jenis-penetapan").select2("val", "");
				$("#kd-kelurahan").html("");
			});
			
			$('#tgl-daftar').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#proses-daftar-resto').click(function(){
if($('#npwpd').val() == '' || $('#kd-kecamatan').val() == null || $('#kd-kelurahan').val() == null || $('#kd-keg-usaha').val() == null || $('#nama-usaha').val() == '' || $('#alamat-usaha').val() == '' || $('#jenis-penetapan').val() == null || $('#tgl-daftar').val() == ''){
	notif('Notifikasi','Isi Semua Data','error');			
} else {
	$("#proses-daftar-resto").attr("disabled", "disabled");
	var inputs = $('#frm-daftar-resto').serializeArray();
					$.post( "modul/restoran/p_tambah.php", { postdata: inputs })
  						.done(function( data1 ) {
   							notif('Notifikasi',data1,'success');
							$('#resetfrm').click();
							$("#kd-kecamatan").select2("val", "");
							$("#kd-kelurahan").select2("val", "");
							$("#kd-keg-usaha").select2("val", "");
							$("#jenis-penetapan").select2("val", "");
							$("#proses-daftar-resto").removeAttr('disabled');
  					});
}	
});
			
			
				
			$("#kd-kecamatan").select2("val", "");
			$("#kd-keg-usaha").select2("val", "");
			$("#jenis-penetapan").select2("val", "");	
});
</script>
<form id="frm-daftar-resto" name="frm-daftar-resto" data-parsley-validate class="form-horizontal form-label-left">
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPWPD
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak Daerah">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-wp" name="nama-wp" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Nama Wajib Pajak">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="alamat" name="alamat" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Alamat Wajib Pajak">
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nik" name="nik" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Nomor Induk Kependudukan">
    	</div>
    </div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kecamatan
        </label>
       	<div class="col-md-6 col-sm-6 col-xs-12">
			<select name="kd-kecamatan" id="kd-kecamatan" class="select2_single" style="width:300px" onchange="get_kel($('#kd-kecamatan').val());">
			<?php 
			$sql = "SELECT kd_kecamatan||' - '||nm_kecamatan as value FROM public.kecamatan";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
			
				echo "<option value=\"".$row['value']."\">".$row['value']."</option>";
			}
			pg_free_result($tampil);
			?>
			</select>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kelurahan
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="kd-kelurahan" id="kd-kelurahan" class="select2_single" style="width:300px">
			</select>
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Objek Pajak
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="kd-obj-pajak" name="kd-obj-pajak" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="03 - RESTORAN">
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kegiatan Usaha
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="kd-keg-usaha" id="kd-keg-usaha" class="select2_single" style="width:300px">
			<?php 
			$sql = "SELECT kd_keg_usaha||' - '||nm_keg_usaha AS value FROM public.keg_usaha WHERE kd_obj_pajak='03'";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
			
				echo "<option value=\"".$row['value']."\">".$row['value']."</option>";
			}
			pg_free_result($tampil);
			?>
			</select>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Restoran
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-usaha" name="nama-usaha" class="form-control col-md-7 col-xs-12" placeholder="Nama Usaha">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Restoran
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="alamat-usaha" name="alamat-usaha" class="form-control col-md-7 col-xs-12" placeholder="Alamat Usaha">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Penetapan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<select name="jenis-penetapan" id="jenis-penetapan" class="select2_single" style="width:230px">
				<option value="01 - WARALABA">01 - WARALABA</option>
				<option value="02 - NON WARALABA">02 - NON WARALABA</option>
				<option value="03 - DATA LAMA">03 - DATA LAMA</option>
			</select>
    	</div>
    </div>
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Pendaftaran</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-daftar" name="tgl-daftar" class="form-control has-feedback-left" placeholder="Tanggal Pendaftaran" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
	</div>
	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-daftar-resto" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
        </div>
    </div>
</form>
<?php
pg_close($dbconn);
?>