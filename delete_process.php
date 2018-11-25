<?php
    /**
     * Process the delete of a image
     */

    //include 'include/page_navigation.php';

    // Connect to database
    require 'include/connect.php';

    // Sanitize and validate the ID
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    echo $id;
    if (filter_var($id, FILTER_VALIDATE_INT) == false)
    {
        // Redirect user to another page
        header('Location: http://localhost:31337/wd2/proj-master/proj-master/index.php');
        exit;
    }

    // Sanitize and validate the inputs
    // $image = filter_input(INPUT_GET, 'image', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    // $title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_SPECIAL_CHARS);

    // Update the production in the database
    $query = "DELETE FROM productions WHERE ProductionId = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();


    // Redirect user to another page
    header('Location: http://localhost:31337/wd2/proj-master/proj-master/index.php');
    exit;
?>
