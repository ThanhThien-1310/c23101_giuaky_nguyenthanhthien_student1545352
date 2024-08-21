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
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = intval($_GET['id']);
    
    // Lấy thông tin sách từ cơ sở dữ liệu
    $result = mysqli_query($conn, "SELECT books.*, authors.author_name, categories.category_name 
                                   FROM books 
                                   INNER JOIN authors ON books.author_id = authors.id 
                                   INNER JOIN categories ON books.category_id = categories.id 
                                   WHERE books.id = $book_id");
    if ($result && mysqli_num_rows($result) > 0) {
        $book = mysqli_fetch_assoc($result);
    } else {
        die("Book not found.");
    }
} else {
    die("Invalid book ID.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['title']) && !empty($_POST['author_name']) && !empty($_POST['category_name'])) {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $author_name = mysqli_real_escape_string($conn, $_POST['author_name']);
        $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
        $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
        $publish_year = intval($_POST['publish_year']);
        
        // Kiểm tra xem tác giả đã tồn tại chưa, nếu không thì thêm mới
        $author_query = mysqli_query($conn, "SELECT id FROM authors WHERE author_name = '$author_name'");
        if (mysqli_num_rows($author_query) > 0) {
            $author_id = mysqli_fetch_assoc($author_query)['id'];
        } else {
            // Thêm tác giả mới vào cơ sở dữ liệu
            $insert_author = mysqli_query($conn, "INSERT INTO authors (author_name) VALUES ('$author_name')");
            $author_id = mysqli_insert_id($conn);
        }
        
        // Kiểm tra xem thể loại đã tồn tại chưa, nếu không thì thêm mới
        $category_query = mysqli_query($conn, "SELECT id FROM categories WHERE category_name = '$category_name'");
        if (mysqli_num_rows($category_query) > 0) {
            $category_id = mysqli_fetch_assoc($category_query)['id'];
        } else {
            // Thêm thể loại mới vào cơ sở dữ liệu
            $insert_category = mysqli_query($conn, "INSERT INTO categories (category_name) VALUES ('$category_name')");
            $category_id = mysqli_insert_id($conn);
        }
        
        // Cập nhật thông tin sách trong cơ sở dữ liệu
        $sql = "UPDATE books SET 
                    title = '$title', 
                    author_id = $author_id, 
                    category_id = $category_id, 
                    publisher = '$publisher', 
                    publish_year = $publish_year 
                WHERE id = $book_id";
        
        if (mysqli_query($conn, $sql)) {
            echo '<h3 style="color:blue">Book updated successfully!</h3>';
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo '<h3 style="color:red">Please fill all required fields.</h3>';
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #4CAF50;
        }

        form {
            width: 50%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form table {
            width: 100%;
        }

        form td {
            padding: 10px 0;
        }

        form input[type="text"],
        form input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        a {
            text-decoration: none;
            color: #4CAF50;
            display: inline-block;
            margin-top: 20px;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #388E3C;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        td a {
            color: #4CAF50;
            text-decoration: none;
            margin: 0 5px;
            transition: color 0.3s ease;
        }

        td a:hover {
            color: #388E3C;
        }
    </style>
</head>
<body>
    <h2>Edit Book</h2>
    <form action="" method="post">
        <table>
            <tr>
                <td>Title book:</td>
                <td><input type="text" name="title" value="<?= htmlspecialchars($book['title']); ?>" required></td>
            </tr>
            <tr>
                <td>Author name:</td>
                <td><input type="text" name="author_name" value="<?= htmlspecialchars($book['author_name']); ?>" required></td>
            </tr>
            <tr>
                <td>Category name:</td>
                <td><input type="text" name="category_name" value="<?= htmlspecialchars($book['category_name']); ?>" required></td>
            </tr>
            <tr>
                <td>Publisher:</td>
                <td><input type="text" name="publisher" value="<?= htmlspecialchars($book['publisher']); ?>"></td>
            </tr>
            <tr>
                <td>Publish year:</td>
                <td><input type="number" name="publish_year" min="1900" max="<?= date('Y'); ?>" value="<?= htmlspecialchars($book['publish_year']); ?>"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;"><input type="submit" value="Update"></td>
            </tr>
        </table>
    </form>

    <br>
    <a href="index.php">View book list</a>
</body>
</html>
