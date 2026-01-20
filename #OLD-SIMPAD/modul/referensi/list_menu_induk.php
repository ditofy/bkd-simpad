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
?>

			<?php
			$query = "SELECT * FROM public.menu WHERE sub_id=0 ORDER BY menu_id";
			$result = pg_query($query) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($result))
			{
				echo "<option value=\"".$row['menu_id'].".".$row['nm_menu']."\">".$row['menu_id'].".".$row['nm_menu']."</option>";	
			}
			?>

<?php
pg_free_result($result);
pg_close($dbconn);
?>