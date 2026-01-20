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
                	url: "modul/parkir/"+lk,
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
			
				<h2>Pajak Parkir</h2>
			<div class="x_title">
			</div>
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
                                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" onClick="load_content_tab('pendaftaran.php','')">Pendaftaran</a>
                                            </li>
											<li role="presentation"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" onClick="load_content_tab('update_data.php','')">Update Data</a>
                                            </li>
                                            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab"  aria-expanded="true" onClick="load_content_tab('list_index.php','')">List Data</a>
                                            </li>
											<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab"  aria-expanded="true" onClick="load_content_tab('mutasi_nop.php','')">Mutasi NOP</a>
                                            </li>
											<li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab" data-toggle="tab"  aria-expanded="true" onClick="load_content_tab('hapus_nop.php','')">Hapus NOP</a>
                                            </li>
                                        </ul>
										<div id="contenttab">
										<?php include_once $_SESSION['base_dir']."modul/parkir/pendaftaran.php"; ?>
										</div>
			</div>
			
		</div>
	</div>
</div>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>
</div>
