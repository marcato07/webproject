<?php
    /**
     * Web page with a form to input the show data
     */

    include 'include/user_permission.php';
    require __DIR__ . '/vendor/autoload.php';

    // Connect to database
    require 'include/connect.php';

    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();
?>


<?php include ("templates/header.php") ?>
    <section>
        <?php if ($showPermission == 1): ?>
            <form action="process" method="post">
                <fieldset>
                    <legend>New Production</legend>
                    <ol>
                        <li>
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text">
                        </li>
                        <li>
                            <label for="country">Country</label>
                            <input id="country" name="country" type="text">
                        </li>
                        <li>
                            <label for="datereleased">Date Released</label>
                            <input id="datereleased" name="datereleased" type="date">
                        </li>
                        <li>
                            <label for="description">Description</label>
                            <textarea id="description" name="description"></textarea>
                            <script>
                                // Replace the <textarea id="description"> with a CKEditor
                                // instance, using default configuration.
                                CKEDITOR.replace('description');
                            </script>
                        </li>
                        <li>
                            <input name="command" value="Create" type="submit">
                        </li>
                    </ol>
                </fieldset>
            </form>
        <?php else: ?>
            <p>You do not have enough permission.</p>
        <?php endif; ?>
    </section>
<?php include "templates/footer.php" ?>
