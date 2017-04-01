<?php

try
{
    $target_dir = "Images/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) 
    {
        if($_FILES["fileToUpload"]["tmp_name"] == '')
            $uploadOk = 0;
        else
        {
             $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                    //echo "Le fichier est une image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    //echo "Le fichier n'est pas une image.";
                    $uploadOk = 0;
                }                       
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        //echo "Désolé, ce fichier existe déjà.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        //echo "Désolé, ce fichier est trop grand.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        //echo "Désolé, seul les fichiers JPG, JPEG, PNG & GIF sont autorisés.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo(json_encode(array('Erreur'=>htmlentities("Erreur lors du téléchargement"))));
    // if everything is ok, try to upload file
    } 
    else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo(json_encode(array('Succes'=>htmlentities("Succès"))));
        } else {
            echo(json_encode(array('Erreur'=>htmlentities("Erreur lors du téléchargement."))));
        }
    }
}
catch(Exception $e)
{
    echo(json_encode(array('Erreur'=>htmlentities("Erreur lors du téléchargement"))));
}

?>
