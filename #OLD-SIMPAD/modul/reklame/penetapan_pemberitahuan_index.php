<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
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
			$('#content-pemberitahuan').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			if ($("#jenis-penetapan").val() == '01') {
				lkcnt = 'penetapan_pemberitahuan_terseleksi.php';
			} else {
				lkcnt = 'penetapan_pemberitahuan_masal.php';
			}
			$.ajax({
                	type: "GET",
                	url: "modul/reklame/"+lkcnt,                	
               		success: function(data){						
						$('#content-pemberitahuan').html(data);	
						$('#content-pemberitahuan').fadeIn("fast");
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#content-pemberitahuan').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						$('#content-pemberitahuan').fadeIn("fast");
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
<div id="content-pemberitahuan">
</div>
    