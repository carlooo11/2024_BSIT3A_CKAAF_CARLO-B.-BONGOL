
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

      :root {
        --green: #27ae60;
        --black: #333;
        --white: #fff;
        --bg-color: #eee;
        --box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
        --border: .1rem solid var(--black);
      }

      * {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        outline: none;
        border: none;
        text-decoration: none;
        text-transform: capitalize;
        background-color: e9e9e9;
      }

      html {
        font-size: 45.5%;
        overflow-x: hidden;
      }

      .container {
        max-width: 3300px;
        padding: 2rem;
        margin: 10px auto;
      }

      .product-display {
        margin: 2rem 0;
      }

      .product-display .product-display-table {
        width: 100%;
        text-align: center;
      }

      .product-display .product-display-table thead {
        background-color: lightseagreen;
      }

      .product-display .product-display-table th {
        padding: 1rem;
        font-size: 2rem;
      }

      .product-display .product-display-table td {
        padding: 1rem;
        font-size: 2rem;
        border-bottom: var(--border);
      }

      .order-status {
        display: flex;
        align-items: center;
      }

      .order-status form {
        margin-left: 10px;
      }


      .order-table {
        width: 100%;
        border-collapse: collapse;
      }

      .order-table th, .order-table td {
        padding: 10px;
        text-align: center;
      }

      .order-table tr.order-group td {
        background-color: inherit !important;
        font-weight: bold;
      }

      .order-table tr.even td{
        background-color: #e6fff9 !important;
      }

      .order-table tr.odd td {
        background-color: #ffffff !important;
      }

      .statbtn{
        background-color: #F9E8D9;
        padding: 10px;
        border-top-left-radius: 7px;
        border-bottom-left-radius: 7px;
      }

      .filler-col{
        height: 40px;
      }

      .myordersnav {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        padding: 14px;
        position: fixed;
        width: 100%;
      }

      #ordernavbar {
        display: flex;
        align-items: center;
        justify-content: center;
      }

      #ordernavbar li {
        list-style: none;
        padding: 0 20px;
        position: relative;
      }

      #ordernavbar li a {
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        color: black;
      }

      #ordernavbar li a:hover,
      #ordernavbar li a.active {
        color: lightseagreen;
      }

      #ordernavbar li a.active::after,
      #ordernavbar li a:hover::after {
        content: "";
        width: 30%;
        height: 2px;
        background: #088178;
        position: absolute;
        bottom: -4px;
        left: 20px;
      }

      .save-btn {
        background-color: lightseagreen;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 7px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        margin-bottom: 15px;
      }

      .save-btn:hover {
        background-color: lightsalmon;
      }

      @media (max-width: 991px) {
        html {
          font-size: 55%;
        }
      }

      @media (max-width: 768px) {
        .product-display {
          overflow-y: scroll;
        }

        .product-display .product-display-table {
          width: 80rem;
        }
      }

      @media (max-width: 450px) {
        html {
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
      <li><a href="inventory.php">Inventory</a></li>
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

<section>
  <div class="myordersnav">
    <ul id="ordernavbar">
    <li><a href="orders.php">Pending</a></li>
    <li><a href="confirmed.php">Confirmed</a></li>
      <li><a href="shipped_orders.php">Shipped</a></li>
      <li><a  class="active" href="delivered_orders.php">Delivered</a></li>
      <li><a href="completed_orders.php">Completed</a></li>
      <li><a href="cancellation_requests.php">Cancellation Requests</a></li>
      <li><a href="canceled_orders.php">Cancelled</a></li>
    </ul>
  </div>
  <div class="filler-col"></div>
</section>


<div class="container">
  <div class="product-display">
    <table class="product-display-table order-table">
      <thead>
        <tr>
          <th>Product Image</th>
          <th>User Id</th>
          <th>Product Id</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total Price</th>
          <th>Payment Method</th>
          <th>Payment Status</th>
          <th>Order Status</th>
        </tr>
      </thead>
      <tbody>
      <?php
        include "../connection.php";

        $query = "SELECT * FROM orders WHERE orderStatus = 'Delivered'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $currentOrderId = null;
            $isEven = false;
            while ($rows = $result->fetch_assoc()) {
                $user_id = htmlspecialchars($rows['userId']);
                $orderId = htmlspecialchars($rows['order_reference_number']);
                $prodId = htmlspecialchars($rows['prodId']);
                $price = htmlspecialchars($rows['price']);
                $quantity = htmlspecialchars($rows['quantity']);
                $totalPrice = htmlspecialchars($rows['totalPrice']);
                $orderStatus = htmlspecialchars($rows['orderStatus']);
                $payment_method = htmlspecialchars($rows['payment_method']);
                $payment_status = htmlspecialchars($rows['payment_status']);

                $sql_fetch_product = "SELECT * FROM products WHERE prodId = '$prodId'";
                $result_product = $conn->query($sql_fetch_product);
                $product = $result_product->fetch_assoc();
                if ($orderId !== $currentOrderId) {
                    $currentOrderId = $orderId;
                    $isEven = !$isEven;
                    $rowClass = $isEven ? 'even' : 'odd';
                    echo '<tr class="order-group ' . $rowClass . '"><td colspan="9">Order Reference Number: ' . $orderId . '</td>';
                    
                } else {
                    $rowClass = $isEven ? 'even' : 'odd';
                }
                echo '<tr class="' . $rowClass . '">';
                echo '<td><img src="data:image/jpg;base64,' . base64_encode($product['prodpic']) . '" style="height: 70px; width:auto;" alt="Product Image" /></td>';
                echo '<td>' . '<a href ="cart.php">' . $user_id . '</a>' . '</td>';
                echo '<td>' . $prodId . '</td>';
                echo '<td>' . $price . '</td>';
                echo '<td>' . $quantity . '</td>';
                echo '<td>' . $totalPrice . '</td>';
                echo '<td>' . $payment_method . '</td>';
                echo '<td>' . $payment_status . '</td>';
                echo '<td>Delivered</td>';
                echo '</tr>';
            }
        }
        ?>

      </tbody>
    </table>
  </div>
</div>

<script>
    function submitForm(orderId) {
      var form = document.getElementById('orderForm' + orderId);
      form.submit();
    }
  </script>

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


</body>
</html>
