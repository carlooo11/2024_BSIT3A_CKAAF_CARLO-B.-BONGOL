<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ckaaf - Dashboard</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="style.css">
    
</head>

<body>
    <section id="header">
        <a href="#"><img src="../img/logo.png" class="logo" alt="Ckaaf Logo"></a>
        <div>
            <ul id="navbar">
                <li><a href="my_shop.php">View Shop</a></li>
                <li><a class="active" href="index.php">Dashboard</a></li>
                <li><a href="inventory.php">Inventory</a></li>
                <li><a href="orders.php">Orders</a></li>
                <div class="dropdown">
                    <button class="dropbtn"><img src="../img/people/uicon.jpeg" class="uicon" alt="User Icon"></button>
                    <div class="dropdown-content">
                        <a href="../request/logout.php">Logout</a>
                    </div>
                </div>
                <a href="#" id="close"><i class="far fa-times"></i></a>
            </ul>
        </div>
        <div id="mobile">
            <img src="../img/people/uicon.jpeg" class="uicon" alt="User Icon">
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>

    
        <div class="classclass">
            <div><a href="index.php" class="back-button">Back</a></div>
            <div class="div" style="justify-self: center; margin-top:10px;"><h2>My Sales</h2></div>
        </div>

        <section >
    <div class="dashboard">
        <!-- <div class="box_container"> -->
    
            <div class="topten">
                <?php
                include "../connection.php";

                session_start();

                $query = "
                    SELECT p.prodname, SUM(o.quantity) AS total_sold
                    FROM orders o
                    JOIN products p ON o.prodId = p.prodId
                    GROUP BY o.prodId
                    ORDER BY total_sold DESC
                    LIMIT 10";
                $result = mysqli_query($conn, $query) or die('Query failed');
               
                ?>
                <h3>Top 10 Products Sold</h3>
                <ol>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                        echo '<li>' . $row['prodname'] . ' - ' . $row['total_sold'] . ' sold</li>';
                            
                        }
                    } else {
                        echo '<li>No products found.</li>';
                    }
                    ?>
                </ol>
            </div>
        <!-- </div> -->
        
        <div class="tVSy">
            <?php
            include "../connection.php";

            $today = date("Y-m-d");
            $yesterday = date("Y-m-d", strtotime("-1 day"));

            $query_today = "SELECT SUM(totalPrice) AS total_sales_today FROM orders WHERE DATE(orderDate) = '$today'";
            $result_today = mysqli_query($conn, $query_today);
            $row_today = mysqli_fetch_assoc($result_today);
            $total_sales_today = $row_today['total_sales_today'];

            $query_yesterday = "SELECT SUM(totalPrice) AS total_sales_yesterday FROM orders WHERE DATE(orderDate) = '$yesterday'";
            $result_yesterday = mysqli_query($conn, $query_yesterday);
            $row_yesterday = mysqli_fetch_assoc($result_yesterday);
            $total_sales_yesterday = $row_yesterday['total_sales_yesterday'];

            echo "<h3>Sales Today vs Yesterday</h3>";
            echo "<p>Today's Sales: <strong>$total_sales_today</strong></p>";
            echo "<p>Yesterday's Sales: <strong>$total_sales_yesterday</strong></p>";

            mysqli_close($conn);
            ?>
        </div>
        
        <div class="tyVSly">
            <?php
            include "../connection.php";

            $current_year = date("Y");
            $previous_year = $current_year - 1;

            $query_current_year = "SELECT SUM(totalPrice) AS total_sales_current_year FROM orders WHERE YEAR(orderDate) = '$current_year'";
            $result_current_year = mysqli_query($conn, $query_current_year);
            $row_current_year = mysqli_fetch_assoc($result_current_year);
            $total_sales_current_year = $row_current_year['total_sales_current_year'];

            $query_previous_year = "SELECT SUM(totalPrice) AS total_sales_previous_year FROM orders WHERE YEAR(orderDate) = '$previous_year'";
            $result_previous_year = mysqli_query($conn, $query_previous_year);
            $row_previous_year = mysqli_fetch_assoc($result_previous_year);
            $total_sales_previous_year = $row_previous_year['total_sales_previous_year'];

            echo "<h3>Sales This Year vs Last Year</h3>";
            echo "<p>This Year's Sales: $total_sales_current_year</p>";
            echo "<p>Last Year's Sales: $total_sales_previous_year</p>";

            mysqli_close($conn);
            ?>
        </div>
    </div>
    </section>
    
    <section>
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

        $query = "SELECT * FROM orders WHERE orderStatus = 'Recieved' AND payment_status ='Completed' ORDER BY order_reference_number";
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
               echo '<tr class="order-group ' . $rowClass . '"><td colspan="8">Order Reference Number: ' . $orderId . '</td><td>';
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
            echo '<td>' . $orderStatus . '</td>';
            echo '</tr>';
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
    </section>
</body>

<script>
    function submitForm(orderId) {
      var form = document.getElementById('orderForm' + orderId);
      form.submit();
    }
  </script>
</html>

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
    .classclass{
        display: grid;
        grid-template-columns: 30px 1fr;
    }

    .dashboard {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
        padding: 50px;
    }

    .topten {
        background: linear-gradient(-45deg, #0039e6, #00ccff);
        /* background-color: lightseagreen; */
        width: 450px;
        padding: 20px;
        font-size:medium;
        margin: 0 auto;
        border-radius: 20px;
        
    }

    .tVSy{
        background: linear-gradient(-45deg, #ff0066, #d966ff);
        width: 450px;
        padding: 20px;
        font-size:medium;
        margin: 0 auto;
        border-radius: 20px;
        color: #000;
    }

    .tyVSly{
        background: linear-gradient(-45deg, #009933, #ccff66);
        width: 450px;
        padding: 20px;
        font-size:medium;
        margin: 0 auto;
        border-radius: 20px;
    }

    .box, .box1{
        background-color: #f2f2f2; 
        border: 1px solid #ddd; 
        border-radius: 5px; 
        padding: 20px; 
        margin-right: 4rem; 
        box-shadow: var(--box-shadow);
        border: 1px solid #000;

    }

     ol, .h3{
        padding: 40px;
    }

    p{
        color: #000;
    }

    .back-button {
        display: inline-block;
        padding: 10px 20px; 
        background-color: #007bff; 
        color: #fff;
        text-decoration: none; 
        border-radius: 5px; 
        margin-top: 20px;
        margin-left: 60px;
    }

    .back-button:hover {
        background-color: #0056b3; 
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
        height: 60px;
        background: linear-gradient(to bottom, #ffffff, rgba(0, 0, 0, 0.0));
      }

      .myordersnav {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        width: 100%;
        margin-top: 0;
      }

      #ordernavbar {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(to bottom, #ffffff, rgba(0, 0, 0, 0.0));
        width: 100%;
        margin-top: 0;
        padding: 3rem;
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
