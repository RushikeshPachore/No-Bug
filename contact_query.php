<?php
include('config/db.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  $name = $conn->real_escape_string($_POST['name']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $course = $conn->real_escape_string($_POST['course']);
  $message = $conn->real_escape_string($_POST['message']);

  $query = "INSERT INTO contact_query (name, email, phone, course, message) 
            VALUES ('$name', '$email', '$phone', '$course', '$message')";

  if ($conn->query($query) === TRUE) {
    echo "Contact query submitted successfully!";
  } else {
    echo "Error: " . $conn->error;
  } 
  $conn->close();
} else {
  echo "Form not submitted correctly.";
}
