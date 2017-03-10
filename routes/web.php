<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

$basepath = base_path();

// Dynamically include all files in the web directory
foreach (new DirectoryIterator($basepath.'/routes/web') as $file)
{
  if (!$file->isDot() && !$file->isDir() && $file->getFilename() != '.gitignore')
  {
    require_once $basepath.'/routes/web/'.$file->getFilename();
  }
}