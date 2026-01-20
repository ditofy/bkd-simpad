<link href="css/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">
<?php
if (!isset($_SESSION)) { session_start(); }
/*if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}*/
$bdir = pg_escape_string($_GET['bdir']);
include $bdir."inc/function.php";
include $bdir."inc/conn.php";

$conn = conn();
$date_start=pg_escape_string(date("Y-m-d",strtotime($_GET['tgl-awal'])));
$date_end = pg_escape_string(date("Y-m-d",strtotime($_GET['tgl-akhir'])));

?>
<div class="x_content">
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;" border="1">										
                                        <thead>
											<tr class="headings">
											 <td colspan="9" align="center"><h3>DATA PEMBAYARAN PAJAK DAERAH  <?php echo $row_obj['nm_obj_pajak']; ?> TANGGAL: <?php echo $date_start; ?> s.d <?php echo $date_end; ?></h3></td>
											 </tr>
                                            <tr>
                                <td class="text-center">Tax Type</td>
                                <td class="text-center">Nomor</td>
                                <td class="text-center">Outlet ID</td>
                                <td class="text-center">Billing Number</td>
                                <td class="text-center">Amount</td>
                                <td class="text-center">Reference Number</td>
                                <td class="text-center">Billing Date</td>
                                <td class="text-center">PJSP</td>
                                <td class="text-center">Customer Name</td>
                              
                            </tr>
                            </thead><tbody>
	<?php  if (! validateDate($date_start) || ! validateDate($date_end)) {
        echo "Format tanggal tidak valid.<br>";
    } else {
        $rows = select($conn, "SELECT t.*, 
       		(SELECT CASE 
                   WHEN COUNT(*) > 1 THEN 'D' 
                   ELSE 'U' 
                END
        	FROM qris.trx 
        	WHERE nop = t.nop) AS mark
		FROM qris.trx AS t
		WHERE DATE(t.billing_date) >= ? 
  			AND DATE(t.billing_date) <= ?
		ORDER BY t.billing_date DESC",
        [
            $date_start,
            $date_end,
        ]);
		
		 foreach ($rows as $row) {
        $mark = ($row['mark'] == 'D') ? 'bg-danger' : '';
						?>				

<tr class="even pointer">
                <td class="<?php echo $mark ?> text-center"><?php echo $row['tax_type']; ?></td>
                <td class="<?php echo $mark ?>"><?php echo $row['nop']; ?></td>
                <td class="<?php echo $mark ?> text-center"><?php echo $row['outlet_id']; ?></td>
                <td class="<?php echo $mark ?> text-center"><?php echo $row['billing_number']; ?></td>
                <td class="<?php echo $mark ?> text-end"><?php echo number_format($row['amount'], 2, ',', '.'); ?></td>
                <td class="<?php echo $mark ?> text-center"><?php echo $row['reference_number']; ?></td>
                <td class="<?php echo $mark ?> text-center"><?php echo $row['billing_date']; ?></td>
                <td class="<?php echo $mark ?>"><?php echo $row['pjsp']; ?></td>
                <td class="<?php echo $mark ?>"><?php echo $row['customer_name']; ?></td>
                                                              
                                               
                                            </tr>
<?php
}
}
?>

</tbody>

                                    </table>
                                </div>
