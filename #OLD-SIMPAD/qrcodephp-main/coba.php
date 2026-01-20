<?php
//library phpqrcode
include "phpqrcode/qrlib.php";
 
//direktory tempat menyimpan hasil generate qrcode jika folder belum dibuat maka secara otomatis akan membuat terlebih dahulu
$tempdir = "temp/"; 
if (!file_exists($tempdir))
    mkdir($tempdir);
 
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="icon" href="dk.png">
    <title>QRCode Generator</title>
</head>
<body>
  <?php
    //Isi dari QRCode Saat discan
    $email = 'dewan.komputer@gmail.com';
    $subject = 'Pertanyaan Anda';
    $body = 'Isi dari email yang ingin dikirim';
     
    $isi_teks = "mailto:".$email.'?subject='.urlencode($subject).'&body='.urlencode($body);
    //Nama file yang akan disimpan pada folder temp 
    $namafile = "dewan-komputer.png";
    //Kualitas dari QRCode 
    $quality = 'L'; 
    //Ukuran besar QRCode
    $ukuran = 8; 
    $padding = 0; 
  //  QRCode::png($isi_teks,$tempdir.$namafile,$quality,$ukuran,$padding);
  ?>
  <div align="center" style="margin-top: 50px;">
    <h2>Cara Membuat QRCode Generator Send Email Menggunakan PHP </h2>
    <img src="temp/<?php echo $namafile; ?>" style="margin: 50px; width: 250px;">
    <p>www.dewankomputer.com</p>
    <a href="https://dewankomputer.com/2019/01/14/cara-membuat-qrcode-generator-menggunakan-php-part-8-qrcode-kirim-email/"><p><< Kembali ke Tutorial</p></a>
  </div>
</body>
</html>