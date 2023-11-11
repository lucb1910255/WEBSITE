<!DOCTYPE html>
<html>
<head>
	<title>Quản lí tài khoản</title>
	<style>
		table {
			border-collapse: collapse;
			width: 90%;
		}

		th, td {
			text-align: left;
			padding: 8px;
			border-bottom: 1px solid #ddd;
		}

		tr:hover {background-color: #f5f5f5;}

		.order-list {
			padding: 8px;
			background-color: #f5f5f5;
		}

		.order-list table {
			border-collapse: collapse;
			width: 90%;
		}

		.order-list th, .order-list td {
			text-align: left;
			padding: 8px;
			border-bottom: 1px solid #ddd;
		}
		.searchBar{
	position: relative;
	height: 60px;
  }
  .search-container {
	display: flex;
	justify-content: center;
	padding-top: 10px;
  }
  .search-container .searchForm{
	display: flex;
	justify-content: center;
	width: 100%;
  }
  
  .search-container input[type=text] {
	padding: 10px;
	margin-right: 5px;
	border: 1px solid black;
	font-size: 15px;
	width: 50%;
	border-radius:5px ;
  }
  
  .search-container button {
	border: none;
	background: #2196F3;
	color: white;
	font-size: 17px;
	cursor: pointer;
  }
  
  .search-container button:hover {
	background: #0b7dda;
  }

  /* notice no result search*/
  .not-found{
	display: none;
	text-align: center;
	margin: 10px;
	color:darkgrey
  }
	</style>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
	$(document).ready(function() {
		var clickCount = {}; // Object to store click counts for each user ID

		// Handle click event on user row
		$("table#user-table").on("click", "tr.user-row", function() {
			// Get user ID from data attribute
			var userId = $(this).data("user-id");

			// Send AJAX request to get orders for user
			if (!clickCount[userId]) {
				clickCount[userId] = 1;
			} else {
				clickCount[userId]++;
			}

			$.ajax({
				method: "GET",
				url: "get_orders.php",
				data: { user_id: userId }
			}).done(function(data) {
				var orderList = $("tr.order-list[data-user-id=" + userId + "]");

				if (clickCount[userId] % 2 == 1) {
					// Show order list below user row
					$("tr.user-row[data-user-id=" + userId + "]").after("<tr class='order-list' data-user-id='" + userId + "'><td colspan='5'><table>" + data + "</table></td></tr>");
				} else {
					// Hide order list
					orderList.hide();
				}
			});
		});
	});
</script>
</head>
<body>
	
	<h1>Quản lí tài khoản</h1>
	<div class="searchBar">
    <div class="search-container">
        <input type="text" placeholder="Tìm kiếm tài khoản . . ." name="search" onkeyup="searchUsers(this.value)">
        <button type="submit" name="search-btn"><i class="fa fa-search"></i></button>
    </div>
</div>
	<table id="user-table">
		<tr>
			<th>ID</th>
			<th>Tên tài khoản</th>
			<th>Email</th>
			<th>Địa chỉ</th>
			<th>Phone</th>
		</tr>
		<?php
			// Connect to MySQL database
			include_once('connection.php');
			// Select all users from User table
			$sql = "SELECT * FROM User where password!='null'";
			$result = mysqli_query($conn, $sql);

			// Loop through all rows and display user data in table
			if (mysqli_num_rows($result) > 0) {
			    while($row = mysqli_fetch_assoc($result)){echo "<tr class='user-row' data-user-id='" . $row["id"] . "'>";
			        echo "<td>" . $row["id"] . "</td>";
			        echo "<td>" . $row["username"] . "</td>";
			        echo "<td>" . $row["email"] . "</td>";
			        echo "<td>" . $row["address"] . "</td>";
			        echo "<td>" . $row["phone"] . "</td>";
			        echo "</tr>";
			    }
			} else {
			    echo "0 results";
			}
		?>
        
	</table>
</body>
<script>
	function searchUsers(query) {
    $.ajax({
        method: "GET",
        url: "search_users.php",
        data: { query: query }
    }).done(function(data) {
        // Replace table with filtered user data
        $("table#user-table").html(data);
    });
}
	</script>
</html>
