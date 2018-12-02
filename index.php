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
        echo $default_search;
    }
    // Get all the productions in the productions table
    // run a SELECT query
    $search_query = "SELECT ProductionId, Name, DateReleased, Country, LastUpdate, Description, genre FROM productions WHERE LOWER(Name) LIKE :search OR LOWER(Description) LIKE :search ORDER BY $orderBy ASC";
    
    // prepare a PDOStatement object
    $statement = $db->prepare($search_query);
    $statement-> bindParam(':search', $default_search);
    //$statement-> bindParam(':orderBy', $orderBy);

    if(isset($_GET['sorting']))
    {
    	$value = $_GET['sorting'];
    	echo $value;
    }
    //the statement is not executed until after the Search takes place
    $query ="SELECT genre
            FROM category WHERE genre LIKE %$value% ORDER BY genre";
    // prepare a PDOStatement object
    $statement = $db->prepare($query);
    // The query is now executed.
    $success = $statement->execute();
    $category= $statement->fetchAll();

?>

<?php include "templates/header.php" ?>
<li>
    <div id="sorting_form">
        <form method="get" action="">
        <label for="sorting">sorting</label>
            <select id="sorting" name="sorting">
                <option value="">All</option>
                <option value="Animation">Animation</option>
                <option value="Drama">Drama</option>
                <option value="Family">Family</option>
            </select>
        <input class="btn btn-info" type="submit" name="submit" value="sorting" />
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
                    <th><a href=""> Genre</a></th>
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
                    <td><?= $prod['genre']?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </section>

