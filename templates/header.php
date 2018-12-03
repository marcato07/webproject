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
    <script src="/project/vendor/ckeditor/ckeditor/ckeditor.js"></script>
</head>
<body>

    <div class="w-75 p-3 container" style="background-color: #eee;">
    
    <header>
        <h1><a href="project/home">The Reviews</a></h1>
    </header>
    <div id="mainnavigation">

        <nav>
           <div class="nav nav-tabs" id="nav-tab" role="tablist">

                <a href="project/home" class="nav-item nav-link active" data-toggle="tab" role="tab" aria-selected="true" >Home</a>

                <a href="project/bookmarks.php" class="nav-item nav-link"  data-toggle="tab" role="tab"  aria-selected="true" >Bookmarks</a>
                <?php if ($showPermission == 0): ?>
                
                    <a href="project/signin/sign-on" class="nav-item nav-link"  data-toggle="tab" role="tab"  aria-selected="true" >Sign On</a>

                    <a href="project/signin/Register" class="nav-item nav-link"  data-toggle="tab" role="tab"  aria-selected="true" >Register</a>

                <?php else: ?>
                    <a href="signoff" class="nav-item nav-link"  data-toggle="tab" role="tab"  aria-selected="true" >Sign Off</a>
                
                <?php if ($showPermission == 1): ?>
                    <a href="project/manageusers" class="nav-item nav-link"  data-toggle="tab" role="tab"  aria-selected="true" >Manage Users</a>
                    
                <?php endif; ?>
                <?php endif; ?>
        </div>
    </nav>
 </div> 

    <div>
        <?php if ($showPermission > 0): ?>
            <div>You are logged as <?= $_SESSION['FirstName'] . ' ' . $_SESSION['LastName'] ?></div>
        <?php endif; ?>
    </div>
