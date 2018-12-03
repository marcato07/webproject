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
    

    if(!empty($_POST['sorting']))
    {
    	$val = '%'.$_POST['sorting'].'%';
    	echo $val;

    	//the statement is not executed until after the Search takes place
   
    }
    else if(empty($_POST['sorting']))
    {
    	$val = '%';
    }

    
    $search_query = "SELECT ProductionId, Name, DateReleased, Country, LastUpdate, Description, genre FROM productions WHERE LOWER(Name) LIKE :search AND genre LIKE :val OR LOWER(Description) LIKE :search AND genre LIKE :val ORDER BY $orderBy ASC";
    
    // prepare a PDOStatement object
    $statement = $db->prepare($search_query);
    $statement-> bindParam(':search', $default_search);
    $statement-> bindParam(':val', $val);
    //$statement-> bindParam(':orderBy', $orderBy);

    $query ="SELECT genre
            FROM category";
    // prepare a PDOStatement object
    $statements = $db->prepare($query);
    // The query is now executed.
    $success = $statements->execute();
    $categories= $statements->fetchAll();


?>

<?php include "templates/header.php" ?>

<?php include "search.php";
    //now execute the completed query
    $success = $statement->execute();

    // Fetch the result in a local variable
    $search_results = $statement->fetchAll();

        
 ?>

    <section>
        <?php if ($showPermission == 1): ?>
            <p><a href="project/create_production.php">Add New Production</a></p>
            <p><a href="project/category.php">Add New Category</a></p>
        <?php endif ?>
        <table class="table">
            <thead>
                <tr>
                    <th><a href="project/home/Name">Name</a></th>
                    <th><a href="project/home/DateReleased">Date Released</a></th>
                    <th><a href="project/home/Country">Country</a></th>
                    <th><a href="project/home/LastUpdate">Updated on</a></th>
                    <th><a href=""> Genre</a></th>
                </tr>
            </thead>
            <tbody>
               <!--  <?php if($make): ?>
                    <p><?=$make?></p>
                <?php endif ?> -->
                <?php foreach ($search_results as $prod): ?>
                <tr>
                    <td><a href="project/show/<?= $prod['ProductionId'] ?>/<?= $prod['Name'] ?>"><?= $prod['Name'] ?></a></td>
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

