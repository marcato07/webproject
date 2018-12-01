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
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="/wd2/proj-master/proj-master/vendor/ckeditor/ckeditor/ckeditor.js"></script>
</head>
<body>

    <div class="w-75 p-3 container" style="background-color: #eee;">

    <header>
        <h1><a href="wd2/proj-master/proj-master/home">The Reviews</a></h1>
    </header>
    <div id="mainnavigation">

        <nav>
           <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a href="wd2/proj-master/proj-master/home" class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" role="tab" aria-controls="nav-home" aria-selected="true" >Home</a>

                <a href="wd2/proj-master/proj-master/bookmarks.php" class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" role="tab" aria-controls="nav-profile" aria-selected="false" >Bookmarks</a>
                <?php if ($showPermission == 0): ?>
                
                    <a href="wd2/proj-master/proj-master/signin/sign-on" class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" role="tab" aria-controls="nav-profile" aria-selected="false" >Sign On</a>

                    <a href="wd2/proj-master/proj-master/signin/Register" class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" role="tab" aria-controls="nav-profile" aria-selected="false" >Register</a>

                <?php else: ?>
                    <a href="wd2/proj-master/proj-master/signoff" class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" role="tab" aria-controls="nav-profile" aria-selected="false" >Sign Off</a>
                
                <?php if ($showPermission == 1): ?>
                    <a href="wd2/proj-master/proj-master/manageusers" class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" role="tab" aria-controls="nav-profile" aria-selected="false" >Manage Users</a>
                    
                <?php endif; ?>
                <?php endif; ?>
           
        </nav>
    </div>
    <div>
        <?php if ($showPermission > 0): ?>
            <div>You are logged as <?= $_SESSION['FirstName'] . ' ' . $_SESSION['LastName'] ?></div>
        <?php endif; ?>
    </div>
