<?php 

function conectarDB() : mysqli {
    $db = mysqli_connect($_ENV['DB_HOST'],
                        $_ENV['DB_USER'], 
                        $_ENV['DB_PASS'], 
                        $_ENV['DB_DB']);
    if (!$db) {
        echo "Error while connecting to the database";
        exit;
    } 
    return $db;
}



?>