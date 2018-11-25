<?php
    /**
     * Functions to retrieve data from the database tables
     */

    // Retrieve one record from a table
    function selectRecord ($db, $query)
    {
        // prepare a PDOStatement object
        $statement = $db->prepare($query);
        // The query is now executed.
        $success = $statement->execute();
        // Fetch the result and return it
        return $statement->fetch();
    }

    // Retrieve more than 1 record from a table
    function selectAllRecords ($db, $query)
    {
        // prepare a PDOStatement object
        $statement = $db->prepare($query);
        // The query is now executed.
        $success = $statement->execute();
        // Fetch the result and return it
        return $statement->fetchAll();
    }
?>