<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "productsdb";
$response = array();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $name = $_POST['name'];
    $unit = $_POST['unit'];
    $price = $_POST['price'];
    $expiry_date = $_POST['expiry_date'];
    $inventory = $_POST['inventory'];
    $image = $_FILES['image']['name'];

    
    if (!preg_match('/^[A-Za-z0-9\s]+$/', $name)) {
        $response['message'] = "Invalid name format";
    } elseif (!preg_match('/^[A-Za-z0-9\s]+$/', $unit)) {
        $response['message'] = "Invalid unit format";
    } elseif (!is_numeric($price) || $price <= 0) {
        $response['message'] = "Invalid price";
    } elseif (!strtotime($expiry_date)) {
        $response['message'] = "Invalid expiry date format";
    } elseif (!filter_var($inventory, FILTER_VALIDATE_INT) || $inventory < 0) {
        $response['message'] = "Invalid inventory";
    } else {
        
        $targetDir = "images/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

        
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO products (name, unit, price, expiry_date, inventory, image) VALUES ('$name', '$unit', '$price', '$expiry_date', '$inventory', '$image')";
        if ($conn->query($sql) === TRUE) {
            $response['message'] = "Product created successfully";
        } else {
            $response['message'] = "Error creating product: " . $conn->error;
        }

        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $productId = $_POST['productId'];
    $name = $_POST['name'];
    $unit = $_POST['unit'];
    $price = $_POST['price'];
    $expiry_date = $_POST['expiryDate'];
    $inventory = $_POST['inventory'];
    $image = $_FILES['image']['name'];

    
    if (!preg_match('/^[A-Za-z0-9\s]+$/', $name)) {
        $response['message'] = "Invalid name format";
    } elseif (!preg_match('/^[A-Za-z0-9\s]+$/', $unit)) {
        $response['message'] = "Invalid unit format";
    } elseif (!is_numeric($price) || $price <= 0) {
        $response['message'] = "Invalid price";
    } elseif (!strtotime($expiry_date)) {
        $response['message'] = "Invalid expiry date format";
    } elseif (!filter_var($inventory, FILTER_VALIDATE_INT) || $inventory < 0) {
        $response['message'] = "Invalid inventory";
    } else {
        
        $targetDir = "images/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

        
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE products SET name='$name', unit='$unit', price='$price', expiry_date='$expiry_date', inventory='$inventory', image='$image' WHERE id='$productId'";
        if ($conn->query($sql) === TRUE) {
            $response['message'] = "Product updated successfully";
        } else {
            $response['message'] = "Error updating product: " . $conn->error;
        }

        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $productId = $_POST['id'];

    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM products WHERE id = '$productId'";
    if ($conn->query($sql) === TRUE) {
        $response['message'] = "Product deleted successfully";
    } else {
        $response['message'] = "Error deleting product: " . $conn->error;
    }

    $conn->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'read') {
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    $response['products'] = $products;
    $conn->close();
}

header('Content-Type: application/json');
echo json_encode($response);
