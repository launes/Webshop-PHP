<?php
session_start();
require_once 'db_connect.php';

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get database connection
$conn = erstelleDatenbankverbindung();

// Add item to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = max(1, (int)$_POST['quantity']);
    
    if (isset($_SESSION['user_id'])) {
        // Add to database if user is logged in
        $stmt = $conn->prepare("SELECT addCart(?, ?, ?)");
        $stmt->bind_param("iii", $_SESSION['user_id'], $product_id, $quantity);
        $stmt->execute();
    } else {
        // Add to session if user is not logged in
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    header('Location: cart.php');
    exit;
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    if (isset($_SESSION['user_id'])) {
        // Remove from database if user is logged in
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $_SESSION['user_id'], $remove_id);
        $stmt->execute();
    } else {
        // Remove from session if user is not logged in
        unset($_SESSION['cart'][$remove_id]);
    }
    header('Location: cart.php');
    exit;
}

// Function to get product details from database
function getProduct($conn, $id) {
    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get cart items
$cart_items = [];
$total = 0;

if (isset($_SESSION['user_id'])) {
    // Get cart items from database if user is logged in
    $stmt = $conn->prepare("SELECT c.product_id, c.quantity, p.name, p.price 
                           FROM cart c 
                           JOIN products p ON c.product_id = p.id 
                           WHERE c.user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = [
            'id' => $row['product_id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $row['quantity']
        ];
        $total += $row['price'] * $row['quantity'];
    }
} else {
    // Get cart items from session if user is not logged in
    foreach ($_SESSION['cart'] as $id => $qty) {
        $product = getProduct($conn, $id);
        if ($product) {
            $cart_items[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $qty
            ];
            $total += $product['price'] * $qty;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
<h1>Your Cart</h1>
<?php if (empty($cart_items)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
        <?php foreach ($cart_items as $item): 
            $subtotal = $item['price'] * $item['quantity'];
        ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>€<?= number_format($item['price'], 2) ?></td>
            <td>€<?= number_format($subtotal, 2) ?></td>
            <td><a href="?remove=<?= $item['id'] ?>">Remove</a></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td colspan="2"><strong>€<?= number_format($total, 2) ?></strong></td>
        </tr>
    </table>
    
    <p><a href="checkout.php" class="button">Proceed to Checkout</a></p>
<?php endif; ?>

<h2>Add Product</h2>
<?php
// Get available products from database
$stmt = $conn->prepare("SELECT id, name, price FROM products");
$stmt->execute();
$result = $stmt->get_result();
?>
<form method="post">
    <label>Product: 
        <select name="product_id" required>
            <?php while ($product = $result->fetch_assoc()): ?>
                <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?> - €<?= number_format($product['price'], 2) ?></option>
            <?php endwhile; ?>
        </select>
    </label>
    <label>Quantity: <input type="number" name="quantity" min="1" value="1" required></label>
    <button type="submit">Add to Cart</button>
</form>

<?php $conn->close(); ?>
</body>
</html>