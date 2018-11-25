<?php
    /**
     * Exhibit all the information about a show
     */

    include 'include/user_permission.php';
    include 'include/validations.php';
    //include 'include/page_navigation.php';

    // Connect to database
    require 'include/connect.php';

    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();

    // Sanitize and validate the ID
    $id = validateIntInput(INPUT_GET, 'id');
    if ($id == null)
    {
        // Redirect user to another page
        header('Location: wd2/proj-master/proj-master/home');
    }

    $title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_SPECIAL_CHARS);

    // Get the production passed by GET parameter
    // run a SELECT query
    $query = "SELECT Name, DateReleased, Country, LastUpdate, Description, Image, ProductionId
              FROM productions
              WHERE ProductionId = $id";
    // prepare a PDOStatement object
    $statement = $db->prepare($query);
    // The query is now executed.
    $success = $statement->execute();
    // Fetch the result in a local variable
    $prod = $statement->fetch();


    //Get the comments 
    $query ="SELECT CurrentDate, content, ProductionId, UserId, Username, CurrentDate, type
            FROM comments WHERE ProductionId=$id ORDER BY CurrentDate DESC";
    // prepare a PDOStatement object
    $statement = $db->prepare($query);
    // The query is now executed.
    $success = $statement->execute();
    $comments= $statement->fetchAll();


   
    // Get the production season
    // run a SELECT query
    $query = "SELECT SeasonNum, NumEpisodes, Year 
              FROM seasons
              WHERE ProductionId = $id";
    // prepare a PDOStatement object
    $statement = $db->prepare($query);
    // The query is now executed.
    $success = $statement->execute();
    // Fetch the result in a local variable
    $seasons = $statement->fetchAll();

    $nextSeason = count($seasons) + 1;




?>

<?php include ("templates/header.php") ?>
    <section>
        <?php if ($showPermission == 1): ?>
            <nav>
                <ul class="nav nav-tabs">
                    <li><a href="wd2/proj-master/proj-master/edit/<?= $id ?>/<?= $prod['Name'] ?>">Edit</a></li>
                    <li><a href="wd2/proj-master/proj-master/imageupload/<?= $id ?>/<?= $prod['Name'] ?>">Image Upload</a></li>
                    <li><a href="wd2/proj-master/proj-master/image_delete_process/<?= $id ?>/<?= $prod['Image'] ?>" onclick="return confirm('Are you sure you wish to delete the image?')">Delete Image</a></li>
                    <li><a href="wd2/proj-master/proj-master/delete_process/<?= $id ?>" onclick="return confirm('Are you sure you wish to delete the production?')">Delete Contents</a></li>
                </ul>
            </nav>
        <?php endif; ?>
        <h1><?= $prod['Name'] ?></h1>
        <?php if ($prod['Image'] != ''): ?>
            <div><img src="wd2/proj-master/proj-master/uploads\<?= $prod['Image'] ?>" alt="<?= $prod['Name'] ?>"></div>
        <?php endif; ?>
        <p>Country: <?= $prod['Country'] ?></p>
        <p>Date Released: <?= date("F j, Y", strtotime($prod['DateReleased'])) ?></p>
        <p>Update on: <?= date("F j, Y", strtotime($prod['LastUpdate'])) ?></p>
        <div>
            <h5>Description:</h5>
            <p><?= html_entity_decode($prod['Description']) ?></p>
        </div>

          <div>
                <?php foreach ($comments as $comment): ?>
                    
                    <?php if  (($showPermission != 1 ) && ($comment['type'] != 2)):?>
                        <p> Writer: <?=$comment['Username']?> Comment: <?=$comment['content']?> 
                             <small> <?= date("F j, Y", strtotime($comment['CurrentDate'])) ?> </small>
                             
                            <!-- <?php if($comment['Username'] == $_SESSION['Username']): ?>
                                 <form action="wd2/proj-master/proj-master/comment_delete.php" method="post">
                                 <input type="submit" name="Edit" value="Edit"/>
                                </form>
                            <?php endif; ?> -->

                         </p>

                    <?php endif; ?>
                <?php endforeach; ?>

                <?php foreach ($comments as $comment): ?>
                    <?php if ($showPermission == 1):?>
                        Writer: <?=$comment['Username']?> Comment: <?=$comment['content']?> 
                                    <small> <?= date("F j, Y", strtotime($comment['CurrentDate'])) ?> </small>

                        <form action="wd2/proj-master/proj-master/comment_delete.php" method="post">
                        <input type="hidden" name="id" value="<?= $id?>"> 
                        <input type="hidden" name="title" value="<?= $title?>">
                        <input type="hidden" name="CurrentDate" value="<?= $comment['CurrentDate']?>">  
                        <input type="hidden" name="UserId" value="<?= $_SESSION['UserId']?>">

                            <?php if($comment['type'] == 2): ?>
                                 <input type="submit" name="command" value="Public" onclick="return confirm('Are you sure you wish to public this comment?')" />      
                            <?php elseif($comment['type'] != 2): ?> 
                                <input type="submit" name="command" value="Hide" onclick="return confirm('Are you sure you wish to hide this comment?')" />  
                            <?php endif; ?>

                        <input type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this comment?')" />                   
                        </form>

                          <?php endif;?>


                <?php endforeach; ?>

                <?php if ($showPermission > 0): ?>
                     <form action="wd2/proj-master/proj-master/comment.php" method="post">
                            <fieldset>
                              <legend>New Comment</legend>
                                <label for="content">Content</label>
                                <textarea name="content" id="content"></textarea>
                              </p>
                              <input type="hidden" name="id" value="<?= $id?>"> 
                              <input type="hidden" name="title" value="<?= $title?>">
                              <input type="hidden" name="CurrentDate" value="<?= $comment['CurrentDate']?>">  
                              <input type="hidden" name="UserId" value="<?= $_SESSION['UserId']?>">
                            <input type="submit" name="command" value="Create" />
                              </p>
                            </fieldset>
                          </form>
                <?php else: ?>
                    <p> <i>when you log on, you can write a comment!</i> </p>  
                <?php endif; ?>

            </ul> 
        </div>

        <div>
           <ul>
                <?php foreach ($seasons as $season): ?>
                    <li>
                        Season <?=$season['SeasonNum']?> (<?=$season['Year']?>): <?=$season['NumEpisodes']?> episodes
                        <?php if ($showPermission == 1): ?>
                            <a href="/wd2/proj-master/proj-master/editseason/<?=$id?>/<?=$season['SeasonNum']?>/<?=$title?>">Edit</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                <?php if ($showPermission == 1): ?>
                    <a href="/wd2/proj-master/proj-master/createseason/<?=$id?>/<?=$nextSeason?>/<?=$title?>">Add new season</a>
                <?php endif; ?>
            </ul>
        </div>

        <form action="wd2/proj-master/proj-master/bookmarks.php" method="post">
            <fieldset>
                <input name="id" value="<?=$id?>" type="hidden">
                <input name="title" value="<?=$title?>" type="hidden">
                <input name="command" value="Bookmark this page" type="submit">
            </fieldset>
        </form>
    </section>
<?php include "templates/footer.php" ?>