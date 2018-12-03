<?php
    /**
     * Manage users in the database
     * Exhibit all users available in the database
     */

    include 'include/user_permission.php';

    // Connect to database
    require 'include/connect.php';

    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();

    $orderBy = 'Username';

    if (isset($_GET['orderby']))
    {
        // Sanitize and validate the Order by parameter
        $orderBy = filter_input(INPUT_GET, 'orderby', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    // Get all the users in the users table
    // run a SELECT query
    $query = "SELECT UserId, FirstName, LastName, Password, Username, DateCreated, PermissionType FROM users ORDER BY $orderBy";

    // prepare a PDOStatement object
    $statement = $db->prepare($query);

    // The query is now executed.
    $success = $statement->execute();

    // Fetch the result in a local variable
    $users = $statement->fetchAll();
?>

<?php include ("templates/header.php") ?>
    <section>
        <?php if ($showPermission == 1): ?>
            <p><a href="project/signin/Register">Add New User</a></p>
        <?php endif; ?>
        <?php if ($showPermission == 1): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th><a href="project/manageusers/Username">Username</a></th>
                        <th><a href="project/manageusers/FirstName">First Name</a></th>
                        <th><a href="project/manageusers/LastName">Last Name</a></th>
                        <th><a href="project/manageusers/DateCreated">Date Created</a></th>
                        <th><a href="project/manageusers/PermissionType">Permission Type</a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><a href="project/edituser/<?= $user['UserId'] ?>/<?= $user['Username'] ?>"><?= $user['Username'] ?></a></td>
                            <td><?= $user['FirstName'] ?></td>
                            <td><?= $user['LastName'] ?></td>
                            <td><?= date("F j, Y", strtotime($user['DateCreated'])) ?></td>
                            <td><?= $user['PermissionType'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You do not have enough permission.</p>
        <?php endif; ?>
    </section>
</div>
<?php include "templates/footer.php" ?>
