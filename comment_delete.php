<?php
    /**
     * Exhibit all the information about a show
     */

    include 'include/user_permission.php';
    include 'include/validations.php';

    // Connect to database
    require 'include/connect.php';

    // start session
    session_start();

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $UserId= filter_input(INPUT_POST, 'UserId', FILTER_SANITIZE_NUMBER_INT);
    $Username = $_SESSION['Username'];
    $CurrentDate= filter_input(INPUT_POST, 'CurrentDate', FILTER_SANITIZE_STRING);
    // Get the permission level
    $showPermission = getPermission();
    $command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    
    // Sanitize and validate the ID
        if ($id == null)
    {
        // Redirect user to another page
        // header('Location: wd2/proj-master/proj-master/home');
    }

        if ($command == "Delete" && $showPermission > 0){
            
            //  Build the parameterized SQL blog and bind to the above sanitized values.

            $comments="DELETE FROM comments WHERE ProductionId=:id AND CurrentDate=:CurrentDate";
            $statement = $db->prepare($comments);
            //  Bind values to the parameters
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->bindValue(':CurrentDate', $CurrentDate);
            
            $statement->execute();

            header('Location: /wd2/proj-master/proj-master/show/'.$id.'/'.$title);
            
    
        } 
        else if ($command == "Hide" || $command == "Public" && ($showPermission == 1)){
            
            //  Build the parameterized SQL blog and bind to the above sanitized values.
            if($command == "Hide"){
                $type=2;
            }
            else{
                $type=1;
            }


            $comments="UPDATE comments SET type=:type WHERE ProductionId=:id AND CurrentDate=:CurrentDate";
            $statement = $db->prepare($comments);
            //  Bind values to the parameters
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->bindValue(':type', $type, PDO::PARAM_INT);
            $statement->bindValue(':CurrentDate', $CurrentDate);
            
            $statement->execute();

            header('Location: /wd2/proj-master/proj-master/show/'.$id.'/'.$title);
            
    
        } 

?>
