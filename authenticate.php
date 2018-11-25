<?php
    /*
     * Get the data posted from signin and look for the credentials
     */

    include 'include/user_permission.php';
    include 'include/validations.php';
    //include 'include/page_navigation.php';

    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();

    // Extract the data POSTed sanitizing user input
    $command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($command == 'Login')
    {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    else if ($command == 'Register' || $command == 'Update')
    {
        $username = filter_input(INPUT_POST, 'newusername', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'newpassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $passwordcheck = filter_input(INPUT_POST, 'newpasswordcheck', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (isset($_POST['permissiontype']))
        {
            // Sanitize and validate the the parameter
            $permissionType = validateIntInput(INPUT_POST, 'permissiontype');
        }
        else
        {
            $permissionType = 2;
        }
    }

    $error = '';

    if ($command == 'Login' || $command == 'Register')
    {
        if (strlen($username) < 1 || strlen($password) < 1)
        {
            $error = 'Username or password is too short.';
        }
    }

    if (($command == 'Register' || $command == 'Update') && ($password != $passwordcheck))
    {
        $error = 'Passwords do not match. Try again.';
    }

    if (($command == 'Register' || $command == 'Update') && (strlen($firstName) < 1 || strlen($lastName) < 1))
    {
        $error = 'First name or last name is too short.';
    }

    if ($error == '')
    {
        require 'include/connect.php';

        if ($command == 'Login')
        {
            // Get the user in the users table
            // run a SELECT query
            $query = "SELECT UserId, Username, FirstName, LastName, Password, PermissionType FROM users";

            // prepare a PDOStatement object
            $statement = $db->prepare($query);

            // The query is now executed.
            $success = $statement->execute();

            // Fetch the result in a local variable
            $users = $statement->fetchAll();

            $userFound = false;

            // Check if a user was found
            foreach ($users as $user)
            {
                // Compare passwords
                //if ($password == $user['Password'] && $username == $user["Username"])
                if (password_verify($password, $user['Password']) && $username == $user["Username"])
                {
                    // Store user info data in session variables
                    $_SESSION['UserId'] = $user['UserId'];
                    $_SESSION['Username'] = $user['Username'];
                    $_SESSION['FirstName'] = $user['FirstName'];
                    $_SESSION['LastName'] = $user['LastName'];
                    $_SESSION['PermissionType'] = $user['PermissionType'];

                    $userFound = true;

                    break;
                }
            }

            if ($userFound == false)
            {
                $error = 'User not found or password incorrect.';
            }
        }
        elseif ($command == 'Register')
        {
            // Check if username already exists
            // Get the user in the users table
            // run a SELECT query
            $query = "SELECT Username, FirstName, LastName, Password, PermissionType FROM users";

            // prepare a PDOStatement object
            $statement = $db->prepare($query);

            // The query is now executed.
            $success = $statement->execute();

            // Fetch the result in a local variable
            $users = $statement->fetchAll();

            // Check if a user was found
            foreach ($users as $user)
            {
                // Compare usernames
                if ($username == $user["Username"])
                {
                    $error = 'Username already exists.';
                    break;
                }
            }

            if ($error == '')
            {
                // Add user to the database with guest permission
                $query = "INSERT INTO users (FirstName, LastName, Username, Password, PermissionType) values (:firstName, :lastName, :username, :password, :permissionType)";
                $statement = $db->prepare($query);
                $statement->bindValue(':firstName', $firstName);
                $statement->bindValue(':lastName', $lastName);
                $statement->bindValue(':username', $username);
                $statement->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
                $statement->bindValue(':permissionType', $permissionType);

                $statement->execute();
            }
        }
        elseif ($command == 'Update')
        {
            // Sanitize and validate the ID
            $id = validateIntInput(INPUT_POST, 'id');
            if ($id == null)
            {
                // ID error
                $error = 'ID not valid';
            }
            else
            {
                if ($password != '')
                {
                    // Update the user in the database
                    $query = "UPDATE users SET FirstName = :firstname, LastName = :lastName, Username = :username, Password = :password, PermissionType = :permissionType WHERE UserId = $id";
                    $statement = $db->prepare($query);
                    $statement->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
                }
                else
                {
                    // Update the user in the database
                    $query = "UPDATE users SET FirstName = :firstname, LastName = :lastName, Username = :username, PermissionType = :permissionType WHERE UserId = $id";
                    $statement = $db->prepare($query);
                }

                $statement->bindValue(':firstname', $firstName);
                $statement->bindValue(':lastName', $lastName);
                $statement->bindValue(':username', $username);
                $statement->bindValue(':permissionType', $permissionType);
                $statement->execute();

                
            }
        }
        elseif ($command == 'Delete')
        {
            // Sanitize and validate the ID
            $id = validateIntInput(INPUT_POST, 'id');
            if ($id == null)
            {
                // ID error
                $error = 'ID not valid';
            }
            else
            {
                // Delete the post from the database
                $query = "DELETE FROM users WHERE UserId = $id";
                $statement = $db->prepare($query);
                $statement->execute();
            }
        }

        if ($error == '')
        {
            // Redirect user to another page
            header('Location: http://localhost:31337/wd2/proj-master/proj-master/index.php');
            exit;
        }
    }
?>

<?php include ("templates/header.php") ?>
    <section>
        <p><?= $error ?></p>
    </section>
