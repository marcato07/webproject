<?php
    /**
     * Web page with a form to input the season data
     */

    include 'include/user_permission.php';
    include 'include/validations.php';
    
    // Connect to database
    require 'include/connect.php';

    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();

    $error = null;

    // Verify if data was posted => INSERT data in the table
    if (isset($_POST['id']))
    {
        // Extract the data POSTed sanitizing user input
        $command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = validateIntInput(INPUT_POST, 'id');
        $nextseason = validateIntInput(INPUT_POST, 'nextseason');
        $numEpisodes = validateIntInput(INPUT_POST, 'numepisodes');
        $year = validateIntInput(INPUT_POST, 'year');

        if ($id == null || $nextseason == null ||
            $numEpisodes == null || $numEpisodes < 1 ||
            $year == null || $year < 1)
        {
            $error = 'Invalid input.';
        }
        else
        {
            // Insert the new record in the database
            $query = "INSERT INTO seasons (SeasonNum, ProductionId, NumEpisodes, Year) values (:nextseason, :id, :numEpisodes, :year)";
            $statement = $db->prepare($query);
            $statement->bindValue(':nextseason', $nextseason);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':numEpisodes', $numEpisodes);
            $statement->bindValue(':year', $year);
            $statement->execute();

            // Redirect user to another page
            header('Location:/wd2/proj-master/proj-master/show/' . $id . '/' . $title);
            exit;
        }
    }
    else
    {
        // Sanitize and validate the GET inputs
        $id = validateIntInput(INPUT_GET, 'id');
        $nextseason = validateIntInput(INPUT_GET, 'nextseason');
        $title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($id == null || $nextseason == null)
        {
            // Redirect user to another page
            header('Location: wd2/proj-master/proj-master/home');
        }
    }
?>


<?php include ("templates/header.php") ?>
    <section>
        <?php if ($showPermission == 1): ?>
            <?php if ($error != null): ?>
                <p><?=$error?></p>
            <?php endif; ?>

            <form action="wd2/proj-master/proj-master/createseason/<?=$id?>/<?=$nextseason?>/<?=$title?>" method="post">
                <fieldset>
                    <legend>New Season: <?=$nextseason?></legend>
                    <ol>
                        <li>
                            <label for="numepisodes">Number of Episodes</label>
                            <input id="numepisodes" name="numepisodes" type="number">
                        </li>
                        <li>
                            <label for="year">Year</label>
                            <input id="year" name="year" type="number">
                        </li>
                        <li>
                            <input name="id" value="<?=$id?>" type="hidden">
                            <input name="nextseason" value="<?=$nextseason?>" type="hidden">
                            <input name="title" value="<?=$title?>" type="hidden">
                            <input name="command" value="Create" type="submit">
                        </li>
                    </ol>
                </fieldset>
            </form>
        <?php else: ?>
            <p>You do not have enough permission.</p>
        <?php endif; ?>
    </section>
<?php include "templates/footer.php" ?>
