<?php
$host = 'localhost';
$dbname = 'hocphp_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_GET['id'] ?? null;
    if (!$id) {
        header("Location: index.php");
        exit;
    }

    // Xử lý khi người dùng bấm nút Cập nhật
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $author = $_POST['author'];
        $page_count = $_POST['page_count'];

        $sql = "UPDATE books SET name = :name, price = :price, author = :author, page_count = :page_count WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'price' => $price,
            'author' => $author,
            'page_count' => $page_count,
            'id' => $id
        ]);

        header("Location: index.php");
        exit;
    }

    // Lấy thông tin cũ của sách ra để điền sẵn vào form
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        die("Không tìm thấy sách!");
    }

} catch (PDOException $e) {
    die("Lỗi: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin sách</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #fdfdfd; }
        .form-container { background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 500px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #2980b9; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; font-size: 16px; }
        button:hover { background: #2471a3; }
        a { margin-left: 10px; text-decoration: none; color: #555; }
    </style>
</head>
<body>

    <h2>Chỉnh sửa thông tin sách</h2>
    <div class="form-container">
        <form action="edit.php?id=<?php echo $book['id']; ?>" method="POST">
            <div class="form-group">
                <label>Tên sách:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($book['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Giá bán (VNĐ):</label>
                <input type="number" name="price" value="<?php echo $book['price']; ?>" required>
            </div>
            <div class="form-group">
                <label>Tác giả:</label>
                <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
            </div>
            <div class="form-group">
                <label>Số trang:</label>
                <input type="number" name="page_count" value="<?php echo $book['page_count']; ?>" required>
            </div>
            <button type="submit">Cập nhật</button>
            <a href="index.php">Quay lại</a>
        </form>
    </div>

</body>
</html>