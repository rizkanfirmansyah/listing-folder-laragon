<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listing Folder Localhost</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background: #f7f7f8;
      font-family: 'Segoe UI', 'Arial', sans-serif;
    }
    .notion-container {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      padding: 32px;
      margin-top: 32px;
    }
    .notion-folder {
      border: 1px solid #ececec;
      border-radius: 6px;
      margin-bottom: 10px;
      background: #fafbfc;
      transition: box-shadow 0.2s;
    }
    .notion-folder:hover {
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .notion-folder-header {
      display: flex;
      align-items: center;
      cursor: pointer;
      padding: 12px 16px;
      font-weight: 500;
      font-size: 1.1rem;
      border-bottom: 1px solid #ececec;
      background: #f5f6fa;
      user-select: none;
    }
    .notion-folder-header .chevron {
      margin-right: 10px;
      transition: transform 0.2s;
    }
    .notion-folder-header.collapsed .chevron {
      transform: rotate(-90deg);
    }
    .notion-folder-content {
      padding: 10px 24px 10px 36px;
      background: #fff;
    }
    .notion-file {
      display: flex;
      align-items: center;
      padding: 4px 0 4px 0;
      font-size: 1rem;
      color: #444;
    }
    .notion-file .icon {
      margin-right: 8px;
      color: #b3b3b3;
    }
    .notion-folder-stats {
      font-size: 0.95rem;
      color: #888;
      margin-left: 8px;
    }
    .notion-folder-link {
      color: #4f8cff;
      text-decoration: none;
      font-size: 0.95rem;
      margin-left: 8px;
    }
    .notion-folder-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="container notion-container">
    <h2 style="font-weight:700;">📁 Listing Folder Localhost</h2>
    <?php
    // Fungsi untuk menghitung total file, folder, dan size secara rekursif
    function getFolderStats($dir) {
      $totalFiles = 0;
      $totalFolders = 0;
      $totalSize = 0;
      $items = @scandir($dir);
      if (!$items) return [0,0,0];
      foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
          $totalFolders++;
          list($f, $d, $s) = getFolderStats($path);
          $totalFiles += $f;
          $totalFolders += $d;
          $totalSize += $s;
        } else {
          $totalFiles++;
          $totalSize += filesize($path);
        }
      }
      return [$totalFiles, $totalFolders, $totalSize];
    }

    // Fungsi untuk menampilkan folder dan file secara rekursif dengan collapse
    function renderFolderTree($dir, $parentId = '', $level = 0, $relativePath = '') {
      static $folderIndex = 0;
      $items = @scandir($dir);
      if (!$items) return;
      $id = $parentId . 'f' . $folderIndex++;
      $folderName = basename($dir);
      $currentRelativePath = ltrim($relativePath . '/' . $folderName, '/');
      list($totalFiles, $totalFolders, $totalSize) = getFolderStats($dir);
      $sizeStr = formatSize($totalSize);
      echo '<div class="notion-folder">';
      echo '<div class="notion-folder-header" data-toggle="collapse" href="#collapse'.$id.'" role="button" aria-expanded="false" aria-controls="collapse'.$id.'">';
      echo '<span class="chevron">▶</span> <span style="font-size:1.2em;">📁</span> ' . htmlspecialchars($folderName);
      echo '<span class="notion-folder-stats">('.$totalFolders.' folders, '.$totalFiles.' files, '.$sizeStr.')</span>';
      echo '<a class="notion-folder-link" href="/' . htmlspecialchars($currentRelativePath) . '" target="_blank">Open</a>';
      echo '</div>';
      echo '<div class="collapse notion-folder-content" id="collapse'.$id.'">';
      echo '<ul style="list-style:none;padding-left:0;">';
      foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
          renderFolderTree($path, $id, $level+1, $currentRelativePath);
        } else {
          $size = formatSize(filesize($path));
          echo '<li class="notion-file"><span class="icon">📄</span>' . htmlspecialchars($item) . ' <span class="notion-folder-stats">('.$size.')</span></li>';
        }
      }
      echo '</ul>';
      echo '</div>';
      echo '</div>';
    }

    // Fungsi untuk format size
    function formatSize($bytes) {
      if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
      } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
      } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
      } elseif ($bytes > 1) {
        return $bytes . ' bytes';
      } elseif ($bytes == 1) {
        return '1 byte';
      } else {
        return '0 bytes';
      }
    }

    // Path ke folder Laragon
    $folderPath = "C:/laragon/www/";
    $folders = array_filter(glob($folderPath . '*'), 'is_dir');
    foreach ($folders as $folder) {
      renderFolderTree($folder, '', 0, '');
    }
    ?>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    // Notion-style chevron toggle
    $(document).on('show.bs.collapse', '.notion-folder-content', function () {
      $(this).prev('.notion-folder-header').find('.chevron').css('transform', 'rotate(90deg)');
    });
    $(document).on('hide.bs.collapse', '.notion-folder-content', function () {
      $(this).prev('.notion-folder-header').find('.chevron').css('transform', 'rotate(0deg)');
    });
  </script>
</body>

</html>