<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nusantara Cuisine</title>
    <link rel="stylesheet" href="/assets/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <nav>
        <div class="logo">
            <img src="/assets/logo.png" alt="Logo Nusantara Cuisine">
            <span>NUSANTARA CUISINE</span>
        </div>
        <ul>
            <li><a href="/" class="nav-link">HOME</a></li>
            <li><a href="/#explore" class="nav-link">EXPLORE NOW</a></li>
            <li><a href="/#gallery" class="nav-link">GALLERY</a></li>
            <?php if(isset($_SESSION['admin_id'])): ?>
            <li><a href="/admin" class="nav-link">ADMIN</a></li>
            <li><a href="/admin/logout.php" class="nav-link">LOGOUT</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <?php
    $flash = get_flash_message();
    if ($flash): ?>
    <div class="alert alert-<?php echo $flash['type']; ?>">
        <?php echo $flash['message']; ?>
    </div>
    <?php endif; ?>

<?php
// includes/footer.php
?>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Nusantara Cuisine. All rights reserved.</p>
    </footer>
    <script src="/assets/script.js"></script>
</body>
</html>