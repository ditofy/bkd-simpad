<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<script>
function load_content_tab(lk,fil) {
$('#contenttab').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
$.ajax({
                	type: "GET",
                	url: "modul/retribusi/"+lk,
                	data: 'fil='+fil,
               		success: function(data){
						//$('#contenttab').hide();
						$('#contenttab').html(data);	
						//$('#contenttab').fadeIn("fast");
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#contenttab').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						$('#contenttab').fadeIn("fast");
      				}
            	});
}

</script>
<div class="">
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			
				<h2>Laporan Pendapatan Asli Daerah</h2>
			<div class="x_title">
			</div>
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
                                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" onClick="load_content_tab('laporan_inner.php','')">Laporan Per OPD (INNER)</a>
                                            </li>
											<li role="presentation"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" onClick="load_content_tab('laporan_kota.php','')">Laporan Kota (DIGIT)</a>
                                            </li>
											<li role="presentation"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" onClick="load_content_tab('laporan_kota_new.php','')">Laporan Kota </a>
                                            </li>
                                           
											
                                        </ul>
										<div id="contenttab">
										<?php include_once $_SESSION['base_dir']."modul/retribusi/laporan_inner.php"; ?>
										</div>
			</div>
			
		</div>
	</div>
</div>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>
</div>
