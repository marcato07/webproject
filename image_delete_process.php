<?php
    /**
     * Process the delete of a image
     */

    //include 'include/page_navigation.php';

    // Connect to database
    require 'include/connect.php';

    // Sanitize and validate the ID
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (filter_var($id, FILTER_VALIDATE_INT) == false)
    {
        // Redirect user to another page
        header('Location: wd2/proj-master/proj-master/home');
    }

    // Sanitize and validate the inputs
    $image = filter_input(INPUT_GET, 'image', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_SPECIAL_CHARS);

    // Update the production in the database
    $query = "UPDATE productions SET Image = :image WHERE ProductionId = $id";
    $statement = $db->prepare($query);
    $statement->bindValue(':image', NULL);
    $statement->execute();

    $filePath = 'C:\xampp\htdocs\wd2\proj-master\proj-master\uploads' . $image;
    unlink($filePath);

    // Redirect user to another page
   // header('Location: wd2/proj-master/proj-master/show/'.$id.'/'.$title);
    header('Location: /wd2/proj-master/proj-master/show/'.$id.'/'.$title);
    exit;
?>
