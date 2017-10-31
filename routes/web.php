<?php

$basepath = base_path();
// Dynamically include all files in the web directory
foreach (new DirectoryIterator($basepath.'/routes/web') as $file) {
    if (! $file->isDot() && ! $file->isDir() && $file->getFilename() != '.gitignore') {
        require_once $basepath.'/routes/web/'.$file->getFilename();
    }
}
