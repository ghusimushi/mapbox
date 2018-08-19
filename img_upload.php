
<?php
    $upload_errors = array();

    if (empty($_FILES['img']['name'])) {
        $upload_errors[] = "Image can't be blank";

    } else {

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image

            $check = getimagesize($_FILES["img"]["tmp_name"]);
            if($check !== false) {
    //            echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $upload_errors[] = "File is not an image.";
                $uploadOk = 0;
            }

        // Check if file already exists
        if (!file_exists($target_file)) {
//            $upload_errors[] = "Sorry, file already exists.";
//            $uploadOk = 0;
            // Check file size
            if ($_FILES["img"]["size"] > 500000) {
                $upload_errors[] = "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                $upload_errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $upload_errors[] = "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                    //            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                } else {
                    $upload_errors[] = "Sorry, there was an error uploading your file.";
                }
            }
        }

    }
?>
