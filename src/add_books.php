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

// Kiểm tra xem dữ liệu được gửi từ form không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['title']) && !empty($_POST['author_name']) && !empty($_POST['category_name'])) {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
        $author_name = mysqli_real_escape_string($conn, $_POST['author_name']);
        $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
        $publish_year = intval($_POST['publish_year']);

        // Kiểm tra và thêm tác giả nếu chưa tồn tại
        $author_result = mysqli_query($conn, "SELECT id FROM authors WHERE author_name = '$author_name'");
        if (mysqli_num_rows($author_result) > 0) {
            $author_id = mysqli_fetch_assoc($author_result)['id'];
        } else {
            mysqli_query($conn, "INSERT INTO authors (author_name) VALUES ('$author_name')");
            $author_id = mysqli_insert_id($conn);
        }

        // Kiểm tra và thêm thể loại nếu chưa tồn tại
        $category_result = mysqli_query($conn, "SELECT id FROM categories WHERE category_name = '$category_name'");
        if (mysqli_num_rows($category_result) > 0) {
            $category_id = mysqli_fetch_assoc($category_result)['id'];
        } else {
            mysqli_query($conn, "INSERT INTO categories (category_name) VALUES ('$category_name')");
            $category_id = mysqli_insert_id($conn);
        }

        // Chuẩn bị câu lệnh SQL để thêm sách mới 
        $sql = "INSERT INTO books (title, category_id, author_id, publisher, publish_year) 
                VALUES ('$title', $category_id, $author_id, '$publisher', $publish_year)";

        if (mysqli_query($conn, $sql)) {
            echo '<h3 style="color:blue">Sách đã được thêm thành công</h3>';
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo '<h3 style="color:red">Cần điền tất cả các trường bắt buộc</h3>';
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new book</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 0 auto;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        h2, a {
            margin-left: 25%;
        }
    </style>
</head>
<body>
    <h2>Add new book</h2>
    <form action="" method="post">
        <table>
            <tr>
                <td>Title book:</td>
                <td><input type="text" name="title" required></td>
            </tr>
            <tr>
                <td>Author name:</td>
                <td><input type="text" name="author_name" required></td>
            </tr>
            <tr>
                <td>Category name:</td>
                <td><input type="text" name="category_name" required></td>
            </tr>
            <tr>
                <td>Publisher:</td>
                <td><input type="text" name="publisher"></td>
            </tr>
            <tr>
                <td>Publish year:</td>
                <td><input type="number" name="publish_year" min="1900" max="<?php echo date('Y'); ?>"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;"><input type="submit" value="Add"></td>
            </tr>
        </table>
    </form>

    <br>
    <a href="index.php">View book list</a>
</body>
</html>

</html>