<?php
include "config/db.php";

if (isset($_GET['id'])) {
  $blog_id = $_GET['id'];

  // Prepare the SQL query to delete the banner
  $query = "DELETE FROM blogs WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $blog_id);

  // Execute the query
  if ($stmt->execute()) {
    echo "Blog deleted successfully.";
    // Redirect back to the banner list
    header("Location: show_blog.php");
    exit;
  } else {
    echo "Error deleting banner: " . $stmt->error;
  }

  // Close the statement
  $stmt->close();
} else {
  echo "No blog ID provided.";
}

// Close the connection
$conn->close();
