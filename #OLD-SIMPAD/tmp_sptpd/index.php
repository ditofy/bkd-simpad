<?php
require 'config.php';
$conn = conn();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Tagihan PBB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.5/dist/axios.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('#id_kecamatan').onchange = function(event) {
          axios({
            method: 'GET',
            url: './ajax.php',
            params: {
              id_kecamatan: this.value,
            }
          })
          .then(function(response) {
            if(response.data.success === true) {
              var element = document.querySelector('#id_kelurahan');
              var option = document.createElement('option');
              element.options.length = 0;
              option.value = '';
              option.text = '-- Pilih Kelurahan --';
              element.appendChild(new Option(option.text, option.value));
          
              for (var i = 0; i < response.data.data.length; i++) {
                option.value = response.data.data[i].kd_kelurahan;
                option.text = response.data.data[i].nm_kelurahan;
                element.appendChild(new Option(option.text, option.value));
              }
            } else {
              console.log(response.data);
            }
          event.preventDefault();
        });
      };
    });
    </script>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-12 mt-5">
          <h1 class="text-center mb-5">Cetak Tagihan PBB Tahun <?php echo constant('TAHUN'); ?></h1>
          <form target="_blank" method="get" action="./print.php">
            <label class="form-label fs-3">Kecamatan</label>
            <select name="id_kecamatan" id="id_kecamatan" class="form-select form-select-lg mb-3" required>
              <option value="" selected>-- Pilih Kecamatan --</option>
              <?php
              $stmt = $conn->prepare("SELECT KD_KECAMATAN, NM_KECAMATAN FROM PBB.REF_KECAMATAN ORDER BY NM_KECAMATAN");
              $stmt->execute();
              $results = $stmt->fetchAll();
              
              foreach($results as $row) {
                  echo '<option value="' . $row['KD_KECAMATAN'] . '">' . $row['NM_KECAMATAN'] . '</option>';
              }
              ?>
            </select>
            
            <label class="form-label fs-3">Kelurahan</label>
            <select name="id_kelurahan" id="id_kelurahan" class="form-select form-select-lg mb-3" required>
              <option value="" selected> -- Pilih Kelurahan --</option>
            </select>

            <div class="d-grid">
              <button type="submit" class="btn btn-lg btn-primary mb-3">Cetak</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>