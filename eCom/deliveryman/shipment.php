<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FedEx</title>  
        
        <style>
            body{
                font-family: 'Courier New', Courier, monospace;
                
            }
            .orders{
                background-color: #ffebe6;
                margin: 0 auto;
                width: fit-content;
                padding: 2rem;
                margin-bottom: 1rem;
                border-radius: 10px;
                border: 2px solid #ff471a;

            }
            strong{
                color: darkgreen;
            }
            .btn{
                padding: 0.5rem;
                background-color: #ff471a;
                border-radius: 5px;
            }
            .btn:hover{
                background-color: #ff8566;
            }
            

        </style>

    </head>

    <body>
        <h1>Courier Tracking and Update App</h1>
        <?php
            include ('../connection.php');

            session_start();

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            $sql_fetch_order_items = "SELECT * FROM orders WHERE orderStatus ='Shipped'";
            $result_order_items = mysqli_query($conn, $sql_fetch_order_items);

          
            if ( mysqli_num_rows($result_order_items) > 0) {
                while ($row = mysqli_fetch_assoc($result_order_items)) { 
                    $shipnum = $row['shipping_tracking_number'];
                    $orderRefNum = $row['order_reference_number'];
                    $quantity = $row['quantity'];
                    $price = $row['price'];
                    $total = $row['totalPrice'];
                ?>
            
                    <div class="orders">
                        <h3>Shipping tracking number: <strong><?php echo $shipnum?></strong></h3>
                        <h5>Order reference number: <strong><?php echo $orderRefNum?></strong></h5>
                        <h5>Item quantity: <strong><?php echo $quantity?></strong></h5>
                        <h5>Price: <strong><?php echo $price?></strong></h5>
                        <h4>Total: <strong><?php echo $total?></strong></h4>

                        <form action="../request/update_order_status.php" method="POST">
                            <input type="hidden" id="orderId" name="orderId" value="<?php echo $orderRefNum?>">
                            <input type="hidden" id="payment" name="payment" value="Completed">
                            <button id="orderStatus" name="orderStatus" value="Delivered" class="btn">Delivered</button>
                        </form>
                    </div>
              <?php             
                }
            } else {
                echo "No products available.";
            }
        ?>


    </body>
</html>