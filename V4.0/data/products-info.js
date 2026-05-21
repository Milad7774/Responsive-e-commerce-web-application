let products = {};
fetch('../API/get-products.php')
    .then(response => response.json())
    .then(data => {
        products = data;
        console.log("im at the top")
        window.dispatchEvent(new Event("products-loaded"))
    })