<?php
    /**
     * Web page with a form to enter your credentials
     */

    include 'include/user_permission.php';

    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();

    if (isset($_GET['type']))
    {
        // Sanitize and validate the get parameter
        $type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
?>

<?php include ("templates/header.php") ?>
    <section>
        <form action="http://localhost:31337/wd2/proj-master/proj-master/authenticate.php" method="post">
            <fieldset>
                <?php if ($type == 'Register'): ?>
                    <legend>Register</legend>
                    <p>
                        <label for="firstName">First Name</label>
                        <input id="firstName" name="firstName" type="text">
                    </p>
                    <p>
                        <label for="lastName">Last Name</label>
                        <input id="lastName" name="lastName" type="text">
                    </p>
                    <p>
                        <label for="newusername">Username</label>
                        <input id="newusername" name="newusername" type="text">
                    </p>
                    <?php if ($showPermission == 1): ?>
                        <p>
                            <label for="permissiontype">Permission Type (1 for Admin, 2 for user)</label>
                            <input id="permissiontype" name="permissiontype" type="number" value="2">
                        </p>
                    <?php endif; ?>
                    <p>
                        <label for="newpassword">Password</label>
                        <input id="newpassword" name="newpassword" type="password">
                    </p>
                    <p>
                        <label for="newpasswordcheck">Repeat Password</label>
                        <input id="newpasswordcheck" name="newpasswordcheck" type="password">
                    </p>
                    <p>
                        <input name="command" value="Register" type="submit">
                    </p>
                <?php else: ?>
                    <legend>Login</legend>
                    <p>
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text">
                    </p>
                    <p>
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password">
                    </p>
                    <p>
                        <input name="command" value="Login" type="submit">
                    </p>
                <?php endif; ?>
            </fieldset>
        </form>
    </section>

