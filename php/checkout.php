<?php
session_start();
require_once 'db_connect.php';

// Get database connection
$conn = erstelleDatenbankverbindung();

// Initialize variables
$cart_items = [];
$total = 0;

// Check if user is logged in and get cart items from database
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT c.product_id, c.quantity, p.name, p.price 
                           FROM cart c 
                           JOIN products p ON c.product_id = p.id 
                           WHERE c.user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total += $row['price'] * $row['quantity'];
    }
    
    if (empty($cart_items)) {
        echo "<p>Your cart is empty. <a href='shop.php'>Go shopping</a></p>";
        exit;
    }
} else {
    // Use session cart for non-logged in users
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty. <a href='shop.php'>Go shopping</a></p>";
        exit;
    }
    $cart_items = $_SESSION['cart'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main>
        <h1>Checkout</h1>
        <h2>Your Cart Summary</h2>
        <table border="1" cellpadding="8">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($cart_items as $item): 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= (int)$item['quantity'] ?></td>
                    <td>€<?= number_format($item['price'], 2) ?></td>
                    <td>€<?= number_format($subtotal, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" align="right"><strong>Total:</strong></td>
                <td><strong>€<?= number_format($total, 2) ?></strong></td>
            </tr>
        </table>

        <h2>Shipping Information</h2>
        <form action="process_checkout.php" method="post">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <p class="notice">Consider <a href="login.php">logging in</a> or <a href="register.php">registering</a> for a faster checkout experience.</p>
                
                <label>
                    Name:<br>
                    <input type="text" name="name" required>
                </label><br><br>
                
                <label>
                    Email:<br>
                    <input type="email" name="email" required>
                </label><br><br>
            <?php endif; ?>
            
            <label>
                Shipping Address:<br>
                <textarea name="address" required rows="3"></textarea>
            </label><br><br>
            
            <label>
                Phone Number:<br>
                <input type="tel" name="phone" required pattern="[0-9+\s-()]{10,}">
            </label><br><br>
            
            <input type="hidden" name="total" value="<?= $total ?>">
            
            <h2>Payment Method</h2>
            <div class="payment-methods">
                <label>
                    <input type="radio" name="payment_method" value="credit_card" required>
                    Credit Card
                </label>
                <label>
                    <input type="radio" name="payment_method" value="paypal" required>
                    PayPal
                </label>
                <label>
                    <input type="radio" name="payment_method" value="bank_transfer" required>
                    Bank Transfer
                </label>
            </div>
            
            <button type="submit" class="checkout-button">Place Order</button>
        </form>
    </main>
    
    <?php include 'footer.php'; ?>
    <?php $conn->close(); ?>
</body>
</html>