<?php
// admin/dishes.php
require_once '../config/database.php';
require_once '../includes/functions.php';
check_login();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Get total records
$result = query("SELECT COUNT(*) as total FROM dishes");
$total_records = fetch_assoc($result)['total'];

// Get dishes with pagination
$sql = "SELECT d.*, c.name as category_name 
        FROM dishes d 
        LEFT JOIN categories c ON d.category_id = c.id 
        ORDER BY d.created_at DESC 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $records_per_page, $offset);
$stmt->execute();
$dishes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
?>

<div class="content-container">
    <div class="content-header">
        <h1>Kelola Makanan</h1>
        <a href="add_dish.php" class="btn btn-primary">Tambah Makanan</a>
    </div>
    
    <?php $flash = get_flash_message(); ?>
    <?php if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type']; ?>">
            <?php echo $flash['message']; ?>
        </div>
    <?php endif; ?>

    <!-- Form for adding a new dish -->
    <div class="add-dish-form">
        <h2>Tambah Makanan Baru</h2>
        <form action="add_dish.php" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Nama Makanan" required>
            <input type="text" name="origin" placeholder="Asal" required>
            <input type="text" name="cooking_time" placeholder="Waktu Memasak" required>
            <input type="text" name="difficulty" placeholder="Kesulitan" required>
            <input type="number" name="calories" placeholder="Kalori" required>
            <textarea name="description" placeholder="Deskripsi" required></textarea>
            <textarea name="ingredients" placeholder="Bahan-bahan (pisahkan dengan koma)" required></textarea>
            <select name="category_id" required>
                <option value="">Pilih Kategori</option>
                <?php
                // Fetch categories from the database to populate the dropdown
                $categories = query("SELECT * FROM categories");
                while ($category = fetch_assoc($categories)) {
                    echo "<option value='{$category['id']}'>{$category['name']}</option>";
                }
                ?>
            </select>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    </div>

    <table class="data-table">
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
            <?php foreach ($dishes as $dish): ?>
            <tr>
                <td>
                    <img src="../assets/Images/dishes/<?php echo $dish['image']; ?>" 
                         alt="<?php echo $dish['name']; ?>"
                         class="thumbnail">
                </td>
                <td><?php echo $dish['name']; ?></td>
                <td><?php echo $dish['category_name']; ?></td>
                <td><?php echo $dish['origin']; ?></td>
                <td><?php echo $dish['cooking_time']; ?></td>
                <td><?php echo $dish['difficulty']; ?></td>
                <td><?php echo $dish['calories']; ?></td>
                <td>
                    <a href="edit_dish.php?id=<?php echo $dish['id']; ?>" 
                       class="btn btn-small">Edit</a>
                    <a href="delete_dish.php?id=<?php echo $dish['id']; ?>" 
                       class="btn btn-small btn-danger"
                       onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php echo generate_pagination($total_records, $records_per_page, $page); ?>
</div>

<?php include '../admin/admin_footer.php'; ?>