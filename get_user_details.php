<?php
include('admin/db_connect.php');

if(isset($_POST['value']) && isset($_POST['type'])) {
  $value = $_POST['value'];
  $type = $_POST['type'];
  
  $query = "SELECT CONCAT(firstname, ' ', middlename, ' ', lastname) AS full_name, $type AS category FROM users WHERE $type = '$value'";
  $result = $conn->query($query);
  
  if ($result->num_rows > 0) {
    echo "<table class='table'>";
    echo "<thead><tr><th>Name</th><th>Category</th></tr></thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row['full_name'] . "</td>";
      echo "<td>" . $row['category'] . "</td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  } else {
    echo "No users found for this $type.";
  }
}
?>
