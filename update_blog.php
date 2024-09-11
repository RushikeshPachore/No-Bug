<?php
include "config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category = $_POST['category'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $blog_id = $_POST['blog_id'];

  // Initialize variables for the SQL query
  $image_path = '';

  // Check if a new image is uploaded
  if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === 0) {
    $target_dir = "uploads/"; // Directory to store the uploaded files
    $target_file = $target_dir . basename($_FILES['image_path']['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate file type (e.g., allow only certain file types)
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowed_types)) {
      // Move uploaded file to the target directory
      if (move_uploaded_file($_FILES['image_path']['tmp_name'], $target_file)) {
        $image_path = $target_file;
      } else {
        echo "Error uploading the image.";
        exit;
      }
    } else {
      echo "Invalid file type.";
      exit;
    }
  }

  // Prepare the SQL query
  if ($image_path) {
    // If a new image is uploaded, update the image path as well
    $query = "UPDATE blogs SET category = ?, title = ?, description = ? image_path = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi",  $category, $title, $description, $blog_id, $image_path);
  } else {
    // If no new image is uploaded, only update the text fields
    $query = "UPDATE blogs SET category = ?, title = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $category, $title, $description, $blog_id);
  }


  if ($stmt->execute()) {
    echo "Blog updated successfully.";
    // Redirect to a success page or back to the banner list
    header("Location: show_blog.php");
    exit;
  } else {
    echo "Error updating blog: " . $stmt->error;
  }

  // Close the statement
  $stmt->close();
}

// Close the connection
$conn->close();
