<?php 
    session_start();
    //Establish a connection to DB
    require("PDO.php");
    //Security
    if(!isset($_SESSION['login'])){
        die("Access Denied");
    }
    //Handling Edit redirection
    if(isset($_POST['edit'])){
        $_SESSION['edit-id'] = $_POST['id'];
        header("Location: edit.php");
    }
    //Gallery for array of images
    $gallery = [];
    //Requesting the Data
    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
    //Making the table Head
    echo '<div class = "table-container"><table class = "table"><tr><th>Item Name</th><th>Main Image</th><th>Price</th><th>Key Words</th><th>Stock</th><th>Description</th><th>Gallery</th><th>Action</th></tr>';
    //Echoing the data row by row
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $gallery = json_decode(($row['gallery']));
        echo "<tr><td>". htmlentities($row['product_name']). "</td><td><img src = ".htmlentities($row['main_image']). "></td><td>".htmlentities($row['price']). "</td><td>".htmlentities($row['search_keyword']). "</td>";
        echo "<td>" . htmlentities( $row['stock']) . "</td><td><textarea>" .htmlentities( $row['description']) . "</textarea></td>";
        //Gallery output
        echo "<td><div class = gallery>";
        foreach($gallery as $gallery_img){
            echo "<img src =". htmlentities($gallery_img). ">";
        }
        echo "</div></td>";
        //From for Deletion
        echo "<td><form action = 'delete.php' method = 'POST' style = 'display: inline;'>";
        echo "<input type = 'hidden' name = 'delete_id' value = ". htmlentities($row['product_id']).">";
        echo "<button type = 'submit' onclick = 'return confirm(\"Delete this row?\")' style = 'color: red; margin-right: 10px; ; cursor: pointer'>&#10008;</button></form>";
        //Form for Editing
        echo "<form action = 'edit.php' method = 'POST' style = 'display: inline;'>";
        echo "<input type = 'hidden' name = 'edit_id' value = ". htmlentities($row['product_id']).">";
        echo "<button type = 'submit' style = 'color:green; cursor: pointer;'>&#x1F589;</button></td></form>";
    }
    echo "</table></div>"

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/showitems.css">
    <title>Items</title>
</head>
<body>
    <header class = 'goBack'> 
        <a href = 'add-items.php' class = link>Add more items</a>
        <a href = '../html/index.html' class = linkStore>View Store</a>
    </header>
</body>
   
</html>
