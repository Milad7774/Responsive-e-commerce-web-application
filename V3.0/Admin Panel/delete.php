<?php
    session_start();
    if(!isset($_SESSION['logged-in']) || $_SESSION['logged-in'] !== true){
        header("Location: login.php");
        exit();
    }

    $jsonFile = '../data/products-info.json';

    $products = json_decode(file_get_contents($jsonFile), true);

    $item = $_GET['name'];
     if(isset($products[$item])){
        unset($products[$item]);
        file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT));
        $jsContent = "const products = " . json_encode($products, JSON_PRETTY_PRINT) . ";";
        file_put_contents('../data/products-info.js', $jsContent);
        $folderpath = "../images/$item";
        deleteFolder($folderpath);
        header('Location: showItems.php');
        exit();
     }
     else{
        die('Item already deleted!');
     }

     //delete function
     function deleteFolder($folderPath) {
        // Check if folder exists
        if (!is_dir($folderPath)) {
            return false; // Not a folder or doesn't exist
        }
        
        // Get all items in the folder
        $items = scandir($folderPath);
        
        // Remove . and .. (current and parent directory links)
        $items = array_diff($items, ['.', '..']);
        
        // Loop through each item
        foreach ($items as $item) {
            $path = $folderPath . DIRECTORY_SEPARATOR . $item;
            
            if (is_dir($path)) {
                // If it's a folder, call this function again (recursion)
                deleteFolder($path);
            } else {
                // If it's a file, delete it
                unlink($path);
            }
        }
        
        // Delete the now-empty folder
        return rmdir($folderPath);
    }


?>