<?php
    session_start();
    //Security
    if(!isset($_SESSION['login'])){
        die("Access Denied!");
    }
    //Establishing DB connection
    require_once("PDO.php");
    //Verifying Method
    if(isset($_POST['edit_id'])){
        //Getting ID
        $_SESSION['edit_id'] = $_POST['edit_id'];
    }

    //Getting the item Data
    $sql = "SELECT * FROM products WHERE product_id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":id" => $_SESSION['edit_id']
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $gallery = [];
    $gallery = json_decode(($row['gallery']), true);

   //Inserting edited item data
   if(isset($_POST['submit'])){
    $item = htmlentities($row['product_name']);
    //Grabbing Data
    $pathToStoreGallery = "../images/$item/Gallery/";

    $pathToStoreMainimg = "../images/$item/MainImage/";

    $NewMainImage = preg_replace('/\s+/','',time().'_'.basename($_FILES['main_image']['name']));

    $NewGallery = [];

    $NewPrice = $_POST['price'];

    $NewDescription = $_POST['description'];

    $NewIndex = $_POST['index'];

    $NewStock = $_POST['stock'];

    $once = 1; //Just to run delete gallery incase there was an upload

    //Grabbing New Gallery
    for($i = 0; $i < count($_FILES['gallery']['name']); $i++){
        if($_FILES['gallery']['error'][$i] == 0){
            // Check if first filename is not empty (file was selected)
            if(!empty($_FILES['gallery']['name'][$i]) && $once === 1) {
                 foreach(glob($pathToStoreGallery."*") as $file) {
                    if(is_file($file)) {
                    unlink($file);
                    }
                }   
            }
            $gallery_name = time(). '_' . basename($_FILES['gallery']['name'][$i]);
            $gallery_name = preg_replace('/\s+/', '', $gallery_name);
            $gallery_path = $pathToStoreGallery.$gallery_name;
            //Moving Gallery
            move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $gallery_path);
            //Storing each image path in array
            $NewGallery[] = $gallery_path; 
            $once = 2;
        }
    }
    $JSONED_gallery = json_encode($NewGallery);
    //Adding without Images REPLACE WITH SQL
    $sql = "UPDATE products SET 
            price = :price,
            description = :description,
            search_keyword = :search_keyword,
            stock = :stock
            WHERE product_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $_SESSION['edit_id'],
        ':price' => $NewPrice,
        ':description' => $NewDescription,
        ':search_keyword' => $NewIndex,
        ':stock' => $NewStock
    ]);

    //Gallery Insertion
    if(!empty($_FILES['gallery']['name'][0])){ 
        $sqlGallery = "UPDATE products SET gallery = :gallery WHERE product_id = :id";
        $stmtGallery = $pdo->prepare($sqlGallery);
        $stmtGallery->execute([
            'id' => $_SESSION['edit_id'],
            ":gallery" => $JSONED_gallery
        ]);
    }
    $Main_imagePath = $pathToStoreMainimg.$NewMainImage;
    if(!empty($_FILES['main_image']['name'])){
        //Delete old image
        foreach(glob($pathToStoreMainimg."*") as $file) {
            if(is_file($file)) {
            unlink($file);
             }
        }
        //Moving MainImage  

        move_uploaded_file($_FILES['main_image']['tmp_name'], $Main_imagePath);

        //Main Image Insertion
        $sqlMainimage = "UPDATE products SET main_image = :main_image WHERE product_id = :id";
        $stmtMainimage = $pdo->prepare($sqlMainimage);
        $stmtMainimage->execute([
            'id' => $_SESSION['edit_id'],
            ":main_image" => $Main_imagePath
        ]);
    }

    $_SESSION['success'] = "Updated!";
    header("Location: edit.php");
    exit();
}






    ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/AddItems.css">
    <title>EDIT</title>
</head>
<body>
    <div class = 'container'>
        <div class = 'form'>
            <h1>Edit Item</h1>
            <?php
                if(isset($_SESSION['success'])){
                    echo "<span style = 'color: green;'>". $_SESSION['success']. "</span>";
                    unset($_SESSION['success']);
                }
                ?>
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
                        <input type="text" id = 'price' name = "price" value = <?php echo htmlentities($row['price']);?>>
                    </div>
        

                    <div>
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" style="height: 20px"><?php echo htmlentities($row['description']) ?></textarea>
                    </div>
                
                    <div>
                        <label for="search">search words:</label>
                        <input type="text" id = 'search' name = "index" value = <?php echo htmlentities($row['search_keyword']) ?>>
                    </div>

                    <div>
                        <label for="Stock">Stock:</label>
                        <input type="text" id = "Stock" name = "stock" value = <?php echo htmlentities($row['stock']) ?>>
                    </div>

                    <div class = 'button'>
                        <input type="submit" name = 'submit' value = 'submit'>
                        <a href="logout.php" style = 'margin-left: 10px'>LOG OUT</a>
                    </div>
            </form>
        </div>
    </div>
    <div id = 'imagesUploaded' class = "imagesUploaded">
        <div class = 'mainImage'>
             <span style = 'font-size: 30px;font-weight:bold'>Main Image:</span>
             <div><img src= <?php echo htmlentities($row['main_image']) ?> alt="main_image" style = 'width: 200px; height: 145px;' id = 'mainShow'></div>
        </div>
        <span style = 'font-size: 30px;font-weight: bold;'>Gallery:</span>
        <div class = 'gallery' id = 'showGallery'>
                <?php 
                    foreach($gallery as $gallery_img){
                        echo "<div>";
                        echo "<img src =\"" .htmlentities($gallery_img). "\" style = 'width: 100%; height: 100px'>";
                        echo "</div>";
                    }
                    ?>
         </div>
    </div>
    <a href="showItems.php" class ="link">Show All Current items!</a>
</body>
        <script src = 'scripts/editScript.js'></script>
</html>