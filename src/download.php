<?php

require_once 'includes/init.php';

use Respect\Validation\Validator as v;

$filename  = pathinfo(get('file'), PATHINFO_BASENAME);
$extension = pathinfo($filename,  PATHINFO_EXTENSION);
$path = UPLOAD_DIR . '/' . $filename;
$isAllowedExtension = v::in(ALLOWED_UPLOAD_EXTENSIONS)->validate($extension);

if ($isAllowedExtension && file_exists($path)) {
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$filename");
    readfile($path);
} else {
    error('File error', 'This file does not exist or is not valid.');
}
