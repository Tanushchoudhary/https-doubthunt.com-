<?php
$targetDirectory = "uploads/"; // Directory where you want to store uploaded files

if(isset($_POST["submit"])) {
    $fileName = $_FILES["fileToUpload"]["name"];
    $fileTmpName = $_FILES["fileToUpload"]["tmp_name"];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Generate a random filename
    $randomName = bin2hex(random_bytes(8)) . '.' . $fileType;
    $targetFile = $targetDirectory . $randomName;

    $uploadOk = 1;

    // Check if file is an actual image
    $check = getimagesize($fileTmpName);
    if($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Allow certain file formats (only images: JPEG, JPG, PNG, GIF)
    if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
    && $fileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($fileTmpName, $targetFile)) {
            $api_url = 'https://api.ocr.space/parse/imageurl?apikey=K85691101788957&url=https://doubthunt.com/uploads/'.$randomName;
$response = file_get_contents($api_url);
$data = json_decode($response, true);
if ($data && isset($data['ParsedResults'][0]['ParsedText'])) {
    $parsed_text = $data['ParsedResults'][0]['ParsedText'];
    nl2br($parsed_text);
    echo '<script>window.location.href = "https://doubthunt.com/send.php?quiz=' . urlencode($parsed_text) . '&image=' . urlencode($randomName) . '";</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=https://doubthunt.com/send.php?quiz=' . urlencode($parsed_text) . '&image=' . urlencode($randomName) . '"></noscript>';
    exit;
} else {
    echo "Failed to retrieve parsed text.";
}

        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}