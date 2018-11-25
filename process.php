<?php
    /*
     * Get the data posted from the create or edit and modify the data of the database
     */

    include 'include/user_permission.php';
    //include 'include/page_navigation.php';

    // Connect to database
    require 'include/connect.php';

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
    $command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $releasedDate = $_POST['datereleased'];

    if (strlen($name) < 1 || strlen($country) < 1 || strlen($description) < 1)
    {
        $error = true;
    }

    $testDate = date_parse($releasedDate); // or date_parse_from_format("d/m/Y", $date);
    if (!checkdate($testDate['month'], $testDate['day'], $testDate['year']))
    {
        // Invalid Date
        $error = true;
    }

    if ($error == false)
    {
        require 'include/connect.php';

        if ($command == "Create")
        {
            // Insert the new post in the database
            $query = "INSERT INTO productions (Name, DateReleased, Country, Description) values (:name, :releasedDate, :country, :description)";

            // how can I get a id here?    
            //$id="SELECT ProductionId FROM productions WHERE DateReleased=:releasedDate";

        }
        elseif ($command == "Update")
        {
            // Sanitize and validate the ID
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            if (filter_var($id, FILTER_VALIDATE_INT) == false)
            {
                // Redirect user to another page
                header('Location: ');
                exit;
            }

            // Update the production in the database
            $query = "UPDATE productions SET Name = :name, DateReleased = :releasedDate, Country = :country, Description = :description WHERE ProductionId = $id";
        }
        elseif ($command == "Delete")
        {
            // Sanitize and validate the ID
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            if (filter_var($id, FILTER_VALIDATE_INT) == false)
            {
                // Redirect user to another page
                header('Location: wd2/proj-master/proj-master/index.php');
                exit;
            }

            // Delete the production from the database
            $query = "DELETE FROM productions WHERE ProductionId = $id";
        }

        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':releasedDate', $releasedDate);
        $statement->bindValue(':country', $country);
        $statement->bindValue(':description', $description);

        $statement->execute();

        // Redirect user to another page
       header('Location: /wd2/proj-master/proj-master/index.php');
        exit;
    }
?>

<?php include ("templates/header.php") ?>
    <section>
        <h1>An error occured while processing your post.</h1>
        <p>Both the title and content must be at least one character.</p>
    </section>
<?php include "templates/footer.php" ?>