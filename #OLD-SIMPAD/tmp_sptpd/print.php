<?php
require 'config.php';
require 'vendor/autoload.php';

use \Milon\Barcode\DNS2D;

$conn = conn();

$id_kecamatan = $_GET['id_kecamatan'] ?? '';
$id_kelurahan = $_GET['id_kelurahan'] ?? '';

$akun = '411312';

$stmt = $conn->prepare("SELECT 
    A.KD_PROPINSI || '.' || A.KD_DATI2 || '.' || A.KD_KECAMATAN || '.' || A.KD_KELURAHAN || '.' || A.KD_BLOK || '.' || A.NO_URUT || '.' || A.KD_JNS_OP AS NOP,
    A.KD_KECAMATAN,
    A.KD_KELURAHAN,
    A.KD_BLOK,
    A.NO_URUT,
    A.KD_JNS_OP,
    A.NM_WP_SPPT,
    A.JLN_WP_SPPT,
    A.BLOK_KAV_NO_WP_SPPT,
    A.RT_WP_SPPT,
    A.RW_WP_SPPT,
    A.KELURAHAN_WP_SPPT,
    A.KOTA_WP_SPPT,
    A.THN_PAJAK_SPPT,
    E.JALAN_OP,
    E.RT_OP,
    E.RW_OP,
    E.BLOK_KAV_NO_OP AS NOMOR,
    A.LUAS_BUMI_SPPT,
    A.LUAS_BNG_SPPT,
    A.KD_KLS_TANAH,
    B.NILAI_PER_M2_TANAH,
    X.KD_KLS_BNG,
    X.NILAI_PER_M2_BNG,
    A.NJOP_BUMI_SPPT,
    A.NJOP_BNG_SPPT,
    A.NJOPTKP_SPPT,
    A.NJOP_SPPT,
    C.NM_KECAMATAN,
    D.NM_KELURAHAN,
    TO_CHAR(A.TGL_JATUH_TEMPO_SPPT, 'DD MON YYYY') TGL_JATUH_TEMPO_SPPT,
    TO_CHAR(A.TGL_TERBIT_SPPT, 'DD MON YYYY') TGL_TERBIT_SPPT,
    PBB_YG_HARUS_DIBAYAR_SPPT
FROM PBB.SPPT A 
INNER JOIN PBB.KELAS_TANAH B ON A.KD_KLS_TANAH = B.KD_KLS_TANAH
INNER JOIN PBB.KELAS_BANGUNAN X ON A.KD_KLS_BNG = X.KD_KLS_BNG
INNER JOIN PBB.REF_KECAMATAN C ON A.KD_KECAMATAN = C.KD_KECAMATAN
INNER JOIN PBB.REF_KELURAHAN D ON A.KD_KECAMATAN = D.KD_KECAMATAN AND A.KD_KELURAHAN = D.KD_KELURAHAN
INNER JOIN PBB.DAT_OBJEK_PAJAK E ON A.KD_PROPINSI = E.KD_PROPINSI 
    AND A.KD_DATI2 = E.KD_DATI2 
    AND A.KD_KECAMATAN = E.KD_KECAMATAN 
    AND A.KD_KELURAHAN = E.KD_KELURAHAN 
    AND A.KD_BLOK = E.KD_BLOK 
    AND A.NO_URUT = E.NO_URUT
    AND A.KD_JNS_OP = E.KD_JNS_OP
WHERE 
    A.KD_KECAMATAN = ? AND
    A.KD_KELURAHAN = ? AND
    A.THN_PAJAK_SPPT = ?
ORDER BY 
    A.KD_PROPINSI,
    A.KD_DATI2,
    A.KD_KECAMATAN,
    A.KD_KELURAHAN,
    A.KD_BLOK,
    A.NO_URUT,
    A.KD_JNS_OP
");
$stmt->execute([
    $id_kecamatan,
    $id_kelurahan,
    constant('TAHUN')
]);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Cetak PBB</title>
<style>
@page {
  size: 14in 11in portrait;
}

@media print {
  html, body {
    width: 14in;
    margin: 0;
    padding: 0;
  }

  .pagebreak {
    clear: both;
    page-break-after: always;
  }
}

html {
  margin: 0;
  padding: 0;
}

body {
  width: 14in;
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
  font-size: 14px;
}

table, th, td {
  border: 0px solid black;
  border-collapse: collapse;
  margin: 0;
  padding: 0;
  vertical-align: top;
}

.relative {
  position: relative;
}

.absolute {
  position: absolute;
}
</style>

</head>
<body>
<?php
$dns2d = new DNS2D();
$dns2d->setStorPath(__DIR__.'/cache/');
$no = 1;
foreach($results as $row) {
    if($no % 2 !== 0)
    {
        ?>
        <div style="width:50%; float:left;" class="relative">
            <div class="absolute" style="top:22cm; left:8.5cm; z-index:999;">
                <?php
                $dataQR = constant('SPPT_URL') . '/' . $row['NOP'];
                ?>
                <img src="data:image/png;base64,<?php echo $dns2d->getBarcodePNG($dataQR, 'QRCODE', 2, 2); ?>" style="">
            </div>
            <div class="absolute" style="top:22cm; left:11cm; z-index:999;">
                <img src="cap.png" style="width:3cm;">
            </div>
            <table style="width:100%;">
                <tr><td colspan="2" style="padding-top:1.5cm; padding-left:15cm;"><?php echo $akun ?></td></tr>
                <tr><td colspan="2" style="padding-top:0.2cm; padding-left:12.5cm;"><?php echo $row['THN_PAJAK_SPPT']; ?></td></tr>
                <tr>
                    <td style="width:50%; padding-top:0cm; padding-left:1cm;"><?php echo $row['NOP']; ?></td>
                    <td style="width:50%; padding-top:0cm; padding-right:0.5cm; text-align:right;"></td>
                </tr>
            </table>

            <table style="width:100%; margin-top:0.5cm;">
                <tr>
                    <td style="width:50%; padding-top:0cm; padding-left:0.0cm;"><?php echo $row['JALAN_OP']; ?></td>
                    <td style="width:50%; padding-top:0cm; padding-left:0.3cm;"><?php echo $row['NM_WP_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="padding-top:0cm; padding-left:0.0cm;">RT. <?php echo $row['RT_OP']; ?> RW. <?php echo $row['RW_OP']; ?></td>
                    <td style="padding-top:0cm; padding-left:0.3cm;"><?php echo $row['JLN_WP_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="padding-top:0cm; padding-left:0.0cm;"><?php echo $row['NM_KELURAHAN']; ?></td>
                    <td style="padding-top:0cm; padding-left:0.3cm;">RT. <?php echo $row['RT_WP_SPPT']; ?> RW. <?php echo $row['RW_WP_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="padding-top:0cm; padding-left:0.0cm;"><?php echo $row['NM_KECAMATAN']; ?></td>
                    <td style="padding-top:0cm; padding-left:0.3cm;"><?php echo $row['KELURAHAN_WP_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="padding-top:0cm; padding-left:0.0m;">PAYAKUMBUH</td>
                    <td style="padding-top:0cm; padding-left:0.3cm;"><?php echo $row['KOTA_WP_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="padding-top:0cm; padding-left:0.0cm;"></td>
                    <td style="padding-top:0cm; padding-left:1.1cm;">BELUM ADA</td>
                </tr>
            </table>

            <table style="width:100%; margin-top:1cm;">
                <tr>
                    <td style="width:1.7cm; padding-top:0cm; padding-left:0.0cm;">BUMI</td>
                    <td style="width:1.7cm; padding-top:0cm; padding-right:0.5cm; text-align:right;"><?php echo nominal($row['LUAS_BUMI_SPPT']); ?></td>
                    <td style="width:1cm; padding-top:0cm; padding-left:0.5cm;"><?php echo $row['KD_KLS_TANAH']; ?></td>
                    <td style="width:2.6cm; padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NILAI_PER_M2_TANAH'] * 1000); ?></td>
                    <td style="width:3cm; padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NJOP_BUMI_SPPT']); ?></td>
                </tr>
                <tr>
                    <td style="width:1.7cm; padding-top:0cm; padding-left:0.0cm;">BANGUNAN</td>
                    <td style="width:1.7cm; padding-top:0cm; padding-right:0.5cm; text-align:right;"><?php echo nominal($row['LUAS_BNG_SPPT']); ?></td>
                    <td style="width:1cm; padding-top:0cm; padding-left:0.5cm;"><?php echo $row['KD_KLS_BNG']; ?></td>
                    <td style="width:2.6cm; padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NILAI_PER_M2_BNG'] * 1000); ?></td>
                    <td style="width:3cm; padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NJOP_BNG_SPPT']); ?></td>
                </tr>
            </table>

            <table style="width:100%; margin-top:0.8cm;">
                <tr><td style="padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NJOP_SPPT']); ?></td></tr>
                <tr><td style="padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NJOPTKP_SPPT']); ?></td></tr>
                <tr><td style="padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NJOP_SPPT'] - $row['NJOPTKP_SPPT']); ?></td></tr>
                <tr><td style="padding-top:0.5cm; padding-right:0.7cm; text-align:right;">0.1 %</td></tr>
                <tr><td style="padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td></tr>
                <tr><td style="height:2cm; padding-top:0.5cm; padding-left:0cm;"><?php echo terbilang($row['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td></tr>
            </table>

            <table style="width:100%; margin-top:0.8cm;">
                <?php
                $stmtTahun = $conn->prepare("SELECT 
                    A.THN_PAJAK_SPPT,
                    A.PBB_YG_HARUS_DIBAYAR_SPPT 
                FROM PBB.SPPT A
                WHERE
                    A.THN_PAJAK_SPPT BETWEEN '2009' AND ?
                    AND A.STATUS_PEMBAYARAN_SPPT = '0' 
                    AND A.KD_KECAMATAN = ?
                    AND A.KD_KELURAHAN = ?
                    AND A.KD_BLOK = ?
                    AND A.NO_URUT = ?
                    AND A.KD_JNS_OP = ?
                ORDER BY A.THN_PAJAK_SPPT ASC
                ");
                $stmtTahun->execute([
                    ( (int) constant('TAHUN') - 1 ),
                    $row['KD_KECAMATAN'],
                    $row['KD_KELURAHAN'],
                    $row['KD_BLOK'],
                    $row['NO_URUT'],
                    $row['KD_JNS_OP'],
                ]);
                $resultsTahun = $stmtTahun->fetchAll();

                $x = 1;
                foreach($resultsTahun as $rowTahun) {
                    ?>
                    <tr>
                        <td style="width:1.5cm; padding-top:0cm; padding-left:0.0cm;"><?php echo $rowTahun['THN_PAJAK_SPPT']; ?></td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:0.6cm; text-align:right;"><?php echo nominal($rowTahun['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:0.3cm; text-align:right;"></td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:1cm; text-align:right;"><?php echo nominal($rowTahun['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td>
                        <td style="width:4.7cm; padding-top:0cm; padding-left:0.3cm;"></td>
                    </tr>
                    <?php
                    $x++;
                }
                
                for($y=$x; $y <= 16; $y++)
                {
                    ?>
                    <tr>
                        <td style="width:1.5cm; padding-top:0cm; padding-left:0.0cm;">&nbsp;</td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:0.6cm; text-align:right;">&nbsp;</td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:0.3cm; text-align:right;">&nbsp;</td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:1cm; text-align:right;">&nbsp;</td>
                        <td style="width:4.7cm; padding-top:0cm; padding-left:0.3cm;">&nbsp;</td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <table style="width:100%; margin-top:0.5cm;">
                <tr>
                    <td style="width:10.5cm; padding-top:0cm; padding-left:3.5cm;"><?php echo $row['TGL_JATUH_TEMPO_SPPT']; ?></td>
                    <td style="width:6.5cm; padding-top:0cm; padding-left:2.5cm;"><?php echo $row['TGL_TERBIT_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="width:10.3cm; padding-top:0.6cm; padding-left:0cm;">TELLER BANK NAGARI / INTERNET BANKING</td>
                    <td style="width:6.5cm; padding-top:0cm; padding-left:0cm;"><td>
                </tr>
                <tr>
                    <td style="width:10.3cm; padding-top:0cm; padding-left:0cm;">MOBILE BANKING / SCAN UNTUK QRIS</td>
                    <td style="width:6.5cm; padding-top:0.4cm; padding-left:0cm; text-align:center;">
                        <?php echo constant('NAMA_TDD'); ?><br>
                        NIP. <?php echo constant('NIP_TDD'); ?>
                    </td>
                </tr>
            </table>

            <table style="width:11cm; margin-top:1.3cm;">
                <tr><td colspan="2" style="padding-top:0cm; padding-left:3.2cm;"><?php echo $row['NM_WP_SPPT']; ?></td></tr>
                <tr><td colspan="2" style="padding-top:0cm; padding-left:5.4cm;"><?php echo $row['NM_KECAMATAN']; ?></td></tr>
                <tr><td colspan="2" style="padding-top:0cm; padding-left:5.4cm;"><?php echo $row['NM_KELURAHAN']; ?></td></tr>
                <tr><td colspan="2" style="padding-top:0cm; padding-left:3.2cm;"><?php echo $row['NOP']; ?></td></tr>
                <tr>
                    <td style="width:50%; padding-top:0cm; padding-left:3.2cm;"><?php echo $row['THN_PAJAK_SPPT']; ?></td>
                    <td style="width:50%; padding-top:0cm; padding-right:0.3cm; text-align:right;"><?php echo nominal($row['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td>
                </tr>
            </table>
        </div>
        <?php
    }
    else
    {
        ?>
        <div style="width:50%; float:left;" class="relative">
            <div class="absolute" style="top:22cm; left:9cm; z-index:999;">
                <?php
                $dataQR = constant('SPPT_URL') . '/' . $row['NOP'];
                ?>
                <img src="data:image/png;base64,<?php echo $dns2d->getBarcodePNG($dataQR, 'QRCODE', 2, 2); ?>" style="">
            </div>
            <div class="absolute" style="top:22cm; left:11cm; z-index:999;">
                <img src="cap.png" style="width:3cm;">
            </div>
            <table style="width:100%;">
                <tr><td colspan="2" style="padding-top:1.5cm; padding-left:15.5cm;"><?php echo $akun ?></td></tr>
                <tr><td colspan="2" style="padding-top:0.2cm; padding-left:13cm;"><?php echo $row['THN_PAJAK_SPPT']; ?></td></tr>
                <tr>
                    <td style="width:50%; padding-top:0cm; padding-left:1.5cm;"><?php echo $row['NOP']; ?></td>
                    <td style="width:50%; padding-top:0cm; padding-right:0.5cm; text-align:right;"></td>
                </tr>
            </table>
            
            <table style="width:100%; margin-top:0.5cm;">
                <tr>
                    <td style="width:50%; padding-top:0cm; padding-left:0.5cm;"><?php echo $row['JALAN_OP']; ?></td>
                    <td style="width:50%; padding-top:0cm; padding-left:0.8cm;"><?php echo $row['NM_WP_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="padding-top:0cm; padding-left:0.5cm;">RT. <?php echo $row['RT_OP']; ?> RW. <?php echo $row['RW_OP']; ?></td>
                    <td style="padding-top:0cm; padding-left:0.8cm;"><?php echo $row['JLN_WP_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="padding-top:0cm; padding-left:0.5cm;"><?php echo $row['NM_KELURAHAN']; ?></td>
                    <td style="padding-top:0cm; padding-left:0.8cm;">RT. <?php echo $row['RT_WP_SPPT']; ?> RW. <?php echo $row['RW_WP_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="padding-top:0cm; padding-left:0.5cm;"><?php echo $row['NM_KECAMATAN']; ?></td>
                    <td style="padding-top:0cm; padding-left:0.8cm;"><?php echo $row['KELURAHAN_WP_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="padding-top:0cm; padding-left:0.5cm;">PAYAKUMBUH</td>
                    <td style="padding-top:0cm; padding-left:0.8cm;"><?php echo $row['KOTA_WP_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="padding-top:0cm; padding-left:0.5cm;"></td>
                    <td style="padding-top:0cm; padding-left:1.9cm;">BELUM ADA</td>
                </tr>
            </table>

            <table style="width:100%; margin-top:1cm;">
                <tr>
                    <td style="width:1.7cm; padding-top:0cm; padding-left:0.5cm;">BUMI</td>
                    <td style="width:1.7cm; padding-top:0cm; padding-right:0.5cm; text-align:right;"><?php echo nominal($row['LUAS_BUMI_SPPT']); ?></td>
                    <td style="width:1cm; padding-top:0cm; padding-left:1.0cm;"><?php echo $row['KD_KLS_TANAH']; ?></td>
                    <td style="width:2.6cm; padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NILAI_PER_M2_TANAH'] * 1000); ?></td>
                    <td style="width:3cm; padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NJOP_BUMI_SPPT']); ?></td>
                </tr>
                <tr>
                    <td style="width:1.7cm; padding-top:0cm; padding-left:0.5cm;">BANGUNAN</td>
                    <td style="width:1.7cm; padding-top:0cm; padding-right:0.5cm; text-align:right;"><?php echo nominal($row['LUAS_BNG_SPPT']); ?></td>
                    <td style="width:1cm; padding-top:0cm; padding-left:1.0cm;"><?php echo $row['KD_KLS_BNG']; ?></td>
                    <td style="width:2.6cm; padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NILAI_PER_M2_BNG'] * 1000); ?></td>
                    <td style="width:3cm; padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NJOP_BNG_SPPT']); ?></td>
                </tr>
            </table>

            <table style="width:100%; margin-top:0.8cm;">
                <tr><td style="padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NJOP_SPPT']); ?></td></tr>
                <tr><td style="padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NJOPTKP_SPPT']); ?></td></tr>
                <tr><td style="padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['NJOP_SPPT'] - $row['NJOPTKP_SPPT']); ?></td></tr>
                <tr><td style="padding-top:0.5cm; padding-right:0.7cm; text-align:right;">0.1 %</td></tr>
                <tr><td style="padding-top:0cm; padding-right:0.7cm; text-align:right;"><?php echo nominal($row['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td></tr>
                <tr><td style="height:2cm; padding-top:0.5cm; padding-left:0.5cm;"><?php echo terbilang($row['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td></tr>
            </table>

            <table style="width:100%; margin-top:0.8cm;">
            <?php
                $stmtTahun = $conn->prepare("SELECT 
                    A.THN_PAJAK_SPPT,
                    A.PBB_YG_HARUS_DIBAYAR_SPPT 
                FROM PBB.SPPT A
                WHERE
                    A.THN_PAJAK_SPPT BETWEEN '2009' AND ?
                    AND A.STATUS_PEMBAYARAN_SPPT = '0' 
                    AND A.KD_KECAMATAN = ?
                    AND A.KD_KELURAHAN = ?
                    AND A.KD_BLOK = ?
                    AND A.NO_URUT = ?
                    AND A.KD_JNS_OP = ?
                ORDER BY A.THN_PAJAK_SPPT ASC
                ");
                $stmtTahun->execute([
                    ( (int) constant('TAHUN') - 1 ),
                    $row['KD_KECAMATAN'],
                    $row['KD_KELURAHAN'],
                    $row['KD_BLOK'],
                    $row['NO_URUT'],
                    $row['KD_JNS_OP'],
                ]);
                $resultsTahun = $stmtTahun->fetchAll();

                $x = 1;
                foreach($resultsTahun as $rowTahun) {
                    ?>
                    <tr>
                        <td style="width:1.5cm; padding-top:0cm; padding-left:0.5cm;"><?php echo $rowTahun['THN_PAJAK_SPPT']; ?></td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:0.6cm; text-align:right;"><?php echo nominal($rowTahun['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:0.3cm; text-align:right;"></td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:1cm; text-align:right;"><?php echo nominal($rowTahun['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td>
                        <td style="width:4.7cm; padding-top:0cm; padding-left:0.3cm;"></td>
                    </tr>
                    <?php
                    $x++;
                }
                
                for($y=$x; $y <= 16; $y++)
                {
                    ?>
                    <tr>
                        <td style="width:1.5cm; padding-top:0cm; padding-left:0.5cm;">&nbsp;</td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:0.6cm; text-align:right;">&nbsp;</td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:0.3cm; text-align:right;">&nbsp;</td>
                        <td style="width:3.5cm; padding-top:0cm; padding-right:1cm; text-align:right;">&nbsp;</td>
                        <td style="width:4.7cm; padding-top:0cm; padding-left:0.3cm;">&nbsp;</td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <table style="width:100%; margin-top:0.5cm;">
                <tr>
                    <td style="width:10.5cm; padding-top:0cm; padding-left:4cm;"><?php echo $row['TGL_JATUH_TEMPO_SPPT']; ?></td>
                    <td style="width:6.5cm; padding-top:0cm; padding-left:3cm;"><?php echo $row['TGL_TERBIT_SPPT']; ?></td>
                </tr>
                <tr>
                    <td style="width:10.3cm; padding-top:0.6cm; padding-left:0.5cm;">TELLER BANK NAGARI / INTERNET BANKING</td>
                    <td style="width:6.5cm; padding-top:0cm; padding-left:0.5cm;"></td>
                </tr>
                <tr>
                    <td style="width:10.3cm; padding-top:0cm; padding-left:0.5cm;">MOBILE BANKING / SCAN UNTUK QRIS</td>
                    <td style="width:6.5cm; padding-top:0.4cm; padding-left:0.5cm; text-align:center;">
                        <?php echo constant('NAMA_TDD'); ?><br>
                        NIP. <?php echo constant('NIP_TDD'); ?>
                    </td>
                </tr>
            </table>
            
            <table style="width:11cm; margin-top:1.3cm;">
                <tr><td colspan="2" style="padding-top:0cm; padding-left:3.7cm;"><?php echo $row['NM_WP_SPPT']; ?></td></tr>
                <tr><td colspan="2" style="padding-top:0cm; padding-left:5.9cm;"><?php echo $row['NM_KECAMATAN']; ?></td></tr>
                <tr><td colspan="2" style="padding-top:0cm; padding-left:5.9cm;"><?php echo $row['NM_KELURAHAN']; ?></td></tr>
                <tr><td colspan="2" style="padding-top:0cm; padding-left:3.7cm;"><?php echo $row['NOP']; ?></td></tr>
                <tr>
                    <td style="width:50%; padding-top:0cm; padding-left:3.7cm;"><?php echo $row['THN_PAJAK_SPPT']; ?></td>
                    <td style="width:50%; padding-top:0cm; padding-right:0.3cm; text-align:right;"><?php echo nominal($row['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td>
                </tr>
            </table>
        </div>
        <div class="pagebreak"></div>
        <?php
    }

    $no++;
}
?>
</body>
</html>