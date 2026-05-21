<?php
    session_start();
    if(!isset($_SESSION['logged-in']) || $_SESSION['logged-in'] !== true){
        header("Location: login.php");
        exit();
    }
    //Reading JSON
    $JSON = '../data/products-info.json';

    $products = json_decode(file_get_contents($JSON), true);
    $item = $_GET['name'];

    //Displaying item Data
    foreach($products as $itemName => $itemData){
        if($item == $itemName){
            $oldMainImage = $itemData['img'];
            $oldPrice = $itemData['price'];
            $oldDescription = $itemData['description'];
            $oldGallery = $itemData['gallery'];
            $oldIndex = $itemData['index'];
            $oldStock = $itemData['stock'];
        }
    }
    //Inserting edited item data
    if(isset($_POST['submit'])){
        //Grabbing Data
        $pathToStore = "../images/$item/";

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
                     foreach(glob($pathToStore."Gallery/*") as $file) {
                        if(is_file($file)) {
                        unlink($file);
                        }
                    }   
                }
                $gallery_name = time(). '_' . basename($_FILES['gallery']['name'][$i]);
                $gallery_name = preg_replace('/\s+/', '', $gallery_name);
                $gallery_path = $pathToStore."Gallery/$gallery_name";
                //Moving Gallery
                move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $gallery_path);
                //Storing each image path in array
                $NewGallery[] = $gallery_path;
                $once = 2;
            }
        }
        //Adding without Images
        $products[$item] = array_merge($products[$item], [
            'price' => $NewPrice,
            'description' => $NewDescription,
            'index' => $NewIndex,
            'stock' => $NewStock
        ]);

        if(!empty($_FILES['gallery']['name'][0])){
            $products[$item] = array_merge($products[$item],[
                'gallery' => $NewGallery
            ]);
        }
        if(!empty($_FILES['main_image']['name'])){
            //Delete old image
            foreach(glob($pathToStore."MainImage/*") as $file) {
                if(is_file($file)) {
                unlink($file);
                 }
            }

            $products[$item] = array_merge($products[$item], [
                'img' => $pathToStore."MainImage/$NewMainImage"
            ]);
        }
        //Moving MainImage

        move_uploaded_file($_FILES['main_image']['tmp_name'], $pathToStore."MainImage/$NewMainImage");

        file_put_contents($JSON, json_encode($products, JSON_PRETTY_PRINT));

        $UpdatedProducts = 'const products = '.json_encode($products, JSON_PRETTY_PRINT) . ';';

        file_put_contents("../data/products-info.js", $UpdatedProducts);

        $_SESSION['updated'] = "Updated!";
        header("Location: edit.php?name=$item");
        exit();
    }



    

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/AP.css">
    <title>EDIT</title>
</head>
<body>
    <div class = 'container'>
        <div class = 'form'>
            <?php
                if(isset($_SESSION['updated'])){
                    echo "<p style= 'color:green'>". $_SESSION['updated'] . "</p>";
                    unset($_SESSION['updated']);
                }
            ?>
            <h1>Edit Item <?php echo $item?></h1>
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
                        <input type="text" id = 'price' name = "price" value = '<?php echo $oldPrice;?>'>
                    </div>
        

                    <div>
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" style="height: 20px"><?php echo $oldDescription;?></textarea>
                    </div>
                
                    <div>
                        <label for="search">search words:</label>
                        <input type="text" id = 'search' name = "index" value = '<?php echo $oldIndex;?>'>
                    </div>

                    <div>
                        <label for="Stock">Stock:</label>
                        <input type="text" id = "Stock" name = "stock" value = '<?php echo $oldStock;?>'>
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
             <div><img src="<?php echo $oldMainImage?>" alt="" style = 'width: 200px; height: 145px;' id = 'mainShow'></div>
        </div>
        <span style = 'font-size: 30px;font-weight: bold;'>Gallery:</span>
        <div class = 'gallery' id = 'showGallery'>
                <?php
                    foreach($oldGallery as $oldGalleryImg){
                        echo "<div><img src = ".$oldGalleryImg."></div>";
                    }


                ?>
         </div>
    </div>
    <a href="showItems.php" class ="link">Show All Current items!</a>
</body>
        <script src = 'scripts/editScript.js'></script>
</html>