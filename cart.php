<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .cart-empty {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .cart-empty a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .cart-empty a:hover {
            background-color: #0056b3;
        }

        .cart-items {
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .domain-name {
            flex: 1;
            font-size: 16px;
            color: #333;
        }

        .price {
            font-weight: bold;
            color: #28a745;
            margin: 0 20px;
        }

        .remove-btn {
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .remove-btn:hover {
            background-color: #c82333;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-top: 20px;
        }

        .total-text {
            font-size: 18px;
            font-weight: bold;
        }

        .total-amount {
            font-size: 24px;
            color: #28a745;
            font-weight: bold;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            color: white;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .checkout-btn:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .price {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Shopping Cart</h1>
        
        <?php
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $total = 0;
            ?>
            <div class="cart-items">
                <?php
                foreach ($_SESSION['cart'] as $key => $item) {
                    $total += $item['price'];
                    ?>
                    <div class="cart-item">
                        <div class="domain-name"><?php echo htmlspecialchars($item['domain_name']); ?></div>
                        <div class="price">$<?php echo number_format($item['price'], 2); ?></div>
                        <form method="post" action="remove-from-cart.php" style="display: inline;">
                            <input type="hidden" name="item_key" value="<?php echo $key; ?>">
                            <button type="submit" class="remove-btn">Remove</button>
                        </form>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div class="cart-total">
                <span class="total-text">Total:</span>
                <span class="total-amount">$<?php echo number_format($total, 2); ?></span>
            </div>

            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            <?php
        } else {
            ?>
            <div class="cart-empty">
                <p>Your cart is empty!</p>
                <a href="domains.php">Browse our domains to add items to your cart</a>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>
