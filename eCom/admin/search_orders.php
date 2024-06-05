<?php
include "../connection.php";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT * FROM orders WHERE userId LIKE ? OR order_reference_number LIKE ?";
$stmt = $conn->prepare($sql);
$likeQuery = '%' . $query . '%';
$stmt->bind_param('ss', $likeQuery, $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

$stmt->close();
$conn->close();
?>


<!-- Include the search results in the admin panel -->
<?php include 'admin_pannel.php'; ?>
<!-- Display error notification if no products are found -->
<?php if (empty($orders)): ?>
    <p>No products found.</p>
<?php endif; ?>