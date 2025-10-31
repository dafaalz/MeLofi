<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=belum+login");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get cart data from POST
if (!isset($_POST['cart_data'])) {
    header("Location: store.php?error=invalid+cart");
    exit();
}

$cart_data = json_decode($_POST['cart_data'], true);

if (empty($cart_data)) {
    header("Location: store.php?error=empty+cart");
    exit();
}

include 'header.php';
include 'sidebar.php';
?>

<main class="app-content">
    <div class="checkout-container">
        <h1 class="page-title">Checkout Confirmation</h1>
        
        <div class="checkout-items">
            <h2>Your Order</h2>
            <div class="order-items">
                <?php
                $total = 0;
                $song_ids = [];
                
                foreach ($cart_data as $item) {
                    $song_id = intval($item['id']);
                    $song_ids[] = $song_id;
                    
                    // Verify song exists and user doesn't already own it
                    $checkQuery = "SELECT l.*, a.cover_album, a.nama_album, ar.nama_artis
                                   FROM lagu l
                                   JOIN album a ON l.id_album = a.id_album
                                   JOIN artis ar ON a.id_artis = ar.id_artis
                                   WHERE l.id_lagu = $song_id";
                    $checkResult = mysqli_query($connect, $checkQuery);
                    
                    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
                        $song = mysqli_fetch_assoc($checkResult);
                        
                        // Check if already owned
                        $ownQuery = "SELECT * FROM transaksi WHERE user_id = $user_id AND lagu_id = $song_id";
                        $ownResult = mysqli_query($connect, $ownQuery);
                        
                        if (mysqli_num_rows($ownResult) > 0) {
                            echo "<div class='order-item owned'>";
                            echo "<img src='" . htmlspecialchars($song['cover_album']) . "' alt='Cover'>";
                            echo "<div class='order-item-info'>";
                            echo "<h3>" . htmlspecialchars($song['judul']) . "</h3>";
                            echo "<p>" . htmlspecialchars($song['nama_artis']) . " • " . htmlspecialchars($song['nama_album']) . "</p>";
                            echo "<p class='already-owned'>✓ Already Owned</p>";
                            echo "</div>";
                            echo "</div>";
                        } else {
                            $price = floatval($item['price']);
                            $total += $price;
                            
                            echo "<div class='order-item'>";
                            echo "<img src='" . htmlspecialchars($song['cover_album']) . "' alt='Cover'>";
                            echo "<div class='order-item-info'>";
                            echo "<h3>" . htmlspecialchars($song['judul']) . "</h3>";
                            echo "<p>" . htmlspecialchars($song['nama_artis']) . " • " . htmlspecialchars($song['nama_album']) . "</p>";
                            echo "<div class='order-item-price'>$" . number_format($price, 2) . "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                }
                ?>
            </div>
            
            <div class="order-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Tax (0%):</span>
                    <span>$0.00</span>
                </div>
                <div class="summary-row total">
                    <span><strong>Total:</strong></span>
                    <span><strong>$<?php echo number_format($total, 2); ?></strong></span>
                </div>
            </div>
            
            <form action="process_purchase.php" method="POST" class="checkout-form">
                <input type="hidden" name="song_ids" value="<?php echo implode(',', $song_ids); ?>">
                
                <h3>Payment Information</h3>
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="">Select payment method</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="button secondary" onclick="window.location.href='store.php'">Cancel</button>
                    <button type="submit" class="button primary">Complete Purchase</button>
                </div>
            </form>
        </div>
    </div>
</main>

<style>
.checkout-container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 2rem;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
}

.checkout-items h2, .checkout-items h3 {
    font-family: 'Playfair Display', serif;
    margin-bottom: 1.5rem;
    color: #111111;
}

.order-items {
    margin-bottom: 2rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    border: 1px solid #eaeaea;
    border-radius: 12px;
    margin-bottom: 1rem;
    background: #fafafa;
}

.order-item.owned {
    opacity: 0.6;
}

.order-item img {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    object-fit: cover;
}

.order-item-info {
    flex: 1;
}

.order-item-info h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
}

.order-item-info p {
    margin: 0;
    color: #555;
}

.already-owned {
    color: #28a745 !important;
    font-weight: 600;
}

.order-item-price {
    font-size: 1.2rem;
    font-weight: 600;
    color: #111;
}

.order-summary {
    background: #f5f5f5;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
}

.summary-row.total {
    border-top: 2px solid #111;
    margin-top: 1rem;
    padding-top: 1rem;
    font-size: 1.2rem;
}

.checkout-form {
    margin-top: 2rem;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}
</style>

<?php include 'footer.php'; ?>
