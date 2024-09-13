<?php
include('config/db.php');
// Check if the form was submitted "&& isset($_POST['submit']")
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
  if (isset($_FILES["image_path"]) && $_FILES["image_path"]["error"] == 0) {
    //file details
    $filename = $_FILES["image_path"]["name"];
    $filetmp = $_FILES["image_path"]["tmp_name"];
    $filesize = $_FILES["image_path"]["size"];

    $upload_dir = 'uploads/';

    if (!file_exists($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }

    $unique_id = uniqid(); // Generate a unique ID
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION); // Get file extension
    $unique_filename = $unique_id . '.' . $file_ext; // Append unique ID to the file extension
    $upload_file = $upload_dir . $unique_filename; // Use the unique filename

    // Move the uploaded file to the destination directory
    // $upload_file = $upload_dir . basename($filename);

    if (move_uploaded_file($filetmp, $upload_file)) {
      // Get form data
      $category = $conn->real_escape_string($_POST['category']);
      $title = $conn->real_escape_string($_POST['title']);
      $description = $conn->real_escape_string($_POST['description']);
      $image_path = $conn->real_escape_string($unique_filename);

      // SQL query to insert data into banners table
      $query = "INSERT INTO blogs (category,title,description,image_path)
          VALUES ('$category', '$title', '$description', '$image_path')";

      // Execute the query
      if (
        $conn->query($query) === TRUE
      ) {
        header("Location: show_blog.php");
        exit;
      } else {
        echo "Error: " . $conn->error;
      }
    } else {
      echo "File upload failed";
    }
  } else {
    echo "Image upload error";
  }
}




function getAllBlogs($conn)
{
  $sql = "SELECT * FROM blogs";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    return $result->fetch_all(MYSQLI_ASSOC);
  } else {
    return [];
  }
}

$blogs = getAllBlogs($conn);
$conn->close();
