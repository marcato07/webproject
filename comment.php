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

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $UserId= filter_input(INPUT_POST, 'UserId', FILTER_SANITIZE_NUMBER_INT);
    $Username = $_SESSION['Username'];
    //filter_input(INPUT_POST, 'Username', FILTER_SANITIZE_SPECIAL_CHARS);

    // Get the permission level
    $showPermission = getPermission();

    
    
    // Sanitize and validate the ID
        if ($id == null)
    {
        // Redirect user to another page
        header('Location: wd2/proj-master/proj-master/home');
    }

       
        $comments = "SELECT * FROM comments";
        $statement = $db->prepare($comments);
        $statement->execute(); 

        if ($_POST && isset($_POST['content']))
        {
            if(!strlen($_POST['content'])>=1) {
              //waringing about length
             // header("Location: /wd2/proj-master/proj-master/home");

            }
            else{
            //  Sanitize user input to escape HTML entities and filter out dangerous characters.
            $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            //  Build the parameterized SQL blog and bind to the above sanitized values.

            $comments="INSERT INTO comments (content, ProductionId, UserId, Username) VALUES (:content, :id, :UserId, :Username)";
            $statement = $db->prepare($comments);
            //  Bind values to the parameters
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->bindValue(':content', $content);
            $statement->bindValue(':UserId', $UserId, PDO::PARAM_INT);
            $statement->bindValue(':Username', $Username);
            
            $statement->execute();

            header('Location: /wd2/proj-master/proj-master/show/'.$id.'/'.$title);
            }
    
        } 

?>
