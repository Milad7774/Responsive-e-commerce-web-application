<?php
header('Content-Type: application/json');
require_once '../Admin Panel/PDO.php';

$stmt = $pdo->query("SELECT product_name, main_image, gallery, price, description, search_keyword, stock FROM products");
$products = [];

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Decode gallery JSON back to array
    $gallery = json_decode($row['gallery'], true);
    
    $products[$row['product_name']] = [
        'img' => $row['main_image'],
        'price' => $row['price'],
        'name' => $row['product_name'],
        'gallery' => $gallery ?: [],
        'description' => $row['description'],
        'search_keyword' => $row['search_keyword'],
        'stock' => $row['stock']
    ];
}

echo json_encode($products);
?>