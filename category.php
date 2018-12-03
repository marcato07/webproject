<?php
    /**
     * Web page with a form to input the show data
     */

    include 'include/user_permission.php';
    require __DIR__ . '/vendor/autoload.php';
    // Connect to database
    require 'include/connect.php';


    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();


    $query ="SELECT genre
            FROM category";
    // prepare a PDOStatement object
    $statements = $db->prepare($query);
    // The query is now executed.
    $success = $statements->execute();
    $categories= $statements->fetchAll();

?>

<?php include ("templates/header.php") ?>
    <section>
        <?php if ($showPermission == 1): ?>

            <form action="wd2/proj-master/proj-master/processcategory.php" method="post">
                <fieldset>
                    <legend>New Category</legend>


                        <li>
                            <label for="cgenre">Current genre</label>
                            
                            <?php foreach ($categories as $item): ?>
                                <li><?=$item['genre']?> </li>
                                <input type="submit" name="command" value="Edit" onclick="return confirm('Are you sure you wish to Edit this category?')" />      
                                <input type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to Delete this category?')" />  
                            <?php endforeach; ?>
                            
                        </li>

                        <li>
                            <label for="genre">Add New Genre</label>
                            <input id="genre" name="genre" type="text">
                            <input name="command" value="Create" type="submit">
                        </li>
                    </form>
        <?php else: ?>
            <p>You do not have enough permission.</p>
        <?php endif; ?>
    </section>
<?php include "templates/footer.php" ?>