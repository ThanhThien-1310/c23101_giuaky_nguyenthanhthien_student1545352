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

// Lấy danh sách sách 
if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $sql = "SELECT books.*, categories.category_name, authors.author_name 
            FROM books 
            INNER JOIN categories ON books.category_id = categories.id 
            INNER JOIN authors ON books.author_id = authors.id 
            WHERE books.title LIKE '%$search%' 
            OR categories.category_name LIKE '%$search%' 
            OR authors.author_name LIKE '%$search%'";
} else {
    $sql = "SELECT books.*, categories.category_name, authors.author_name 
            FROM books 
            INNER JOIN categories ON books.category_id = categories.id 
            INNER JOIN authors ON books.author_id = authors.id";
}

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List books</title>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <h2 class="my-4 text-center">List books</h2>
        <form action="" method="post" class="d-flex mb-4">
            <input type="text" name="search" class="form-control me-2" placeholder="Search for book title" value="<?php echo $_POST['search'] ?? '' ?>">
            <button type="submit" class="btn btn-success">Search</button>
        </form>

        <?php
        // Kiểm tra và hiển thị kết quả 
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<table class='table table-hover table-bordered'>
                    <thead class='table-light'>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Publisher</th>
                            <th>Publish Year</th>
                            <th>Edit/Delete</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["title"] . "</td>
                        <td>" . $row["author_name"] . "</td>
                        <td>" . $row["category_name"] . "</td>
                        <td>" . $row["publisher"] . "</td>
                        <td>" . $row["publish_year"] . "</td>
                        <td>
                            <a href='edit_books.php?id=" . $row["id"] . "' class='btn btn-sm btn-primary me-2'>Edit</a>
                            <a href='delete_books.php?id=" . $row["id"] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this book?');\">Delete</a>
                        </td>
                    </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-warning'>No results found for '<strong>" . ($_POST['search'] ?? '') . "</strong>'.</div>";
        }

        // Đóng kết nối
        mysqli_close($conn);
        ?>
        <a href="add_books.php" class="btn btn-success mt-4">Add new book</a>
    </div>

    <!-- Thêm jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
