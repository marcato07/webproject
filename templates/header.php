<?php
    /**
     * Header template
     */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/">
    <meta charset="utf-8">
    <title>The Reviews</title>
    <!-- <link rel="stylesheet" type="text/css" href="style.css" /> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="/wd2/proj-master/proj-master/vendor/ckeditor/ckeditor/ckeditor.js"></script>
</head>
<body>
    <header>
        <h1><a href="wd2/proj-master/proj-master/home">The Reviews</a></h1>
    </header>
    <div id="mainnavigation">
        <nav>
            <ul class="nav nav-tabs">
                <li><a href="wd2/proj-master/proj-master/home">Home</a></li>
                <li><a href="wd2/proj-master/proj-master/bookmarks.php">Bookmarks</a></li>
                <?php if ($showPermission == 0): ?>
                    <li><a href="wd2/proj-master/proj-master/signin/sign-on">Sign On</a></li>
                    <li><a href="wd2/proj-master/proj-master/signin/Register">Register</a></li>
                <?php else: ?>
                    <li><a href="wd2/proj-master/proj-master/signoff">Sign Off</a></li>
                    <?php if ($showPermission == 1): ?>
                        <li><a href="wd2/proj-master/proj-master/manageusers">Manage Users</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <div>
        <?php if ($showPermission > 0): ?>
            <div>You are logged as <?= $_SESSION['FirstName'] . ' ' . $_SESSION['LastName'] ?></div>
        <?php endif; ?>
    </div>
