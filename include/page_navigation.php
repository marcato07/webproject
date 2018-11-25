<?php
    /**
     * Functions to redirect to other page
     */

    function redirectPage ($destinationPage)
    {
        // Redirect user to another page
        header('Location: ' . $destinationPage);
        // Exit the script normally
        exit(0);
    }
?>