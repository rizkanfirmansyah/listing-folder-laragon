<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listing Folder Localhost</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    #accordion {
      color: #ffffff !important;
    }

    .folder-card {
      background-color: cornflowerblue;
      color: #ffffff !important;
      border-radius: 5px;
      margin-bottom: 10px;
      margin-right: 10px;
    }

    .folder-card-header {
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div class="container-fluid p-5">
    <h2>Listing Folder Localhost</h2>
    <?php
    // Path ke folder Laragon (pastikan ini mengarah ke root folder web server Anda)
    $folderPath = "C:/laragon/www/";

    // Membaca daftar file dan folder
    $folders = array_filter(glob($folderPath . '*'), 'is_dir');

    // Menampilkan daftar folder dengan accordion
    echo '<div id="accordion" class="">';
    foreach ($folders as $index => $folder) {
      $folderName = basename($folder);
      echo '<div class="card folder-card col-12">';
      echo '<div class="card-header folder-card-header" id="heading' . $index . '"  data-toggle="collapse" href="#collapse' . $index . '" aria-expanded="true" aria-controls="collapse' . $index . '">';
      echo '<h5 class="mb-0 text-light">';
      echo '<a class="text-light">' . $folderName . '</a>';
      echo '</h5>';
      echo '</div>';
      echo '<div id="collapse' . $index . '" class="collapse" aria-labelledby="heading' . $index . '" data-parent="#accordion">';
      echo '<div class="card-body text-light">';
      echo '<ul>';
      // Menampilkan daftar file di dalam folder
      $files = glob($folder . '/*');
      foreach ($files as $file) {
        $fileName = basename($file);
        // Menyusun link untuk membuka subfolder atau file
        if (is_dir($file)) {
          echo '<li><a href="' . $folderName . '/' . $fileName . '" class="text-light">' . $fileName . '</a></li>';
        } else {
          echo '<li>' . $fileName . '</li>';
        }
      }
      echo '</ul>';
      // Menampilkan "Open This Folder" untuk folder yang sedang dibuka
      echo '<p><a href="' . $folderName . '" class="text-light">Open This Folder</a></p>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
    echo '</div>';
    ?>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>