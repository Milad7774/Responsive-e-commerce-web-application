<?php
    session_start();
    $username = 'Your-username';
    $salt = 'Some-salt';
    $password = 'Your-password';
    if(isset($_POST['submit'])){
        $name = $_POST['username'];
        $pass = hash('sha256', $_POST['password'].$salt);
        if($name == $username && $pass == hash('sha256', $password.$salt)){
            session_regenerate_id(true);
            $_SESSION['logged-in'] = true;
            header('Location: add-items.php');
            exit();
        }
        else{
            die('<h1>Wrong Password or username!</h1>');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Admin Panel</title>
</head>
<body>
    <div class = 'container'>
        <h1>Verification</h1>
        <form action="" method = 'post'>
            <div>
                <label for="username">Username:</label>
                <input type="text" name = 'username' id = 'username'>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name = 'password' id = 'password'>
            </div>
            <div class = 'button'>
                <button type = 'submit' name = 'submit'>Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
