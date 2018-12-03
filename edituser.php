<?php
    /**
     * Exhibit all the information about a user allowing to edit
     */

    include 'include/user_permission.php';
    include 'include/page_navigation.php';

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
        redirectPage('/proj/home');
    }

    // Get the user passed by GET parameter
    // run a SELECT query
    $query = "SELECT FirstName, LastName, Password, Username, PermissionType FROM users WHERE UserId = $id";

    // prepare a PDOStatement object
    $statement = $db->prepare($query);

    // The query is now executed.
    $success = $statement->execute();

    // Fetch the result in a local variable
    $user = $statement->fetch();
?>

<?php include ("templates/header.php") ?>
    <section>
        <?php if ($showPermission != 0): ?>
            <form action="authenticate.php" method="post">
                <fieldset>
                    <legend>Edit User</legend>
                    <p>
                        <label for="firstName">First Name</label>
                        <input id="firstName" name="firstName" type="text" value="<?= $user['FirstName'] ?>">
                    </p>
                    <p>
                        <label for="lastName">Last Name</label>
                        <input id="lastName" name="lastName" type="text" value="<?= $user['LastName'] ?>">
                    </p>
                    <?php if ($showPermission == 1): ?>
                        <p>
                            <label for="permissiontype">Permission Type (1 for Admin, 2 for user)</label>
                            <input id="permissiontype" name="permissiontype" type="number" value="<?= $user['PermissionType'] ?>">
                        </p>
                    <?php endif; ?>
                    <p>
                        <label for="newpassword">New Password</label>
                        <input id="newpassword" name="newpassword" type="password">
                    </p>
                    <p>
                        <label for="newpasswordcheck">Repeat New Password</label>
                        <input id="newpasswordcheck" name="newpasswordcheck" type="password">
                    </p>
                    <p>
                        <input id="newusername" name="newusername" type="hidden" value="<?= $user['Username'] ?>">
                        <input name="id" value="<?=$id?>" type="hidden">
                        <input name="command" value="Update" type="submit">
                        <input name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this user?')" type="submit">
                    </p>
                </fieldset>
            </form>
        <?php else: ?>
            <p>You do not have enough permission.</p>
        <?php endif; ?>
    </section>
<?php include "templates/footer.php" ?>
