<?php
    /**
     * Home page 
     * Exhibit all series available in the database
     */

    include 'include/user_permission.php';

    // Connect to database
    require 'include/connect.php';

    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();


    // Sanitize and validate the Order by parameter
    $orderBy = filter_input(INPUT_GET, 'orderby', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $default_search='%';
    if (!$orderBy)
    {
        $orderBy = 'Name';
        unset($_SESSION['search']);
    }
    else if(isset($_SESSION['search']))
    {
        $default_search = '%'.$_SESSION['search'].'%';
    }
    // Get all the productions in the productions table
    // run a SELECT query
    $search_query = "SELECT ProductionId, Name, DateReleased, Country, LastUpdate, Description FROM productions WHERE LOWER(Name) LIKE :search OR LOWER(Description) LIKE :search ORDER BY $orderBy ASC";
    
    // prepare a PDOStatement object
    $statement = $db->prepare($search_query);
    $statement-> bindParam(':search', $default_search);
    //$statement-> bindParam(':orderBy', $orderBy);

    //the statement is not executed until after the Search takes place

?>

<?php include "templates/header.php" ?>
<li>
    <div id="sorting_form">
        <form method="post" action="">
        <label for="sorting">sorting</label>
            <select id="sorting" name="sorting">
                <option value="Action">Action</option>
                <option value="Animation">Animation</option>
                <option value="Drama">Drama</option>
                <option value="Family">Family</option>
            </select>
        <input type="submit" name="submit" value="Sorting" />
        </form>
    </div>
</li>
<?php include "search.php";
    //now execute the completed query
    $success = $statement->execute();

    // Fetch the result in a local variable
    $search_results = $statement->fetchAll();

        
 ?>

    <section>
        <?php if ($showPermission == 1): ?>
            <p><a href="wd2/proj-master/proj-master/create_production.php">Add New Production</a></p>
        <?php endif ?>
        <table class="table">
            <thead>
                <tr>
                    <th><a href="wd2/proj-master/proj-master/home/Name">Name</a></th>
                    <th><a href="wd2/proj-master/proj-master/home/DateReleased">Date Released</a></th>
                    <th><a href="wd2/proj-master/proj-master/home/Country">Country</a></th>
                    <th><a href="wd2/proj-master/proj-master/home/LastUpdate">Updated on</a></th>
                </tr>
            </thead>
            <tbody>
               <!--  <?php if($make): ?>
                    <p><?=$make?></p>
                <?php endif ?> -->
                <?php foreach ($search_results as $prod): ?>
                <tr>
                    <td><a href="wd2/proj-master/proj-master/show/<?= $prod['ProductionId'] ?>/<?= $prod['Name'] ?>"><?= $prod['Name'] ?></a></td>
                    <td><?= date("F j, Y", strtotime($prod['DateReleased'])) ?></td>
                    <td><?= $prod['Country'] ?></td>
                    <td><?= date("F j, Y", strtotime($prod['LastUpdate'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

