<?php
    session_start();
    //Security
    if(!isset($_SESSION['login'])){
        die("Access Denied");
    }
    //Establish a connection to the DataBase
    require_once('PDO.php');

    if(isset($_POST['submit'])){
        //Grabbing Data
        
        $main_image = preg_replace('/\s+/', "",time()."_". basename($_FILES['main_image']['name']));

        $price = $_POST['price'];

        $description = $_POST['description'];

        $search_keyword = $_POST['search_keyword'];

        $gallery = [];

        $stock = $_POST['stock'];

        $file_name =  preg_replace('/\s+/',"",$_POST['file_name']);

        //Verify Data
        if($price === "" || $description === "" || $search_keyword === "" || $file_name == "" || $stock == ""){
            $_SESSION['error'] = 'All fields must be filled!';
            header("Location:".$_SERVER['PHP_SELF']);
            exit();
        }

        if(!is_numeric($price) || !is_numeric($stock)){
            $_SESSION['error'] = "Price Must Be a Number!";
            header("Location: add-items.php");
            exit();
        }

        //Verifying images
        if($_FILES['main_image']['name'] == ''){
            $_SESSION['error'] = "Please upload the main image";
            header("Location: add-items.php");
            exit();
        }

        if($_FILES['gallery']['name'][0] == ''){
            $_SESSION['error'] = "Please upload Gallery";
            header("Location: add-items.php");
            exit();
        }

        //Validating Folder
        if(file_exists("../images/$file_name")){
            header("Location: add-items.php");
            $_SESSION['error'] = "File Name already exists!";
            exit();
        }
        //making the item folder
            mkdir("../images/$file_name");

            mkdir("../images/$file_name/MainImage");

            mkdir("../images/$file_name/Gallery");
        //loop for gallery

        for($i = 0; $i < count($_FILES['gallery']['name']); $i++){
            if($_FILES['gallery']['error'][$i] != 0){
                header('Location: add-items.php');
                $_SESSION['error'] = 'Error in one or more of the Gallery images';
                exit();
            }
            $gallery_name = preg_replace('/\s+/', "", basename(time()."_".$_FILES['gallery']['name'][$i]));

            $path_Gallery = "../images/$file_name/Gallery/$gallery_name";
            //moving each gallery image
            move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $path_Gallery);

            $gallery[] = $path_Gallery;
        }
        //moving Main Images
        $path_Main = "../images/$file_name/MainImage/$main_image";

        //JSON gallery array
         $JSONED_gallery = json_encode($gallery);

        move_uploaded_file($_FILES['main_image']['tmp_name'], $path_Main);
        //Adding to the DataBase
        $sql = "INSERT INTO products(product_name, main_image, gallery, price, search_keyword, description, stock)
        VALUES(:product_name, :main_image, :gallery, :price, :search_keyword, :description, :stock)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':product_name' => $file_name,
            ':main_image' => $path_Main,
            ':gallery' => $JSONED_gallery,
            ':price' => $price,
            ':search_keyword' => $search_keyword,
            ':description' => $description,
            ':stock' => $stock
        ]);

        header("Location: add-items.php");
        $_SESSION['success'] = "Item Added!";
        exit();

    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/AddItems.css">
    <title>Add Items</title>
</head>
<body>
    <div class = 'container'>
        <div class = 'form'>
            <h1>Add Items</h1>
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
            <form action="" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="main_image">Main Image:</label>
                    <input type="file" id = 'main_image' name = "main_image">
                </div>

                    <div>
                        <label for="gallery">Choose Gallery:</label>
                        <input type="file" id="gallery" name="gallery[]" accept="image/*" multiple>
                    </div>

                    <div>
                        <label for="price">Price($):</label>
                        <input type="text" id = 'price' name = "price">
                    </div>
        

                    <div>
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" style="height: 20px"></textarea>
                    </div>
                
                    <div>
                        <label for="search">search words:</label>
                        <input type="text" id = 'search' name = "search_keyword">
                    </div>

                    <div>
                        <label for="stock">Stock:</label>
                        <input type="text" id = 'stock' name = 'stock'>
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
<script src = 'scripts/AddItems.js'></script>
</html>