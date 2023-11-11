
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<h1>Đơn hàng</h1>
<table>
    <thead>        
        <tr>
            <th>ID #</th>
            <th>Ngày đặt</th>
            <th>Ngày giao</th>
            <th>Tổng</th>
            <th>Tên tài khoản</th>
        </tr>
    </thead>
    <tbody id="order-table-body">
        <?php
        // Kết nối đến cơ sở dữ liệu
        include_once("connection.php");
        // Truy vấn danh sách đơn hàng
        
        // Xử lý lấy giá trị date từ dropdown menu
        $filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

        // Thêm điều kiện WHERE vào câu truy vấn nếu có ngày được chọn
        $where = $filter_date ? " WHERE order_date = '{$filter_date}'" : '';

        // Câu truy vấn cơ sở dữ liệu
        $sql = "SELECT * FROM invoice {$where} ORDER BY id DESC";

        // Thực hiện truy vấn
        $result = $conn->query($sql);

        // Hiển thị danh sách đơn hàng
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Lấy thông tin người dùng
                $user_id = $row["user_id"];
                $user_sql = "SELECT * FROM User WHERE id = $user_id";
                $user_result = $conn->query($user_sql);
                $user_row = $user_result->fetch_assoc();

                // Lấy danh sách sản phẩm trong đơn hàng
                $invoice_id = $row["id"];
                $product_sql = "SELECT * FROM InvoiceDetail WHERE invoice_id = $invoice_id";
                $product_result = $conn->query($product_sql);

                ?>
                <tr>
                    <td><a href="?mpage=view_invoice&&id=<?php echo $row["id"]; ?>">#<?php echo $row["invoice_number"]; ?></a>
                    </td>
                    <td>
                        <?php echo $row["order_date"]; ?>
                    </td>
                    <td>
                        <?php echo $row["delivery_date"]; ?>
                    </td>
                    <td>
                        <?php echo $row["total"]; ?>
                    </td>
                    <td>
                        <?php echo $user_row["username"]; ?>
                    </td>                    
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='7'>No orders yet</td></tr>";
        }
        ?>
    </tbody>
</table>

