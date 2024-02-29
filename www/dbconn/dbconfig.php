<?php 
    // Database connection settings
    $host = 'localhost';      // Change as necessary
    $data = 'gldb';           // Change as necessary
    $user = 'gluser';         // Change as necessary
    $pass = 'password';       // Change as necessary
    $chrs = 'utf8mb4';
    $attr = "mysql:host=$host;dbname=$data;charset=$chrs";
    $opts =
    [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];
?>