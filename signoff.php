<?php
    /*
     * Delete the credential information from the session variables
     */

   // include 'include/page_navigation.php';

    // start session
    session_start();

    // Remove all session data
    session_destroy();

    // Redirect user to another page
    header('Location: /project/index.php');
    exit;
?>