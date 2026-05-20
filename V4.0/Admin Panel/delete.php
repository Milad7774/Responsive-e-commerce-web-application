<?php
    session_start();
    //Establishing a connection with DB
    require_once("PDO.php");
    //Folder Delete
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
    //Security
    if(!isset($_SESSION['login'])){
        die("Access Denied");
    }
    //Validating Method
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        //id to delet
        $delete_id = $_POST['delete_id'];
        //getting item name to delete folder
        $stmt2 = $pdo->prepare("SELECT product_name FROM products WHERE product_id = :id");

        $stmt2->execute([
            ':id' => $delete_id
        ]);

        $row = $stmt2->fetch(PDO::FETCH_ASSOC);

        $file_name = "../images/$row[product_name]";
        //Deleting the row
        $sql = "DELETE FROM products WHERE product_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $delete_id
        ]);
        deleteFolder($file_name);
        header("Location: showItems.php");
        exit();
    }
    else{
        die("Request Error!");
    }