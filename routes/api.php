<?php
$basepath = base_path();
// Dynamically include all files in the api directory
foreach (new DirectoryIterator($basepath.'/routes/api') as $file) {
    if (!$file->isDot() && !$file->isDir() && $file->getFilename() != '.gitignore') {
        require_once $basepath.'/routes/api/'.$file->getFilename();
    }
}
