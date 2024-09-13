<?php
include 'config/db.php';

// && isset($_POST['submit'])
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {

  if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

    $filename = $_FILES["image"]["name"];
    $filetmp = $_FILES["image"]["tmp_name"];
    $filesize = $_FILES["image"]["size"];
    $upload_dir = 'courses/';

    if (!file_exists($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }

    $unique_id = uniqid();
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
    $unique_filename = $unique_id . '.' . $file_ext;  // Create a unique file name
    $upload_file = $upload_dir . $unique_filename;
    // Validate image file
    if (move_uploaded_file($filetmp, $upload_file)) {
      $title = $conn->real_escape_string($_POST['title']);
      $description = $conn->real_escape_string($_POST['description']);
      $duration = $conn->real_escape_string($_POST['duration']);
      // Prepare SQL query
      $stmt = $conn->prepare("INSERT INTO course (image, title, description, duration) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $unique_filename, $title, $description, $duration);

      // Execute SQL query and check for errors
      if ($stmt->execute()) {
        echo "New course added successfully!";
      } else {
        echo "Error: " . $stmt->error;
      }
      $stmt->close();
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  } else {
    echo "Error: No valid image file uploaded.";
  }
} else {
  echo "Error: Form not submitted correctly.";
}

// Close the database connection
$conn->close();
