<?php
    /* Connect to the project database */

    //define('DB_DSN','mysql:host=sql212.byethost.com;dbname=b31_23086083_users;charset=utf8');
    //define('DB_USER','b31_23086083');
    //define('DB_PASS','qsvt4jhm');

    define('DB_DSN','mysql:host=localhost;dbname=serverside;charset=utf8');
    define('DB_USER','serveruser');
    define('DB_PASS','gorgonzola7!');

    // Create a PDO object called $db.
    $db = new PDO(DB_DSN, DB_USER, DB_PASS);

    try
    {
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
    }
    catch (PDOException $e)
    {
        print "Error: " . $e->getMessage();
        die(); // Force execution to stop on errors.
    }
?>