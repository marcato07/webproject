<?php
    /**
     * Upload an image and process it
     */

    include 'include/user_permission.php';
    //include 'ImageResize.php';
    //use \Gumlet\ImageResize;

    require __DIR__ . '/vendor/autoload.php';

    include 'vendor/eventviva/php-image-resize/lib/ImageResize.php';
    use \Eventviva\ImageResize;

    function file_is_allowed($temporary_path, $new_path)
    {
        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
        $allowed_file_extensions = ['gif', 'jpg', 'png'];

        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $actual_mime_type = finfo_file($finfo, $temporary_path);

        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

        return $file_extension_is_valid && $mime_type_is_valid;
    }

    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads')
    {
        $current_folder = dirname(__FILE__);
        $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
        return join(DIRECTORY_SEPARATOR, $path_segments);
    }

    // Connect to database
    require 'include/connect.php';

    // start session
    session_start();

    // Get the permission level
    $showPermission = getPermission();

    $errorMsg = '';

    // Check if the uploads folder exist
    if (!is_dir('uploads'))
    {
        // Create uploads folder if it does not exist
        mkdir('uploads');
    }

    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);

    if ($image_upload_detected)
    {
        $image_filename       = $_FILES['image']['name'];
        $temporary_image_path = $_FILES['image']['tmp_name'];
        $new_image_path       = file_upload_path($image_filename);

        // Sanitize and validate the ID
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if (filter_var($id, FILTER_VALIDATE_INT) == false)
        {
            // Redirect user to another page
            header('Location: wd2/proj-master/proj-master/');
            exit;
        
        }

        // Sanitize and validate the title
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);

        if (file_is_allowed($temporary_image_path, $new_image_path))
        {
            move_uploaded_file($temporary_image_path, $new_image_path);

            // Resize image
            //$imageResize = new ImageResize($new_image_path);
            //$imageResize->resizeToBestFit(2000, 1200);
            //$imageResize->save($new_image_path);

            $imageResize = new ImageResize($new_image_path);
            $imageResize->resizeToBestFit(300, 400);
            $imageResize->save(file_upload_path('s_'.$image_filename));

            // Update the production in the database
            $query = "UPDATE productions SET Image = :image WHERE ProductionId = $id";
            $statement = $db->prepare($query);
            $statement->bindValue(':image', 's_'.$image_filename);
            $statement->execute();

            // Redirect user
            // Redirect user to another page
            header('Location: /wd2/proj-master/proj-master/show/'.$id.'/'.$title);

            // Exit the script normally
            exit(0);
        }
        else
        {
            $errorMsg = 'File not allowed';
        }
    }

    // Sanitize and validate the ID
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (filter_var($id, FILTER_VALIDATE_INT) == false)
    {
        // Redirect user to another page
         header('Location: /wd2/proj-master/proj-master/index.php');
    }

    // Sanitize and validate the title
    $title = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
?>

<?php include ("templates/header.php") ?>
    <section>
        <?php if ($showPermission == 1): ?>
            <form method="post" enctype="multipart/form-data">
                <fieldset>
                    <ol>
                        <li>
                            <label for="image">Image Filename:</label>
                            <input type="file" name="image" id="image">
                        </li>
                    </ol>
                </fieldset>
                <fieldset>
                    <ol>
                        <li>
                            <input name="id" value="<?=$id?>" type="hidden">
                            <input name="title" value="<?=$title?>" type="hidden">
                            <input type="submit" name="submit" value="Upload Image">
                        </li>
                    </ol>
                </fieldset>
            </form>
            <?php if ($errorMsg != ''): ?>
                <h4><?= $errorMsg ?></h4>
            <?php endif; ?>
        <?php else: ?>
            <p>You do not have enough permission.</p>
        <?php endif; ?>
    </section>
<?php include "templates/footer.php" ?>
