<?php
if (empty($_COOKIE['auth'])) {
    header('Location: ./index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/admin/css/style.css">
</head>
<body>
<div class="container">
    <div class="explorer">
<?php

$basePath = !empty($_GET['path']) ? $_GET['path'] : './';

$dir_array = [];
$file_array = [];

if (is_dir($basePath)) {
    $dd = opendir($basePath);

    while ($item = readdir($dd)) {
        if (is_dir($basePath . $item)) array_push($dir_array, $item);
        if (is_file($basePath . $item)) array_push($file_array, $item);
    }
} else {
    array_push($dir_array, '..');
}
sort($dir_array);
sort($file_array);

$list = '';

foreach ($dir_array as $dir) {
    $path = $basePath . $dir . '/';

    if ($dir == '.') continue;

    if ($dir == '..') {
        $basePathArr = explode('/', $basePath);
        if (is_dir($basePath)) array_pop($basePathArr);

        $lastItem = array_pop($basePathArr);

        if ($lastItem != '.' && $lastItem != '..') {
            $path = implode('/', $basePathArr) . '/';
        }

        if (!file_exists($path)) {
            array_pop($basePathArr);

            $path = implode('/', $basePathArr) . '/';
        }
    }

    $list .= '<li><a href="/admin/explorer.php?path=' . $path . '"> ' . $dir . ' </a></li>';
}

foreach ($file_array as $file) {
    $list .= '<li><a href="/admin/explorer.php?path=' . $basePath . $file . '">' . $file . '</a></li>';
}

if (!empty($list)) echo '<ul><li><a href="/admin/">[ . ]</a></li>' . $list . '</ul>';

if (is_file($basePath)) {
    $content = htmlspecialchars(file_get_contents($basePath));

    echo '<form method="POST">
    <textarea name="filecontent" style="width: 520px; height: 200px;">' . $content . '</textarea>
    <button>Save</button>
    </form>';
}
if (!empty($_POST['filecontent'])) {
    file_put_contents($basePath, mb_convert_encoding(htmlspecialchars_decode($_POST['filecontent']), 'UTF-8'));
    header("location:" . $_SERVER['REQUEST_URI']);
}

?>
</div>
<div class="forms">
<form method="POST">
    <?php 
    if (empty($_GET['path'])) {$path = './';}  
    else $path = $_GET['path'] . (!empty($_GET['file']) ? '/' . $_GET['file'] : ''); ?>
    
    <input type="hidden" name="path" value="<?php echo $path; ?>" />
    <input type="hidden" name="action" value="delete" />

    <?php 
    date_default_timezone_set('Europe/Minsk');
    if (is_file($path)) {
    echo 'File size: ' . filesize($path) . '<br>';
    echo 'Last update: ' . date("F d Y H:i:s.", filemtime($path)) . '<br>';
    }
    echo 'Adress: ' . $path; ?>
    <button>Delete</button>
</form>

<form method="POST">
    Rename:
    <input type="text" name="rename" />
    <button>Okay</button>
</form>

<form method="POST">
    <input type="hidden" name="action" value="create" />

    Create: 
    <input type="text" name="name" />
    <input checked type="radio" name="type" value="file" />
    <input type="radio" name="type" value="directory" />
    <button>Go</button>
</form>

<?php 

    // Delete

if (!empty($_POST['action']) && !empty($_POST['path']) && $_POST['action'] == 'delete') {

    $newPathArr = explode('/', $path);

    if (is_dir($path)) {
        array_pop($newPathArr);
        array_pop($newPathArr);
    }
    if (is_file($path)) {
        array_pop($newPathArr);
    }
    $newPath = implode('/', $newPathArr) . '/';

    if (is_dir($_POST['path'])) rmdir($_POST['path']);
    else if (is_file($_POST['path'])) unlink($_POST['path']);

    header("Location: ./explorer.php?path=$newPath");
}

    // Rename

if (!empty($_POST['rename'])) {
    $newPathArr = explode('/', $path);

    if (is_dir($path)) {
        array_pop($newPathArr);
        array_pop($newPathArr);
    }
    if (is_file($path)) {
        array_pop($newPathArr);
    }
    array_push($newPathArr, $_POST['rename']);

    if (is_dir($path)) $newPath = implode('/', $newPathArr) . '/';
    if (is_file($path)) $newPath = implode('/', $newPathArr);

    rename($path, $newPath);

    header("Location: ./explorer.php?path=$newPath");
}

    // Create

if (!empty($_POST['action']) && !empty($_POST['type']) && !empty($_POST['name']) && $_POST['action'] == 'create') {
    if (empty($_GET['path'])) {$path = './' . '/' . $_POST['name'];}  
    else $path = $_GET['path'] . '/' . $_POST['name'];
        
        switch ($_POST['type']) {
            case 'file':
                $file = fopen($path, 'w');
                fclose($file);
            break;
            case 'directory':
                mkdir($path);
            break;
        }

        header("Location:" . $_SERVER['REQUEST_URI']);
    }
?>

    <!-- Uploader -->

<form action="./uploader.php" method="POST" enctype="multipart/form-data">
<input type="file" multiple name="files[]" />
<button>Upload</button>
</form>

    <!-- Logout -->

<div class="logout">
    <a href="/admin/logout.php">Log out</a>
</div>
</div>
</div>
</body>
</html>