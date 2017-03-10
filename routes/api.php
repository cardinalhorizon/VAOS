<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$basepath = base_path();

// Dynamically include all files in the api directory
foreach (new DirectoryIterator($basepath.'/routes/api') as $file)
{
  if (!$file->isDot() && !$file->isDir() && $file->getFilename() != '.gitignore')
  {
    require_once $basepath.'/routes/api/'.$file->getFilename();
  }
}