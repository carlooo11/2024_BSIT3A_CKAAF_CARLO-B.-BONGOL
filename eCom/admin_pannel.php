<!-- admin_panel.php -->
<?php 

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ckaaf</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <section id="header">
        <a href="#"><img src="../img/logo.png" class="logo" alt=""></a>

     
     
     <form id="searchForm" method="GET" action="search_orders.php">
        <input class= "two" type="text" name="query" id="searchInput" placeholder="Search by name, order reference, or GCash reference">
        <button type="submit" id="searchInput">Search</button>
    </form>

        <div>
            <ul id="navbar">
                <li><a href="my_shop.php">View Shop</a></li>
                <li><a class="active" href="index.php">Dashboard</a></li>
                <li><a href="inventory.php">Inventory</a></li>
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
            <div id="searchResults">
            <?php if (isset($orders)) : ?>
                <?php foreach ($orders as $order) : ?>
                    <div>
                    <div class="container">
                        <div class="product-display">
                            <table class="product-display-table order-table">
                            <thead>
                                <tr>
                                <th>Order ID</th>
                                <th>Order reference number</th>
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
                        <td> <?php echo htmlspecialchars($order['orderId']); ?></td>
                        <td> <?php echo htmlspecialchars($order['order_reference_number']); ?></td>
                       <td><?php echo htmlspecialchars($order['userId']); ?></td>
                       <td> <?php echo htmlspecialchars($order['prodId']); ?></td>
                       <td> <?php echo htmlspecialchars($order['price']); ?></td>
                       <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                       <td> <?php echo htmlspecialchars($order['totalPrice']); ?></td>
                       <td> <?php echo htmlspecialchars($order['payment_method']); ?></td>
                       <td><?php echo htmlspecialchars($order['payment_status']); ?></td>
                       <td><?php echo htmlspecialchars($order['orderStatus']); ?></td>

                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <section>
    
        <?php
    
           
           

        ?>
      </tbody>
    </table>
  </div>
</div>
    </section>

        </section>


</body>
</html>
        <style>
#searchInput{
    padding: 1rem;
    border-radius: 5px;
}
.two{
    width: 500px;
}
            .report-container {
                margin-top: 5rem;
                text-align: center;
            }

            .dashboard {
                background: var(--orange);
                padding: 1rem;
                position: relative;
            }

            .dashboard::before {
                position: absolute;
                top: -25%;
                left: -10px;
                width: 225px;
                height: 220px;
                background-size: 225px;
            }

            .box-container {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 2rem;
                padding: 2% 5%;
                
            }

            .box1 {
                background: #ff9999;
                border: 1px solid #000;
                padding: 2rem;
                text-align: center;
                border-radius: 5px;
                box-shadow: var(--box-shadow);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .box2 {
                background: #99ccff;
                border: 1px solid #000;
                padding: 2rem;
                text-align: center;
                border-radius: 5px;
                box-shadow: var(--box-shadow);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .box3 {
                background: #99ff99;
                border: 1px solid #000;
                padding: 2rem;
                text-align: center;
                border-radius: 5px;
                box-shadow: var(--box-shadow);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .box4 {
                background: #ffcc99;
                border: 1px solid #000;
                padding: 2rem;
                text-align: center;
                border-radius: 5px;
                box-shadow: var(--box-shadow);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .box5 {
                background: #ccccff;
                border: 1px solid #000;
                padding: 2rem;
                text-align: center;
                border-radius: 5px;
                box-shadow: var(--box-shadow);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .box6 {
                background: #ff99cc;
                border: 1px solid #000;
                padding: 2rem;
                text-align: center;
                border-radius: 5px;
                box-shadow: var(--box-shadow);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .dashboard h3 {
                font-size: 2rem;
                margin: 0;
            }

            .dashboard p {
                font-size: 20px;
                text-transform: capitalize;
                margin-top: 10;
            }

            .detailed_sales{
                display: inline-block;
                padding: 10px 20px; 
                background-color: #007bff; 
                color: #fff;
                text-decoration: none; 
                border-radius: 5px; 
            }

            .detailed_sales:hover {
                background-color: #0056b3; 
            }

            @media (max-width: 768px) {
                .box-container {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 576px) {
                .box-container {
                    grid-template-columns: 1fr;
                }
            }

        </style>
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



     
     
     
     
