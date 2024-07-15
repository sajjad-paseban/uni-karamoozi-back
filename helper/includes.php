<?php 
require  '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createUnsafeImmutable('../');
$dotenv->load();


include "../database/db.php";

header('Content-Type: application/json');