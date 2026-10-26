<?php
$host = 'localhost';
$dbname = 'hocphp_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lấy ID từ trên URL truyền xuống
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        // Câu lệnh SQL xóa bản ghi theo ID
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    // Xóa xong quay lại trang chủ
    header("Location: index.php");
    exit;

} catch (PDOException $e) {
    die("Lỗi xóa dữ liệu: " . $e->getMessage());
}
?>