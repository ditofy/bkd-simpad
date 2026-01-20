<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Silahkan Login');
	exit;
}
?>
<script>
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
	$('#jenis-penetapan').change(function(){
		if ($("#jenis-penetapan").val() != null) {
			var lkcnt = '';
			$('#content-skp').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			if ($("#jenis-penetapan").val() == '01') {
				lkcnt = 'penetapan_skpdn_terseleksi.php';
			} else {
				lkcnt = 'penetapan_skpdn_masal.php';
			}
			$.ajax({
                	type: "GET",
                	url: "modul/skpd_nihil/"+lkcnt,                	
               		success: function(data){						
						$('#content-skp').html(data);	
						$('#content-skp').fadeIn("fast");
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#content-skp').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						$('#content-skp').fadeIn("fast");
      				}
            	});
		}	
	});	
	$("#jenis-penetapan").select2("val", "");
});
</script>				
<select name="jenis-penetapan" id="jenis-penetapan" class="select2_single" style="width:200px">
	<option value="01">Terseleksi</option>
	<option value="02">Masal</option>			
</select>
<div class="x_title">
</div>
<div id="content-skp">
</div>
    