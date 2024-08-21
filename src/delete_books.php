<?php
// Kết nối đến cơ sở dữ liệu
$host = "localhost:3306";
$username = "root";
$password = "";
$database = "book_management";
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Kiểm tra nếu ID của sách đã được gửi qua URL
if (isset($_GET['id'])) {
    $book_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Xóa sách khỏi cơ sở dữ liệu
    $sql = "DELETE FROM books WHERE id = $book_id";
    
    if (mysqli_query($conn, $sql)) {
        echo "Book deleted successfully!";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid book ID.";
}

// Đóng kết nối
mysqli_close($conn);

// Chuyển hướng trở lại danh sách sách
header("Location: index.php");
exit;
?>
