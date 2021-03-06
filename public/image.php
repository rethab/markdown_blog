<?php

if (isset($_GET['src'])) {
    if (isset($_GET['width'])) {
        $src = $_GET['src'];
        $width = $_GET['width'];
        $width = ceil($width / 10) * 10;
        $out = 'cache/w' . $width . '_' . basename($src);
        if (!(is_numeric($width) && $width > 0 && $width < 4000 && is_file(getcwd().$src))) {
            echo $_GET['src'];
            exit;
        }
        if (!is_file($out)) {
            exec('convert .' . escapeshellarg($src) . ' -interlace Plane -strip  -quality 70 -resize ' . escapeshellarg($width) . ' ' . escapeshellarg($out));
        }
        echo '/' . $out;
    } else {
        echo $_GET['src'];
    }
}
