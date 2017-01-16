<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 9/20/16
 * Time: 1:09 AM
 */

require_once (__DIR__."/vendor/autoload.php");

// lets load the environment file in the project directory

$dotenv = new \Dotenv\Dotenv(__DIR__."/../../");
$dotenv->load();

define('VAOS_URL', $_ENV['APP_URL']);