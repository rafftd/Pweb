<?php
// add_dish.php
require_once '../config/database.php';
require_once '../includes/functions.php';
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize inputs
    $name = $_POST['name'];
    $origin = $_POST['origin'];
    $cooking_time = $_POST['cooking_time'];
    $difficulty = $_POST['difficulty'];
    $calories = $_POST['calories'];
    $description = $_POST['description'];
    $ingredients = $_POST['ingredients']; // New field for ingredients
    $category_id = $_POST['category_id']; // Dapatkan category_id dari form
    
    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $target_directory = '../assets/Images/dishes/';
        $target_file = $target_directory . basename($image['name']);
        
        // Move uploaded file to target directory
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            // Prepare SQL insert statement
            $sql = "INSERT INTO dishes (name, category_id, origin, cooking_time, difficulty, image, description, ingredients, calories) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; // Added new fields
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sissssssi", $name, $category_id, $origin, $cooking_time, $difficulty, $image['name'], $description, $ingredients, $calories);
            
            if ($stmt->execute()) {
                set_flash_message('success', 'Makanan berhasil ditambahkan.');
            } else {
                set_flash_message('error', 'Gagal menambahkan makanan.');
            }
        } else {
            set_flash_message('error', 'Gagal mengupload gambar.');
        }
    } else {
        set_flash_message('error', 'Gambar tidak valid.');
    }
    
    // Redirect back to dishes page
    header('Location: dishes.php');
    exit();
}

// Ambil kategori dari database
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Makanan/Minuman</title>
</head>
<body>
    <h1>Tambah Makanan/Minuman</h1>
    <form action="add_dish.php" method="POST" enctype="multipart/form-data">
        <label for="name">Nama:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="origin">Asal:</label>
        <input type="text" id="origin" name="origin" required>
        
        <label for="cooking_time">Waktu Memasak:</label>
        <input type="text" id="cooking_time" name="cooking_time" required>
        
        <label for="difficulty">Tingkat Kesulitan:</label>
        <input type="text" id="difficulty" name="difficulty" required>
        
        <label for="calories">Kalori:</label>
        <input type="number" id="calories" name="calories" required>
        
        <label for="description">Deskripsi:</label>
        <textarea id="description" name="description" required></textarea>
        
        <label for="ingredients">Bahan-bahan:</label>
        <textarea id="ingredients" name="ingredients" placeholder="Pisahkan dengan koma" required></textarea>
        
        <label for="category_id">Kategori:</label>
        <select id="category_id" name="category_id" required>
            <?php while ($row = $result_categories->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select>
        
        <label for="image">Gambar:</label>
        <input type="file" id="image" name="image" required>
        
        <button type="submit">Tambah Makanan</button>
    </form>
    
    <a href="dishes.php">Kembali ke Daftar Makanan</a>
</body>
</html>
