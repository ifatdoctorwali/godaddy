<?php
session_start();
include('db.php');

// Handle domain search
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $searchQuery = htmlspecialchars(trim($_POST['search']));
    try {
        $stmt = $conn->prepare("SELECT * FROM domains WHERE domain_name LIKE :search");
        $stmt->execute(['search' => "%$searchQuery%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Search error: " . $e->getMessage();
    }
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $domain_name = $_POST['domain_name'];
    $price = $_POST['price'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $_SESSION['cart'][] = [
        'domain_name' => $domain_name,
        'price' => $price,
        'quantity' => 1
    ];
    
    echo "<script>alert('Domain added to cart successfully!');</script>";
}

// Get cart count
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Search - Find Your Perfect Domain Name</title>
    <style>
        :root {
            --primary-color: #00838C;
            --secondary-color: #111111;
            --accent-color: #FF5C35;
            --success-color: #00A4A6;
            --light-gray: #F5F7F8;
            --border-color: #E9EBED;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'GD Sherpa', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: #ffffff;
            color: var(--secondary-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .cart-icon {
            position: relative;
            padding: 10px 20px;
            text-decoration: none;
            color: var(--secondary-color);
        }

        .cart-count {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        .hero {
            text-align: center;
            padding: 60px 0;
            background: var(--light-gray);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--secondary-color);
        }

        .search-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .search-form {
            display: flex;
            gap: 10px;
            background: white;
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .search-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            font-size: 18px;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .search-button {
            padding: 15px 30px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .search-button:hover {
            background: #006d74;
        }

        .results-container {
            margin-top: 40px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .domain-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .domain-item:last-child {
            border-bottom: none;
        }

        .domain-name {
            font-size: 20px;
            font-weight: bold;
            color: var(--secondary-color);
        }

        .domain-price {
            font-size: 24px;
            font-weight: bold;
            color: var(--success-color);
        }

        .add-to-cart-form {
            display: inline;
        }

        .add-to-cart-button {
            padding: 10px 20px;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .add-to-cart-button:hover {
            background: #e54e2b;
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .search-form {
                flex-direction: column;
                gap: 10px;
            }

            .domain-item {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .search-input, .search-button {
                width: 100%;
            }

            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <a href="cart.php" class="cart-icon">
                Cart 
                <?php if ($cartCount > 0): ?>
                    <span class="cart-count"><?php echo $cartCount; ?></span>
                <?php endif; ?>
            </a>
        </div>
    </header>

    <div class="hero">
        <div class="container">
            <h1>Find Your Perfect Domain Name</h1>
            <div class="search-container">
                <form method="POST" action="index.php" class="search-form">
                    <input 
                        type="text" 
                        name="search" 
                        class="search-input"
                        placeholder="Enter your domain name idea"
                        value="<?php echo isset($searchQuery) ? htmlspecialchars($searchQuery) : ''; ?>"
                        required
                    >
                    <button type="submit" class="search-button">Search Domains</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="results-container">
            <?php if (isset($error)): ?>
                <div class="no-results"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (isset($results)): ?>
                <?php if (empty($results)): ?>
                    <div class="no-results">
                        <p>No domains found matching '<?php echo htmlspecialchars($searchQuery); ?>'</p>
                        <p>Try a different search term or contact our support team for help.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($results as $domain): ?>
                        <div class="domain-item">
                            <span class="domain-name"><?php echo htmlspecialchars($domain['domain_name']); ?></span>
                            <span class="domain-price">$<?php echo number_format($domain['price'], 2); ?></span>
                            <form method="POST" action="index.php" class="add-to-cart-form">
                                <input type="hidden" name="domain_name" value="<?php echo htmlspecialchars($domain['domain_name']); ?>">
                                <input type="hidden" name="price" value="<?php echo $domain['price']; ?>">
                                <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
