<?php
// Read first line in log
$file = file('log.txt');
$output = $file[0];
$fileDelete = "captures/" . trim($output) . ".png";
if (isset($file[1000])) {
    // Delete first line in log
    unlink($fileDelete);
    unset($file[0]);
    file_put_contents('log.txt', $file);
}
// End read first line in log


// Unique ID
$uniqueID = date('Ymd_His');

$img = $_POST['imgData'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);

//saving
$fileName = 'captures/' . $uniqueID . '.png';
file_put_contents($fileName, $fileData);

// Write file log
$fp = fopen('log.txt', 'a');
fwrite($fp, $uniqueID . "\n");
fclose($fp);
// End read file log

// Delete empty files
$dir    = 'captures';
$files = scandir($dir);

foreach ($files as $file) {
    if (!is_dir($file)) {
        $size = filesize('captures/' . $file);
        if ($size < 10000) {
            unlink('captures/' . $file);
        }
    }
}
