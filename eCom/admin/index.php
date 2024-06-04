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

    <div class="report-container">
        <h1>Sales Report</h1>
    </div>

    <!-- Number of Sales -->
    <section class="dashboard">
        <div class="box-container">
            
            <div class="box1">
                <?php
                include "../connection.php";

                $query = "SELECT COUNT(*) AS total_sales FROM orders WHERE orderStatus = 'Recieved' AND payment_status = 'Completed'";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $total_sales = $row['total_sales'];
                    echo "<h3>$total_sales</h3>";
                    echo "<p>Total Number of Sales</p>";
                } 
                else {
                    echo "Failed to retrieve total number of sales.";
                }
                mysqli_close($conn);
                ?>
            </div>


            <!-- Total amount of Sales -->
            <div class="box2">
                <?php
                include "../connection.php";

                $query = "SELECT SUM(totalPrice) AS total_amount FROM orders WHERE orderStatus = 'Recieved' AND payment_status = 'Completed'";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $total_amount = $row['total_amount'];
                    echo "<h3>$" . number_format($total_amount, 2) . "</h3>";
                    echo "<p>Total Amount of Sales</p>";
                } 
                else {
                    echo "Failed to retrieve total amount of sales.";
                }
                mysqli_close($conn);
                ?>
            </div>


            <!-- number of products sold -->
            <div class="box3">
                <?php
                include "../connection.php";

                $query = "SELECT SUM(quantity) AS total_products FROM orders WHERE orderStatus = 'Recieved' AND payment_status = 'Completed'";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $total_products = $row['total_products'];
                    echo "<h3>" . number_format($total_products) . "</h3>";
                    echo "<p>Total Number of Products Sold</p>";
                } else {
                    echo "Failed to retrieve total number of products sold.";
                }
                mysqli_close($conn);
                ?>
            </div>


            <!-- Users with Pending Payments -->
            <div class="box4">
                <?php
                include "../connection.php";

                $total_pending_users = 0;
                $query = "SELECT COUNT(DISTINCT order_reference_number) AS total_pending_users FROM orders WHERE payment_status = 'Pending'";
                $result = mysqli_query($conn, $query) or die ('Query failed');
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $total_pending_users = $row['total_pending_users'];
                }
                ?>
                <h3><?php echo $total_pending_users; ?></h3>
                <p>Users with Pending Payments</p>
            </div>

            <!-- Users with Completed Payment -->
            <div class="box5">
                <?php
                include "../connection.php";

                $total_confirmed_users = 0;
                $query = "SELECT COUNT(DISTINCT userId) AS total_confirmed_users FROM orders WHERE payment_status = 'Completed'";
                $result = mysqli_query($conn, $query) or die ('Query failed');
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $total_confirmed_users = $row['total_confirmed_users'];
                }
                ?>
                <h3><?php echo $total_confirmed_users; ?></h3>
                <p>Users with Completed Payment</p>
            </div>
        

            <!-- Number of Users -->
            <div class="box6">
                <?php
                include "../connection.php";

                    $select_users = mysqli_query($conn, "SELECT * FROM users WHERE userType = 'client' ") or die ('query failed');
                    $num_of_users = mysqli_num_rows($select_users);
                ?>
                <h3><?php echo $num_of_users; ?></h3>
                <p>Number of Users</p>
            </div>
           
        </div>
        <a href="sales.php" class="detailed_sales">View Detailed Sales Report</a>

    </section>
</body>
</html>
        <style>
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

