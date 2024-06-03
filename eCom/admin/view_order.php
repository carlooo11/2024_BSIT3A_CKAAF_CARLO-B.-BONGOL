
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Page</title>

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

      .green-btn {
   background-color: #27ae60; /* Green color */
   color: #fff; /* White text color */
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
      <li><a href="#">View Shop</a></li>
      <li><a href="index.php">Dashboard</a></li>
      <li><a href="inventory.php">Inventory</a></li> <!-- Updated this line -->
      <li><a class="active" href="orders.php">Orders</a></li>
      <li><a href="settings.php">Settings</a></li>
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



 



<div class="container">
  <div class="product-display">
    <table class="product-display-table">
      <thead>
                 <tr>
                    <th>Product Image</th>
                    <th>User Id</th>
                    <th>Order Id</th>
                    <th>Product Id</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th class="text-center">Order Status</th>
                  </tr>
      </thead>
   
      <?php
          // Include connection file and fetch orders
          include "../connection.php";
          $query = "SELECT * FROM orders";
          $result = $conn->query($query);
          $user_id = $_GET['user_id'];


          if($result->num_rows > 0){
            while ($rows = $result->fetch_assoc()){
              // Assign fetched data to variables
              $user_id = $rows['userId'];
              $prodpic = $rows['prodpic'];
              $orderId = $rows['order_reference_number'];
              $prodId = $rows['prodId'];
              $price = $rows['price'];
              $quantity = $rows['quantity'];
              $totalPrice = $rows['totalPrice'];
              $orderStatus = $rows['orderStatus'];
              ?>
              <tr>
              <td><img src="data:image/jpg;base64,<?php echo base64_encode($row['prodpic']); ?>" style="height: 70px; width:auto;" /></td>   
                <td><?php echo htmlspecialchars($user_id); ?></td>
                <td><?php echo htmlspecialchars($orderId); ?></td>
                <td><?php echo htmlspecialchars($prodId); ?></td>
                <td><?php echo htmlspecialchars($price); ?></td>
                <td><?php echo htmlspecialchars($quantity); ?></td>
                <td><?php echo htmlspecialchars($totalPrice); ?></td>
               <!-- Inside your HTML form -->
               <td>
            <form id="orderForm<?php echo $orderId; ?>" action="update_order_status.php" method="post">
               <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
               <select name="orderStatus" id="orderStatus<?php echo $orderId; ?>">
                  <option value="Pending" <?php if($orderStatus == 'Pending') echo 'selected'; ?>>Pending</option>
                  <option value="Completed" <?php if($orderStatus == 'Completed') echo 'selected'; ?>>Completed</option>
               </select>
               <button type="button" onclick="saveOrder(<?php echo $orderId; ?>)">Save</button>
            </form>
            </td>

<script>
  function submitForm(orderId) {
    // Get the form element
    var form = document.getElementById('orderForm' + orderId);
    // Submit the form
    form.submit();
  }
</script>

                  <!-- Form to edit order status -->
                  <form action="update_order_status.php" method="post">
                    <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
                 
               
                  </form>
                </td>
              </tr>
            <?php
            }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
   

      




    <footer class="section-p1">
        <div class="col">
            <h4>Contact</h4>
            <p><strong>Address: </strong> Purok Earth, Centro Occidental, Buhi - Polangui Rd, Polangui, 4506 Albay</p>
            <p><strong>Phone:</strong>(+63) 9462243688</p>
            <p><strong>Hours:</strong> 9:00 - 4:00, Mon - Sat</p>
            <div class="follow">
                <h4>Follow Us</h4>
                <div class="icon">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-pinterest-p"></i>
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <h4>About</h4>
            <a href="#">About Us</a>
            <a href="#">Delivery Information</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Conditions</a>
            <a href="#">Contact Us</a>
        </div>

        <div class="col">
            <h4>My Account</h4>
            <a href="#">My Orders</a>
            <a href="cart.php">View Cart</a>
            <a href="#">My Wishlist</a>
            <a href="#">Track My Order</a>
            <a href="#">Help</a>
        </div>

        <div class="col install">
        <p>Payment Options</p>
                <img src="../img/pay/pays.png" alt="">
        </div>

        <div class="copyright">
            <p>© 2024, Ckaaf Clothing Shop</p>
        </div>
    </footer>  
   </section>
</body>
</html>
