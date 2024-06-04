<?php
include "../connection.php"; // Ensure connection.php is included properly

// Initialize message array
$message = [];

// Check if the edit parameter is set and valid
if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
    $id = intval($_GET['edit']);
    
    // Fetch the product details from the database
    $select = mysqli_query($conn, "SELECT * FROM products WHERE prodId = $id");
    if (mysqli_num_rows($select) > 0) {
        $product = mysqli_fetch_assoc($select);
    } else {
        $message[] = 'Product not found.';
    }
} else {
    $message[] = 'Invalid product ID.';
}

// Update the product details if the form is submitted
if (isset($_POST['update_product'])) {
    $prodname = mysqli_real_escape_string($conn, $_POST['prodname']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $prod_description = mysqli_real_escape_string($conn, $_POST['prod_description']);
    $restock_amount = $_POST['restock'];
    $id = intval($_POST['prodId']);

    // Calculate new stock
    $new_stock = $stock + $restock_amount;

    // Handle file upload if a new file is provided
    if (!empty($_FILES['prodpic']['tmp_name'])) {
        $product_image = $_FILES['prodpic']['tmp_name'];
        $product_image_type = $_FILES['prodpic']['type'];
        $product_image_size = $_FILES['prodpic']['size'];

        // Check if file is an image
        $check = getimagesize($product_image);
        if ($check !== false) {
            // Validate image type and size (example: allow only JPEG and PNG, max 2MB)
            $allowedTypes = ['image/jpeg', 'image/png'];
            $maxSize = 2 * 1024 * 1024; // 2MB

            if (in_array($product_image_type, $allowedTypes) && $product_image_size <= $maxSize) {
                $imgContent = addslashes(file_get_contents($product_image));
                $update = "UPDATE products SET prodname='$prodname', price='$price', stock='$new_stock', prod_description='$prod_description', prodpic='$imgContent' WHERE prodId = $id";
            } else {
                $message[] = 'Invalid image file.';
            }
        } else {
            $message[] = 'File is not an image.';
        }
    } else {
        // Update without changing the image
        $update = "UPDATE products SET prodname='$prodname', price='$price', stock='$new_stock', prod_description='$prod_description' WHERE prodId = $id";
    }

    // Execute the update query
    if (isset($update)) {
        $upload = mysqli_query($conn, $update);
        if ($upload) {
            // Insert the restock information into the stock table
            $insert_stock = "INSERT INTO stock (prodName, previousStockAmount, quantity_added) VALUES ('$prodname', '$stock', '$restock_amount')";
            $insert_result = mysqli_query($conn, $insert_stock);
            if ($insert_result) {
                $message[] = 'Product updated and restocked successfully.';
            } else {
                $message[] = 'Could not insert restock information: ' . mysqli_error($conn);
            }
        } else {
            $message[] = 'Could not update the product: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Update</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <link rel="stylesheet" href="style.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap');

    :root{
       --green:#27ae60;
       --black:#333;
       --white:#fff;
       --bg-color:#eee;
       --box-shadow:0 .5rem 1rem rgba(0,0,0,.1);
       --border:.1rem solid var(--black);
    }

    *{
       font-family: 'Poppins', sans-serif;
       margin:0; padding:0;
       box-sizing: border-box;
       outline: none; border:none;
       text-decoration: none;
       text-transform: capitalize;
    }

    html{
       font-size: 45.5%;
       overflow-x: hidden;
    }

    .btn{
       display: block;
       width: 100%;
       cursor: pointer;
       border-radius: .5rem;
       margin-top: 1rem;
       font-size: 1.7rem;
       padding:1rem 3rem;
       background: var(--green);
       color:var(--white);  
       text-align: center;
    }

    .edit-btn {
        background-color: khaki;
    }

    .message{
       display: block;
       background: var(--bg-color);
       padding:1.5rem 1rem;
       font-size: 2rem;
       color:var(--black);
       margin-bottom: 2rem;
       text-align: center;
    }

    .container{
       max-width:3300px;
       padding:2rem;
       margin:0 auto;
    }

    .admin-product-form-container.centered{
       display: flex;
       align-items: center;
       justify-content: center;
       min-height: 100vh;
    }

    .admin-product-form-container form{
       max-width: 120rem; /* Increased max-width */
       width: 200%; /* Ensure it uses full width */
       margin:0 auto;
       padding:2rem;
       border-radius: .5rem;
       background: var(--bg-color);
    }

    .admin-product-form-container form h3{
       text-transform: uppercase;
       color:var(--black);
       margin-bottom: 1rem;
       text-align: center;
       font-size: 2.5rem;
    }

    .admin-product-form-container form .box{
       width: 100%;
       border-radius: .5rem;
       padding:1.2rem 1.5rem;
       font-size: 1.7rem;
       margin:1rem 0;
       background: var (--white);
       text-transform: none;
    }

    .product-display{
       margin:2rem 0;
    }

    .product-display .product-display-table{
       width: 100%;
       text-align: center;
    }

    .product-display .product-display-table thead{
       background: var (--bg-color);
    }

    .product-display .product-display-table th{
       padding:1rem;
       font-size: 2rem;
    }

    .product-display .product-display-table td{
       padding:1rem;
       font-size: 2rem;
       border-bottom: var(--border);
    }

    .product-display .product-display-table .btn:first-child{
       margin-top: 0;
    }

    .product-display .product-display-table .btn:last-child{
       background: crimson;
    }

    .product-display .product-display-table .btn:last-child:hover{
       background: var (--black);
    }

    @media (max-width:991px){
       html{
          font-size: 55%;
       }
    }

    @media (max-width:768px){
       .product-display{
          overflow-y:scroll;
       }

       .product-display .product-display-table{
          width: 80rem;
       }
    }

    @media (max-width:450px){
       html{
          font-size: 50%;
       }
    }
  </style>
</head>
<body>
<section id="header">
  <a href="#"><img src="../img/logo.png" class="logo" alt=""></a>

  <div>
    <ul id="navbar">
      <li><a href="../index.php">View Shop</a></li>
      <li><a href="index.php">Dashboard</a></li>
      <li><a class="active" href="inventory.php">Inventory</a></li>
      <li><a href="orders.php">Orders</a></li>
      <div class="dropdown">
        <button class="dropbtn"><img src="../img/people/uicon.jpeg" class="uicon" alt=""></button>
        <div class="dropdown-content">
          <a href="../request/logout.php">Logout</a>
        </div>
      </div>
      <a href="#" id="close"><i class="far fa-times"></i></a>
    </ul>
  </div>
  <div id="mobile">
    <img src="../img/people/uicon.jpeg" class="uicon" alt="">
    <i id="bar" class="fas fa-outdent"></i>
  </div>
</section>

<?php
// Display messages
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<span class="message">' . $msg . '</span>';
    }
}
?>

<div class="container">
  <div class="admin-product-form-container centered">
    <?php if (!empty($product)) { ?>
    <form action="admin_update.php?edit=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
      <h3>Update the Product</h3>
      <input type="hidden" name="prodId" value="<?php echo $product['prodId']; ?>">
      <input type="text" placeholder="Enter product name" name="prodname" class="box" value="<?php echo $product['prodname']; ?>">
      <input type="number" placeholder="Enter product price" name="price" class="box" value="<?php echo $product['price']; ?>">
      <input type="number" placeholder="Enter stock quantity" name="stock" class="box" value="<?php echo $product['stock']; ?>">
      <textarea placeholder="Enter product description" name="prod_description" class="box" style="height:150px;"><?php echo $product['prod_description']; ?></textarea>
      <input type="file" accept="image/png, image/jpeg, image/jpg" name="prodpic" class="box">
      <label for="restock"><h3>Restock</h3></label>
      <input type="number" placeholder="Enter item quantity to be added to stock" name="restock" class="box">
      <input type="submit" class="btn" name="update_product" value="Update Product">
      <a href="inventory.php" class="btn">Back to Inventory</a>
    </form>
    <?php } else { ?>
      <p>Product not found.</p>
      <a href="inventory.php" class="btn">Back to Inventory</a>
    <?php } ?>
  </div>
</div>

</body>
</html>
