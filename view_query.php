<?php
include 'config/db.php';  
include 'inc/header.php';     


$query = "SELECT * FROM contact_query";
$result = $conn->query($query);

echo "<div class='container2'>";
echo "<div class='header-container'>
        <h1>Contact Queries</h1>
        <a href='contact_form.php' class='btn1 btn-primary'>Query</a>
      </div>";
if ($result->num_rows > 0) {
  echo "<table border='1' class='table table-striped'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>";

  while ($row = $result->fetch_assoc()) {
    echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["phone"] . "</td>
                <td>" . $row["course"] . "</td>
                <td>" . $row["message"] . "</td>
                <td>" . $row["submitted_at"] . "</td>
              </tr>";
  }
  echo "</tbody></table>";
} else {
  echo "<p>No contact queries found.</p>";
}

echo "</div>";  // Close container

$conn->close();
include('inc/footer.php');     // Include footer file
