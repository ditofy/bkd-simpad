<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
if ($_SESSION['level'] != '0')
{
	die('Hak Akses Diberhentikan');
}
?>
<script>
function manipulasi(aksi,key){
	$.ajax({
                	type: "GET",
                	url: "modul/user/manipulasi.php",
                	data: 'aksi='+aksi+'&key='+key,
               		success: function(data){
						notif('Notifikasi',data,'success');
						load_content('modul/user/manage.php');
                	}
            });
};

function load_content_tab(lk) {
$.ajax({
                	type: "GET",
                	url: "modul/user/"+lk,
                	data: '',
               		success: function(data){
						$('#contenttab').hide();
						$('#contenttab').html(data);	
						$('#contenttab').fadeIn("fast");
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
			
				<h2>Manage User</h2>
			<div class="x_title">
			</div>
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
                                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" onClick="load_content_tab('list_user.php')">List</a>
                                            </li>
                                            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab"  aria-expanded="true" onClick="load_content_tab('tambah_user.php')">Tambah User</a>
                                            </li>
											<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab"  aria-expanded="true" onClick="load_content_tab('menu_user.php')">Menu User</a>
                                            </li>
                                        </ul>
										<div id="contenttab">
										<?php include_once $_SESSION['base_dir']."modul/user/list_user.php"; ?>
										</div>
			</div>
			
		</div>
	</div>
</div>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>
</div>
