<?php
session_start();
require_once 'db_connect.php';

// Get database connection
$conn = erstelleDatenbankverbindung();

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

// Get user information
$user_id = $_SESSION['user_id'] ?? null;
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$address = $_POST['address'] ?? '';
$phone = $_POST['phone'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$total = floatval($_POST['total'] ?? 0);

// Validate required fields
if (empty($address) || empty($phone) || empty($payment_method)) {
    die("Please fill in all required fields");
}

// If user is not logged in, validate additional fields
if (!$user_id && (empty($name) || empty($email))) {
    die("Please fill in all required fields");
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Create order in database
    $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, address, phone, payment_method, total_amount, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("isssssd", $user_id, $name, $email, $address, $phone, $payment_method, $total);
    $stmt->execute();
    
    $order_id = $conn->insert_id;

    // Transfer cart items to order_items
    if ($user_id) {
        // For logged-in users, get items from cart table
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price)
                              SELECT ?, c.product_id, c.quantity, p.price
                              FROM cart c
                              JOIN products p ON c.product_id = p.id
                              WHERE c.user_id = ?");
        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();

        // Clear user's cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    } else {
        // For non-logged-in users, get items from session
        foreach ($_SESSION['cart'] as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                  VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }
    }

    // Clear session cart
    unset($_SESSION['cart']);

    // Commit transaction
    $conn->commit();

    // Redirect to order confirmation page
    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit;

} catch (Exception $e) {
    // If there's an error, rollback the transaction
    $conn->rollback();
    error_log("Checkout Error: " . $e->getMessage());
    die("An error occurred while processing your order. Please try again later.");
}

$conn->close();
?>
