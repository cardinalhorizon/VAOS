<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 9/14/16
 * Time: 12:30 AM
 */


$capsule = new Illuminate\Database\Capsule\Manager;


// Lets do some boot loading real quick to pull our connection in just in-case
$dotenv = new \Dotenv\Dotenv(__DIR__.'/../');
$dotenv->load();

$capsule->addConnection(
    ['driver' => 'mysql',
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => $_ENV['DB_PREFIX'],
            'strict' => true,
            'engine' => null]);
$capsule->setAsGlobal();
$capsule->bootEloquent();