<?php
include "../connection.php"; // Ensure connection.php is included properly

$message = []; // Initialize message array

if(isset($_POST['add_product'])){ 
   // Escape user input for security
   $prodname = mysqli_real_escape_string($conn, $_POST['prodname']);
   $price = $_POST['price'];
   $stock = $_POST['stock']; // Add stock input handling
   $prod_description = mysqli_real_escape_string($conn, $_POST['prod_description']);
   
   // File upload handling
   $product_image = $_FILES['prodpic']['tmp_name'];
   $product_image_name = $_FILES['prodpic']['name'];
   $product_image_type = $_FILES['prodpic']['type'];
   $product_image_size = $_FILES['prodpic']['size'];
   
   // Check if file is an image
   $check = getimagesize($product_image);
   if($check === false) {
     $message[] = 'File is not an image.';
   } else {
     // Read image file into a variable
     $imgContent = addslashes(file_get_contents($product_image));
 
     // Validate image type and size (example: allow only JPEG and PNG, max 2MB)
     $allowedTypes = ['image/jpeg', 'image/png'];
     $maxSize = 2 * 1024 * 1024; // 2MB
 
     if(!in_array($product_image_type, $allowedTypes)) {
       $message[] = 'Only JPEG and PNG files are allowed.';
     } elseif($product_image_size > $maxSize) {
       $message[] = 'File size exceeds the maximum limit of 2MB.';
     } else {
       // Insert data into database
       $insert = "INSERT INTO products (prodname, price, stock, prod_description, prodpic) VALUES ('$prodname', '$price', '$stock', '$prod_description', '$imgContent')";
       $upload = mysqli_query($conn, $insert);
       if($upload){
         $message[] = 'New product added successfully.';
       } else {
         $message[] = 'Could not add the product: ' . mysqli_error($conn);
       }
     }
   }
 }
 

if(isset($_GET['delete'])){
   $id = intval($_GET['delete']); // Convert to integer for safety
 
   // Delete related records from the carts table
   $delete_cart_query = "DELETE FROM carts WHERE prodId = ?";
   $stmt_cart = mysqli_prepare($conn, $delete_cart_query);
   mysqli_stmt_bind_param($stmt_cart, "i", $id);
   mysqli_stmt_execute($stmt_cart);
   mysqli_stmt_close($stmt_cart);
 
   // Delete the product from the products table
   $delete_product_query = "DELETE FROM products WHERE prodId = ?";
   $stmt_product = mysqli_prepare($conn, $delete_product_query);
   mysqli_stmt_bind_param($stmt_product, "i", $id);
   mysqli_stmt_execute($stmt_product);
   mysqli_stmt_close($stmt_product);
 
   header('location:inventory.php');
 }
 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page</title>

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
       background: var(--white);
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
       background: var(--bg-color);
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
       background: var(--black);
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
      <li><a href="my_shop.php">View Shop</a></li>
      <li><a href="index.php">Dashboard</a></li>
      <li><a class="active" href="inventory.php">Inventory</a></li> <!-- Previously admin_page.php -->
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
if(!empty($message)){
  foreach($message as $msg){
    echo '<span class="message">'.$msg.'</span>';
  }
}
?>

<div class="container">
  <div class="admin-product-form-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
      <h3>Add a New Product</h3>
      <input type="text" placeholder="Enter product name" name="prodname" class="box">
      <input type="number" placeholder="Enter product price" name="price" class="box">
      <input type="number" placeholder="Enter stock quantity" name="stock" class="box"> <!-- Added input field for stock -->
      <textarea placeholder="Enter product description" name="prod_description" class="box"></textarea> <!-- Added input field for description -->
      <input type="file" accept="uploaded_img/png, uploaded_img/jpeg, uploaded_img/jpg" name="prodpic" class="box">
      <input type="submit" class="btn" name="add_product" value="Add Product">
    </form>
  </div>

  <div class="product-display">
    <table class="product-display-table">
      <thead>
        <tr>
          <th>Product Image</th>
          <th>Product Name</th>
          <th>Product Price</th>
          <th>Stock</th>
          <th>Product Description</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $query = "SELECT * FROM products";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <tr>
        <td><img src="data:image/jpg;base64,<?php echo base64_encode($row['prodpic']); ?>" style="height: 70px; width:auto;" /></td>   
        <td><?php echo $row['prodname']; ?></td>
        <td><?php echo $row['price']; ?>/-</td>
        <td><?php echo $row['stock']; ?></td>
        <td><?php echo $row['prod_description']; ?></td>
        <td>
         <a href="admin_update.php?edit=<?php echo $row['prodId']; ?>" class="btn"> <i class="fas fa-edit"></i> edit </a>
         <a href="inventory.php?delete=<?php echo $row['prodId']; ?>" class="btn"> <i class="fas fa-trash"></i> delete </a>
        </td>
      </tr>
      <?php }} ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
