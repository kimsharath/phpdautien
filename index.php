<?php
// Thông tin kết nối CSDL MySQL
$host = 'localhost';
$dbname = 'hocphp_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Xử lý khi người dùng bấm nút "Lưu sách" từ Form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $author = $_POST['author'] ?? '';
        $page_count = $_POST['page_count'] ?? 0;

        // Kiểm tra dữ liệu không để trống
        if (!empty($name) && !empty($price) && !empty($author) && !empty($page_count)) {
            // Câu lệnh SQL thêm dữ liệu sử dụng Prepared Statement để bảo mật
            $sql = "INSERT INTO books (name, price, author, page_count) VALUES (:name, :price, :author, :page_count)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name' => $name,
                'price' => $price,
                'author' => $author,
                'page_count' => $page_count
            ]);

            // Chuyển hướng lại trang chính để tránh bị gửi lại form khi nhấn F5
            header("Location: index.php");
            exit;
        }
    }

    // Truy vấn lấy toàn bộ dữ liệu từ bảng books
    $stmt = $pdo->query("SELECT * FROM books");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Lỗi kết nối hoặc truy vấn: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sách - Thêm và Hiển thị</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #fdfdfd; }
        h2 { color: #2c3e50; }
        .form-container { background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px; width: 500px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #27ae60; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; font-size: 16px; }
        button:hover { background: #219653; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #ffffff; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        tr:hover { background-color: #f9f9f9; }
    </style>
</head>
<body>

    <h2>Thêm Sách Mới Vào Database</h2>
    <div class="form-container">
        <form action="index.php" method="POST">
            <div class="form-group">
                <label>Tên sách:</label>
                <input type="text" name="name" required placeholder="Nhập tên sách...">
            </div>
            <div class="form-group">
                <label>Giá bán (VNĐ):</label>
                <input type="number" name="price" required placeholder="Nhập giá tiền...">
            </div>
            <div class="form-group">
                <label>Tác giả:</label>
                <input type="text" name="author" required placeholder="Nhập tên tác giả...">
            </div>
            <div class="form-group">
                <label>Số trang:</label>
                <input type="number" name="page_count" required placeholder="Nhập số trang...">
            </div>
            <button type="submit">Lưu sách</button>
        </form>
    </div>

    <h2>Danh mục Sách Hiện Có</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Sách</th>
                <th>Giá bán</th>
                <th>Tác giả</th>
                <th>Số trang</th>
                <th>Thao tac</th>
</tr>

        </thead>
       <tbody>
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td>SP0<?php echo $book['id']; ?></td>
                        <td><?php echo htmlspecialchars($book['name']); ?></td>
                        <td><?php echo number_format($book['price']); ?> VNĐ</td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo $book['page_count']; ?></td>
                        <td>
                            <!-- Nút Xóa -->
                            <a href="delete.php?id=<?php echo $book['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sách này không?');" style="color: red; text-decoration: none; margin-right: 10px;">Xóa</a>
                            
                            <!-- Nút Sửa -->
                            <a href="edit.php?id=<?php echo $book['id']; ?>" style="color: blue; text-decoration: none;">Sửa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Chưa có dữ liệu sách nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>