<?php
include "config/db.php";
include "inc/header.php";
?>

<main class="main">
  <div class="container">
    <div class="banner-card">
      <h1>Create a New Blog</h1>
      <form action="blog.php" method="post" enctype="multipart/form-data">
        <label for="category">Category:</label>
        <select id="category" name="category" required>
          <option value="" disabled selected>Select a category</option>
          <option value="Web Development">Web Development</option>
          <option value="Graphic Designing">Graphic Designing</option>
          <option value="Digital Marketing">Digital Marketing</option>
          <option value="Video Editing">Video Editing</option>
        </select>

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="image_path">Image:</label>
        <input type="file" id="image_path" name="image_path" accept="image/*" required>

        <input type="submit" name="submit" value="Create Blog">
      </form>
    </div>
  </div>
</main>

<?php
include "inc/footer.php";
?>