<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
check_login();

// Get total counts
$total_dishes = fetch_assoc(query("SELECT COUNT(*) as total FROM dishes"))['total'];
$total_categories = fetch_assoc(query("SELECT COUNT(*) as total FROM categories"))['total'];
$total_gallery = fetch_assoc(query("SELECT COUNT(*) as total FROM gallery"))['total'];

// Handle form submission for adding a new dish
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_dish'])) {
    $name = $_POST['name'];
    $origin = $_POST['origin'];
    $cooking_time = $_POST['cooking_time'];
    $difficulty = $_POST['difficulty'];
    $calories = $_POST['calories'];
    $description = $_POST['description'];
    $ingredients = $_POST['ingredients'];
    $category_id = $_POST['category_id'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_type = $_FILES['image']['type'];

        // Validate image type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image_type, $allowed_types)) {
            // Move the image to the target directory
            $target_path = "../assets/Images/dishes/" . $image_name;
            if (move_uploaded_file($image_tmp_name, $target_path)) {
                // Insert into database
                query("INSERT INTO dishes (name, origin, cooking_time, difficulty, calories, description, ingredients, category_id, image) 
                       VALUES ('$name', '$origin', '$cooking_time', '$difficulty', '$calories', '$description', '$ingredients', '$category_id', '$image_name')");
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Gagal mengupload gambar.";
            }
        } else {
            $error_message = "Jenis file tidak valid. Harap unggah gambar JPEG, PNG, atau GIF.";
        }
    } else {
        $error_message = "Terjadi kesalahan saat mengupload gambar.";
    }
}

// Fetch recent dishes
$dishes = query("SELECT d.*, c.name as category_name FROM dishes d LEFT JOIN categories c ON d.category_id = c.id ORDER BY d.created_at DESC");
$categories = query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Nusantara Cuisine</title>
    <link rel="stylesheet" href="../assets/stylesadmin.css">
</head>
<body>
    <nav>
        <div class="logo">
            <img src="../assets/logo.png" alt="Logo Nusantara Cuisine">
            <span>NUSANTARA CUISINE</span>
        </div>
        <ul>
            <li><a href="../index.html" class="nav-link">HOME</a></li>
            <li><a href="#explore" class="nav-link">EXPLORE NOW</a></li>
            <li><a href="#gallery" class="nav-link">GALLERY</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <h1>Dashboard Admin</h1>

        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Makanan</h3>
                <p><?php echo $total_dishes; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Kategori</h3>
                <p><?php echo $total_categories; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Galeri</h3>
                <p><?php echo $total_gallery; ?></p>
            </div>
        </div>

        <!-- Form Tambah Makanan Baru -->
        <div class="add-dish-section">
            <h2>Tambah Makanan Baru</h2>
            <?php if (isset($error_message)) : ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form action="index.php" method="POST" enctype="multipart/form-data">
                <label for="name">Nama Makanan:</label>
                <input type="text" name="name" id="name" required>

                <label for="origin">Asal Makanan:</label>
                <input type="text" name="origin" id="origin" required>

                <label for="cooking_time">Waktu Memasak:</label>
                <input type="text" name="cooking_time" id="cooking_time" required>

                <label for="difficulty">Kesulitan:</label>
                <input type="text" name="difficulty" id="difficulty" required>

                <label for="calories">Kalori:</label>
                <input type="number" name="calories" id="calories" required>

                <label for="description">Deskripsi:</label>
                <textarea name="description" id="description" required></textarea>

                <label for="ingredients">Bahan-bahan (pisahkan dengan koma):</label>
                <textarea name="ingredients" id="ingredients" required></textarea>

                <label for="category_id">Kategori:</label>
                <select name="category_id" id="category_id" required>
                    <option value="">Pilih Kategori</option>
                    <?php while ($cat = fetch_assoc($categories)): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="image">Gambar:</label>
                <input type="file" name="image" id="image" accept="image/*" required>

                <input type="submit" name="add_dish" value="Tambah Makanan" class="btn">
            </form>
        </div>

        <!-- Tabel Makanan -->
        <div class="recent-items">
            <h2>Daftar Makanan</h2>
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Asal</th>
                        <th>Waktu Memasak</th>
                        <th>Kesulitan</th>
                        <th>Kalori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($dish = fetch_assoc($dishes)): ?>
                    <tr>
                        <td>
                            <img src="../assets/Images/dishes/<?php echo $dish['image']; ?>" alt="<?php echo $dish['name']; ?>" class="thumbnail">
                        </td>
                        <td><?php echo $dish['name']; ?></td>
                        <td><?php echo $dish['category_name']; ?></td>
                        <td><?php echo $dish['origin']; ?></td>
                        <td><?php echo $dish['cooking_time']; ?></td>
                        <td><?php echo $dish['difficulty']; ?></td>
                        <td><?php echo $dish['calories']; ?></td>
                        <td>
                            <a href="edit_dish.php?id=<?php echo $dish['id']; ?>" class="btn btn-small">Edit</a>
                            <a href="delete_dish.php?id=<?php echo $dish['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../assets/script.js"></script>
</body>
</html>
