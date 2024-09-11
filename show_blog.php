<?php
include('blog.php');
include "inc/header.php";
// include "config/db.php";
?>
<main class="main">
  <div class="container2">
    <!-- blogs in a table -->
    <div style="text-align: right; margin-top: 20px;">
      <a href="create_blog.php" class="btn1">ADD</a>
    </div>
    <table>
      <thead>
        <tr>
          <th>Category</th>
          <th>Title</th>
          <th>Description</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($blogs as $blog) : ?>
          <tr>
            <td><?= htmlspecialchars($blog['category']); ?></td>
            <td><?= htmlspecialchars($blog['title']); ?></td>
            <td><?= htmlspecialchars($blog['description']); ?></td>
            <td><img src="<?= htmlspecialchars($blog['image_path']); ?>" alt="blog Image" width="100"></td>
            <td>
              <a href="edit_blog.php?id=<?= urlencode($blog['id']); ?>" class="btn1 btn-edit">Edit</a>
              <a href="delete_blog.php?id=<?= urlencode($blog['id']); ?>" class="btn1 btn-delete" style="background-color:darkred; " onclick="return confirm('Are you sure you want to delete this blog?');">Delete</a>
              <!-- You can add more actions like Delete if needed -->
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
<?php
include "inc/footer.php";
?>