<?php
// Connect to MySQL database
include_once('connection.php');

// Get search query from GET parameters
$query = $_GET['query'];

// Escape special characters in search query
$query = mysqli_real_escape_string($conn, $query);

// Build SQL query to retrieve filtered user data
$sql = "SELECT * FROM User WHERE (username LIKE '%$query%') OR (email LIKE '%$query%')";

$result = mysqli_query($conn, $sql);
echo "<table id='user-table'>";
echo "<tr>";
echo "<th>ID</th>";
echo "<th>Tên tài khoản</th>";
echo "<th>Email</th>";
echo "<th>Địa chỉ</th>";
echo "<th>Phone</th>";
echo "</tr>";

// Loop through all rows and display user data in table
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr class='user-row' data-user-id='" . $row["id"] . "'>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["address"] . "</td>";
        echo "<td>" . $row["phone"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "Không tìm thấy";
}
?>