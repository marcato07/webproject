<?php
    /**
     * Checks the user permission
     */
    include 'constants.php';

    function getPermission()
    {
        $showPermission = 0;

        // Check user permission
        if (isset($_SESSION[PERMISSION_SESSION_KEY]))
        {
            // Get the value from the Session variable
            $showPermission = $_SESSION[PERMISSION_SESSION_KEY];
        }

        return $showPermission;
    }
?>