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
    $categoryid=filter_input(INPUT_POST, 'categoryid', FILTER_SANITIZE_NUMBER_INT);
    $command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $newgenre= filter_input(INPUT_POST,'title',FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // $categoryid;
   
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
               header('Location: project/index.php');
                exit;
            }

            // Update the production in the database

           $query = "UPDATE category SET genre = :genre WHERE categoryid = '$categoryid' ";

           $genre = $newgenre;
        }
        elseif ($command == "Delete")
        {
            // Sanitize and validate the ID
            if (filter_var($genre, FILTER_SANITIZE_FULL_SPECIAL_CHARS) == false)
            {
                // Redirect user to another page
                header('Location: project/index.php');
                exit;
            }

            // Delete the production from the database
            $query = "DELETE FROM category WHERE genre= '$genre' AND categoryid= '$categoryid' " ;
        }

        $statement = $db->prepare($query);
        //$statement->bindValue(':genre', $genre);
        $statement->bindValue(':genre', $genre);

        $statement->execute();

        // Redirect user to another page
        header('Location: /project/category.php');
        exit;
    }

?>

<?php include ("templates/header.php") ?>
    <section>
        <h1>An error occured while processing your post.</h1>
        <p>Category name has to be 1 more character.</p>

        <?=$categoryid;?>
                <?=$newgenre;?>

            </section>
<?php include "templates/footer.php" ?>