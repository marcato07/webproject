<?php
    /*
     * Get the data posted from the create or edit and modify the data of the database
     */

    include 'include/user_permission.php';
    //include 'include/page_navigation.php';

    // Connect to database
   // require 'include/connect.php';
    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();

    $error = false;

    // Check if user is administrator
    if ($showPermission != 1)
    {
        // Not allowed to make modifications
        $error = true;
    }

    // Extract the data POSTed sanitizing user input

    $genre= filter_input(INPUT_POST,'genre',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (strlen($genre) < 1)
    {
        $error = true;
    }


    if ($error == false)
    {
        require 'include/connect.php';

        if ($command == "Create")
        {
            // Insert the new post in the database
            $query = "INSERT INTO category (genre) values (:genre)";
        }

        elseif ($command == "Edit")
        {
            // Sanitize and validate the ID
            if (filter_var($genre, FILTER_SANITIZE_FULL_SPECIAL_CHARS) == false)
            {
                // Redirect user to another page
                header('Location: wd2/proj-master/proj-master/index.php');
                exit;
            }

            // Update the production in the database

           // $query = "UPDATE category SET genre = :genre, DateReleased = :releasedDate, Country = :country, Description = :description, genre=:genre WHERE ProductionId = $id";
        }
        elseif ($command == "Delete")
        {
            // Sanitize and validate the ID
            if (filter_var($genre, FILTER_SANITIZE_FULL_SPECIAL_CHARS) == false)
            {
                // Redirect user to another page
                //header('Location: wd2/proj-master/proj-master/index.php');
                //exit;
            }

            // Delete the production from the database
            echo $genre;
            $query = "DELETE FROM category WHERE genre = '$genre'";
        }

        $statement = $db->prepare($query);
        $statement->bindValue(':genre', $genre);

        $statement->execute();

        // Redirect user to another page
        header('Location: /wd2/proj-master/proj-master/category.php');
        exit;
    }

?>

<?php include ("templates/header.php") ?>
    <section>
        <h1>An error occured while processing your post.</h1>
        <p>Category name has to be 1 more character.</p>

        <?=$genre?>
            </section>
<?php include "templates/footer.php" ?>