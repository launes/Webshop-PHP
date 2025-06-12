<?php
session_start();
require_once 'db_connect.php';

// Get the order ID from URL
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$order_id) {
    header('Location: shop.php');
    exit;
}

// Get database connection
$conn = erstelleDatenbankverbindung();

// Get order details
$stmt = $conn->prepare("SELECT o.*, oi.product_id, oi.quantity, oi.price, p.name as product_name 
                       FROM orders o
                       JOIN order_items oi ON o.id = oi.order_id
                       JOIN products p ON oi.product_id = p.id
                       WHERE o.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Order not found");
}

$order = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main>
        <h1>Order Confirmation</h1>
        
        <div class="confirmation-message">
            <h2>Thank you for your order!</h2>
            <p>Your order number is: #<?= $order_id ?></p>
        </div>

        <div class="order-details">
            <h3>Order Details</h3>
            <p>Status: <?= ucfirst($order['status']) ?></p>
            <p>Name: <?= htmlspecialchars($order['name']) ?></p>
            <p>Email: <?= htmlspecialchars($order['email']) ?></p>
            <p>Shipping Address: <?= nl2br(htmlspecialchars($order['address'])) ?></p>
            <p>Payment Method: <?= ucfirst(str_replace('_', ' ', $order['payment_method'])) ?></p>
            
            <h3>Items Ordered</h3>
            <table border="1" cellpadding="8">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
                <?php 
                $result->data_seek(0);
                while ($item = $result->fetch_assoc()): 
                    $subtotal = $item['price'] * $item['quantity'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>€<?= number_format($item['price'], 2) ?></td>
                    <td>€<?= number_format($subtotal, 2) ?></td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="3" align="right"><strong>Total:</strong></td>
                    <td><strong>€<?= number_format($order['total_amount'], 2) ?></strong></td>
                </tr>
            </table>
        </div>

        <div class="next-steps">
            <h3>What's Next?</h3>
            <p>We'll send you an email confirmation with your order details and tracking information once your order ships.</p>
            <p><a href="shop.php" class="button">Continue Shopping</a></p>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>
    <?php $conn->close(); ?>
</body>
</html>
