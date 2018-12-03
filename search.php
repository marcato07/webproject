<?php
	//requirements from parent script:
	// must have created $statement with a query
	// $statement must have PDO param :search
	// must have already bound :search to a variable containing '%'
	
	//this script:
	// overwrites any variable called $name
	
	//after this is included, execute the query normally


	//$make = '';
	$search_url='';
	$search_query = strtolower(filter_input(INPUT_POST, 'name'));
	if(isset($_POST['name']) ){
		//$make = '<h4>No match found!</h4>';
		//$sele = "SELECT * FROM $search_table WHERE $search_column LIKE '%$name%' ORDER BY $orderBy";
            // prepare a PDOStatement object
			$name = '%'.$search_query.'%';
			$_SESSION['search'] = $search_query;
		    //$statement = $db->prepare($search_query);
		    $statement-> bindParam(':search', $name);
		    //$statement-> bindParam(':orderBy', $orderBy);

            // The query is now executed.
            //$success = $statement->execute();

            // Fetch the result in a local variable
            //$search_results = $statement->fetchAll();
		//$search_results = mysql_query($sele);
		
		// if(($make = sizeof($search_results)) > 0){
		// 	foreach($search_results as $row){
		// 	echo '<h4> Id						: '.$row['ProductionId'];
		// 	echo '<br> name						: '.$row['Name'];
		// 	echo '</h4>';
		// 	}
		// }else{
		// 	echo'<h2> Search Result</h2>';
		// 	print ($make);
		// }
		   // $search_url = "/search/$search_query";
	}
?>

    <div id="searching_form">
        <form method="post">
        	<!-- <label for="sorting">sorting</label> -->
            <select id="sorting" name="sorting">
                <option value="">All</option>
                
                <?php foreach ($categories as $category): ?>
                	<option value="<?=$category['genre']?>"> <?=$category['genre']?> </option>
	            <?php endforeach; ?>

            </select>

            <input type="text" name="name">
            <input class="btn btn-info" type="submit" name="submit" value="Search" />
        </form>
    </div>
