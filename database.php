<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'gmap';

$con = mysqli_connect($host,$user,$pass,$db);
if (!$con) { die('Database Connection failed! '.mysqli_connect_error()); }

