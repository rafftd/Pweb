<?php


require_once '../config/database.php'; // Menghubungkan ke database jika diperlukan
require_once '../includes/functions.php'; // Menyertakan fungsi yang diperlukan

// Ambil data dari database untuk ditampilkan di halaman
$dishes_result = query("SELECT * FROM dishes");
$galleries_result = query("SELECT * FROM gallery");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nusantara Cuisine - Eksplorasi Kuliner Indonesia</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <nav>
        <div class="logo">
            <img src="../assets/logo.png" alt="Logo Nusantara Cuisine">
            <span>NUSANTARA CUISINE</span>
        </div>
        <ul>
            <li><a href="#home" class="nav-link">HOME</a></li>
            <li><a href="#explore" class="nav-link">EXPLORE NOW</a></li>
            <li><a href="#gallery" class="nav-link">GALLERY</a></li>
        </ul>
        <!-- Tombol 'Add New Dish' hanya muncul jika admin sudah login -->
        <?php if (isset($_SESSION['admin_id'])): ?>
            <a href="../admin/index.php" class="add-dish-button">Add New Dish</a>
        <?php else: ?>
            <a href="../admin/login.php" class="add-dish-button">Login to Add New Dish</a>
        <?php endif; ?>
    </nav>

    <header id="home">
        <div class="hero">
            <div class="hero-logo"></div>
            <div class="hero-text">
                <h1>Authentic.<br>Traditional.<br>Delicious.</h1>
                <p>Jelajahi kekayaan cita rasa kuliner Nusantara</p>
                <a href="#explore" class="explore-button">Explore Now</a>
            </div>
        </div>
    </header>

    <section id="explore">
        <h2>Kuliner Nusantara</h2>
        <div class="dishes-grid">
            <?php while ($dish = fetch_assoc($dishes_result)): ?>
            <div class="dish-card" data-dish="<?php echo strtolower(str_replace(' ', '-', $dish['name'])); ?>">
                <img src="../assets/Images/dishes/<?php echo $dish['image']; ?>" alt="<?php echo $dish['name']; ?>">
                <div class="dish-info">
                    <h3><?php echo $dish['name']; ?></h3>
                    <p><?php echo $dish['origin']; ?></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section id="gallery">
        <h2>Gallery Kuliner</h2>
        <div class="gallery-grid">
            <?php while ($image = fetch_assoc($galleries_result)): ?>
            <div class="gallery-item">
                <img src="../assets/Images/dishes/<?php echo $image['file_name']; ?>" alt="<?php echo $image['caption']; ?>">
                <div class="gallery-caption"><?php echo $image['caption']; ?></div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <div id="dish-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="dish-details"></div>
        </div>
    </div>

    <script>
    const dishes = <?php
        $dishes_array = [];
        mysqli_data_seek($dishes_result, 0); // Reset pointer query ke awal
        while ($dish = fetch_assoc($dishes_result)) {
            $dishes_array[strtolower(str_replace(' ', '-', $dish['name']))] = [
                'name' => $dish['name'],
                'origin' => $dish['origin'],
                'image' => "../assets/Images/dishes/{$dish['image']}",
                'description' => $dish['description'],
                'cooking_time' => $dish['cooking_time'] ?? 'Tidak diketahui',
                'difficulty' => $dish['difficulty'] ?? 'Tidak diketahui',
                'calories' => $dish['calories'] ?? 'Tidak diketahui',
                'ingredients' => explode(',', $dish['ingredients'] ?? '')
            ];
        }
        echo json_encode($dishes_array);
    ?>;
</script>

    <script src="../assets/script.js"></script>
</body>
</html>
