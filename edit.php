<?php
    /**
     * Exhibit all the information about a show allowing to edit
     */

    include 'include/user_permission.php';
    //include 'include/page_navigation.php';

    // Connect to database
    require 'include/connect.php';

    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();

    // Sanitize and validate the ID
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (filter_var($id, FILTER_VALIDATE_INT) == false)
    {
        // Redirect user to another page
        header('/proj/home');
    }


    // Get the production passed by GET parameter
    // run a SELECT query
    $query = "SELECT Name, DateReleased, Country, LastUpdate, Description, Image FROM productions WHERE ProductionId = $id";

    // prepare a PDOStatement object
    $statement = $db->prepare($query);

    // The query is now executed.
    $success = $statement->execute();

    // Fetch the result in a local variable
    $prod = $statement->fetch();

    $dateReleased = date("Y-m-d", strtotime($prod['DateReleased']));
?>

<?php include ("templates/header.php") ?>
    <section>
        <?php if ($showPermission == 1): ?>
            <form action="wd2/proj-master/proj-master/process" method="post">
                <fieldset>
                    <legend>Update Production</legend>
                    <p>
                        <label for="name">Name</label>
                        <input id="name" name="name" type="text" value="<?= $prod['Name'] ?>">
                    </p>
                    <p>
                        <label for="country">Country</label>
                        <input id="country" name="country" type="text" value="<?= $prod['Country'] ?>">
                    </p>
                    <p>
                        <label for="datereleased">Date Released</label>
                        <input id="datereleased" name="datereleased" type="date" value="<?= $dateReleased ?>">
                    </p>
                    <p>
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="10" cols="80"><?= $prod['Description'] ?></textarea>
                        <script>
                            // Replace the <textarea id="description"> with a CKEditor
                            // instance, using default configuration.
                            CKEDITOR.replace('description');
                        </script>
                    </p>
                    <p>
                        <input name="id" value="<?=$id?>" type="hidden">
                        <input name="command" value="Update" type="submit">
                        <input name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')" type="submit">
                    </p>
                </fieldset>
            </form>
        <?php else: ?>
            <p>You do not have enough permission.</p>
        <?php endif; ?>
    </section>
<?php include "templates/footer.php" ?>
