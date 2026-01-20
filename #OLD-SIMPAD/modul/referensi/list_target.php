<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
if ($_SESSION['level'] != '0')
{
	die('Hak Akses Diberhentikan');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$thn_pajak =  pg_escape_string($_GET['thn_pajak']);
$query = "SELECT A.kd_obj_pajak,A.nm_obj_pajak,B.target FROM public.obj_pajak A LEFT OUTER JOIN public.target B ON A.kd_obj_pajak=B.kd_obj_pajak AND B.thn_pajak='$thn_pajak' ORDER BY A.kd_obj_pajak";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
?>
<script src="js/jquery.numeric.min.js"></script>
<script>
$(document).ready(function () {
	$('#update-target').click(function(){
		$("#update-target").attr("disabled", "disabled");
		var target = $('#frm-target').serializeArray();
		$.post( "modul/referensi/update_target.php", { postdata: target, tahun_pajak: $( "#thn-pajak" ).val() })
  						.done(function( data ) {
   							notif('Notifikasi',data,'success');
							load_list_target();							
  					});
	});
	$( ":input" ).numeric();
});

</script>
<form id="frm-target">
<table class="table table-hover">
		<thead>
			<tr>
				<th>No</th>
				<th>JENIS PAJAK</th>
				<th>TARGET</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$no = 1;
			while ($row = pg_fetch_array($result)){			
				?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $row['nm_obj_pajak']; ?></td>
						<td><?php echo "<input type=\"text\" id=\"".$row['kd_obj_pajak']."\" name=\"".$row['kd_obj_pajak']."\" class=\"form-control col-md-7 col-xs-12\" value=\"".$row['target']."\">"; ?></td>
					</tr>
				<?php
				$no++;
			}
		?>
		<tr>
			<td colspan="3" align="center"><button type="button" class="btn btn-primary" id="update-target">Update</button></td>
		</tr>
		</tbody>
</table>

</form>
<?php
pg_free_result($result);
pg_close($dbconn);
?>
