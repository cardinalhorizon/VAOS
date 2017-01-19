<?php

$DBHost = 'localhost'; //Your host, usually localhost
$DBUser = 'X';  //Your database username
$DBPass = 'X';  //Your database password
$DBName = 'X';  //The database name you want/have the user system on

mysql_connect("$DBHost", "$DBUser", "$DBPass")or die(mysql_error()); 
mysql_select_db("$DBName")or die(mysql_error()); 

