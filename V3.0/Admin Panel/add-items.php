<?php
    session_start();
    //Verification

    if(!isset($_SESSION['logged-in']) || $_SESSION['logged-in'] !== true){
        header("Location: login.php");
        exit();
    }

    //Handling the Data

    if(isset($_POST['submit'])){

        //Getting items

        $jsonFile = '../data/products-info.json';

        $products = json_decode(file_get_contents($jsonFile), true);

        //Storing the data

        $fileName = $_POST['file_name'];

        $price = $_POST['price'];

        $description = $_POST['description'];

        $index = $_POST['index'];

        $main_image = preg_replace('/\s+/','',time().'_'.basename($_FILES['main_image']['name']));

        $gallery = [];

        //Validating the strings
        if($price === "" || $description === "" || $index === "" || $fileName == ""){
            $_SESSION['error'] = 'All fields must be filled!';
            header("Location:".$_SERVER['PHP_SELF']);
            exit();
        }


        //Validating the images
        if($_FILES['main_image']['name'] == '' || count($_FILES['gallery']['name']) == 0){
            $_SESSION['error'] = 'All fields must be filled!';
            header("Location:".$_SERVER['PHP_SELF']);
            exit();
        }
        $fileName = preg_replace('/\s+/','',$fileName);
        mkdir("../images/$fileName");
        mkdir("../images/$fileName/MainImage");
        mkdir("../images/$fileName/Gallery");
        $singleFolder = "../images/$fileName/MainImage";
        $galleryFolder = "../images/$fileName/Gallery";
        move_uploaded_file($_FILES['main_image']['tmp_name'], $singleFolder."/".$main_image);
        $mainFolder = $singleFolder."/".preg_replace('/\s+/','',$main_image);
        //LOOP FOR GALLERY
        for($i = 0; $i < count($_FILES['gallery']['name']); $i++){
            if($_FILES['gallery']['error'][$i] == 0){
                $gallery_name = time(). '_' . basename($_FILES['gallery']['name'][$i]);
                $gallery_name = preg_replace('/\s+/', '', $gallery_name);
                $gallery_path = $galleryFolder."/".$gallery_name;
                move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $gallery_path);
                $gallery[] = $gallery_path;
            }
        }
        //Inserting Data
        $products[$fileName] = [
            'img' => $mainFolder,
            'price' => $price,
            'index' => $index,
            'gallery' => $gallery,
            'description' => $description
        ];
        //Inserting into JSON then updating products-info.js using JSON
        file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT));
        $jsContent = "const products = " . json_encode($products, JSON_PRETTY_PRINT) . ";";
        file_put_contents('../data/products-info.js', $jsContent);

        $_SESSION['success'] = "Uploaded!";
        header("Location:".$_SERVER['PHP_SELF']);
        exit();
    }




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/AP.css">
    <title>Add Items</title>
</head>
<body>
    <div class = 'container'>
        <div class = 'form'>
            <?php
                if(isset($_SESSION['success'])){
                    echo "<p style= 'color:green'>". $_SESSION['success'] . "</p>";
                    unset($_SESSION['success']);
                }
                elseif(isset($_SESSION['error'])){
                    echo "<p style= 'color:red'>". $_SESSION['error'] . "</p>";
                    unset($_SESSION['error']);
                }
            ?>
            <h1>Add Items</h1>
            <form action="" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="main_image">Main Image:</label>
                    <input type="file" id = 'main_image' name = "main_image">
                </div>

                    <div>
                        <label for="images-upload">Choose Gallery:</label>
                        <input type="file" id="images-upload" name="gallery[]" accept="image/*" multiple>
                    </div>

                    <div>
                        <label for="price">Price:</label>
                        <input type="text" id = 'price' name = "price">
                    </div>
        

                    <div>
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" style="height: 20px"></textarea>
                    </div>
                
                    <div>
                        <label for="search">search words:</label>
                        <input type="text" id = 'search' name = "index">
                    </div>

                    <div>
                        <label for="file_name">File Name:</label>
                        <input type="text" id = 'file_name' name = "file_name">
                    </div>

                    <div class = 'button'>
                        <input type="submit" name = 'submit' value = 'submit'>
                        <a href="logout.php" style = 'margin-left: 10px'>LOG OUT</a>
                    </div>
            </form>
        </div>
    </div>
    <div id = 'imagesUploaded' class = "imagesUploaded">
        <div class = 'mainImage' id = 'mainShow'>
             <span style = 'font-size: 30px;font-weight:bold'>Main Image:</span>
        </div>
        <span style = 'font-size: 30px;font-weight: bold;'>Gallery:</span>
        <div class = 'gallery' id = 'showGallery'>
            
        </div>
    </div>
    <a href="showItems.php" class ="link">Show All Current items!</a>
</body>
<script src = 'scripts/script.js'></script>
</html>