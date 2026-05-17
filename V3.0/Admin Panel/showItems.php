<?php
    session_start();
    if($_SESSION['logged-in'] !== true || !isset($_SESSION['logged-in'])){
        header("Location: login.php");
        exit();
    }
    $jsonFile = '../data/products-info.json';

    $products = json_decode(file_get_contents($jsonFile), true);
    echo '<table class = "table"><tr><th>Item Name</th><th>Main Image</th><th>Price</th><th>Search</th><th>Description</th><th>Gallery</th></tr>';
    
    foreach ($products as $itemName => $itemData) {
        echo "<tr><td>$itemName <a href = 'edit.php?name=$itemName' class = 'edit'>&#9997;</a>"."   <span class = 'delete' onclick = remove('$itemName')>&#10060;</span></td><td><img src = ".$itemData['img']."></td>";
        echo "<td>".$itemData['price']."</td><td>".$itemData['index']."</td><td><textarea>".$itemData['description']."</textarea></td>";
        echo "<td><div class = 'gallery'>";
        foreach($itemData['gallery'] as $galleryImg){
            echo "<img src = ".$galleryImg.">";
        }
        echo "</div></td>";
    }
    echo '</table>';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/showitems.css">
    <title>Items</title>
</head>
<body>
    <header class = 'goBack'> 
        <a href = 'add-items.php' class = link>Add more items</a>
        <a href = '../html/index.html' class = linkStore>View Store</a>
    </header>
</body>
    <script>
        function remove(item){
    if(confirm("Are  you sure you want to delete this?")){
        window.location.href = `delete.php?name=${item}`;
        }
    }
    </script>
</html>