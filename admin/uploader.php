<?php 

if (empty($_COOKIE['auth'])) {
    header('Location: ./index.php');
    exit;
}

if (empty($_FILES['files']['name'][0])) exit;

$destPath = '../uploads';
if (!file_exists($destPath)) {
    mkdir('../uploads');
}

$allFiles = scandir($destPath);

foreach($_FILES['files']['tmp_name'] as $index => $path) {
    if (file_exists($path)) {
        $fileInfo = pathinfo($_FILES['files']['name'][$index]);

        $findFiles = preg_grep("/^" . $fileInfo['filename'] . "(.+)?\." . $fileInfo['extension'] . "$/", $allFiles);
        $fileName = $fileInfo['filename'] . (count($findFiles) > 0 ? '_' . (count($findFiles)+1) : '') . '.' . $fileInfo['extension'];

        move_uploaded_file($path, $destPath . '/' . $fileName);
    }
}
header("Location: ./explorer.php?path=./../uploads/");