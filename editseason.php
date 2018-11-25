<?php
    /**
     * Web page with a form to edit a season data
     */

    include 'include/user_permission.php';
    include 'include/validations.php';
    //nclude 'include/page_navigation.php';
    include 'include/dbmanager.php';

    // Connect to database
    require 'include/connect.php';

    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();

    $error = null;

    // Verify if data was posted => UPDATE or DELETE data in the table
    if (isset($_POST['command']))
    {
        // Extract the data POSTed sanitizing user input
        $command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = validateIntInput(INPUT_POST, 'id');
        $season = validateIntInput(INPUT_POST, 'season');
        $numEpisodes = validateIntInput(INPUT_POST, 'numepisodes');
        $year = validateIntInput(INPUT_POST, 'year');

        if ($id == null || $season == null ||
            $numEpisodes == null || $numEpisodes < 1 ||
            $year == null || $year < 1)
        {
            $error = 'Invalid input.';
        }
        else
        {
            if ($command == 'Update')
            {
                // Update the new record in the database
                $query = "UPDATE seasons 
                          SET NumEpisodes = :numEpisodes, Year = :year
                          WHERE ProductionId = :id
                            AND SeasonNum = :season";
                $statement = $db->prepare($query);
                $statement->bindValue(':season', $season);
                $statement->bindValue(':id', $id);
                $statement->bindValue(':numEpisodes', $numEpisodes);
                $statement->bindValue(':year', $year);
                $statement->execute();
            }
            elseif ($command == 'Delete')
            {
                // Delete the record from the database
                $query = "DELETE FROM seasons 
                          WHERE ProductionId = :id
                            AND SeasonNum = :season";
                $statement = $db->prepare($query);
                $statement->bindValue(':id', $id);
                $statement->bindValue(':season', $season);
                $statement->execute();
            }

            // Redirect user to another page
            header('Location:/wd2/proj-master/proj-master/show/' . $id . '/' . $title);
        }
    }
    else
    {
        // Sanitize and validate the GET inputs
        $id = validateIntInput(INPUT_GET, 'id');
        $season = validateIntInput(INPUT_GET, 'season');
        $title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($id == null || $season == null)
        {
            // Redirect user to another page
            redirectPage('/proj/home');
        }

        // Get the production season
        // run a SELECT query
        $query = "SELECT NumEpisodes, Year
                  FROM seasons
                  WHERE ProductionId = $id
                  AND SeasonNum = $season";
        $seasonData = selectRecord($db, $query);
    }
?>


<?php include ("templates/header.php") ?>
    <section>
        <?php if ($showPermission == 1): ?>
            <?php if ($error != null): ?>
                <p><?=$error?></p>
            <?php endif; ?>

            <form action="wd2/proj-master/proj-master/editseason/<?=$id?>/<?=$season?>/<?=$title?>" method="post">
                <fieldset>
                    <legend>Season: <?=$season?></legend>
                    <ol>
                        <li>
                            <label for="numepisodes">Number of Episodes</label>
                            <input id="numepisodes" name="numepisodes" type="number" value="<?=$seasonData['NumEpisodes']?>">
                        </li>
                        <li>
                            <label for="year">Year</label>
                            <input id="year" name="year" type="number" value="<?=$seasonData['Year']?>">
                        </li>
                        <li>
                            <input name="id" value="<?=$id?>" type="hidden">
                            <input name="season" value="<?=$season?>" type="hidden">
                            <input name="title" value="<?=$title?>" type="hidden">
                            <input name="command" value="Update" type="submit">
                            <input name="command" value="Delete" type="submit" onclick="return confirm('Are you sure you wish to delete this season?')">
                        </li>
                    </ol>
                </fieldset>"
            </form>
        <?php else: ?>
            <p>You do not have enough permission.</p>
        <?php endif; ?>
    </section>
<?php include "templates/footer.php" ?>
