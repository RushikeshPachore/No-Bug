<?php
include "config/db.php";
include "inc/header.php";


// Get the blog ID from the URL

$blog_id = $_GET['id'] ?? null;
if ($blog_id) {
  // Fetch the banner details from the database using the banner ID
  $query = "SELECT * FROM blogs WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $blog_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $blog = $result->fetch_assoc();
}
?>

<main class="main">
  <div class="container">
    <div class="banner-card">
      <h1>Update Blog</h1>
      <form action="update_blog.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="blog_id" value="<?= $blog['id']; ?>">

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" value="<?= $blog['category']; ?>" required>

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?= $blog['title']; ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= $blog['description']; ?></textarea>

        <label for="image_path">Image:</label>
        <input type="file" id="image_path" name="image_path" accept="image/*">

        <button type="submit" name="submit">Update Blog</button>
      </form>
    </div>
  </div>
</main>

<?php
include "inc/footer.php";
?>