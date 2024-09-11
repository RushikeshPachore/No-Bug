<?php
include "config/db.php";
include('inc/header.php');
?>


<main class="main">
  <div class="containerform">
    <h1>Contact Us</h1>
    <form action="contact_query.php" method="post">
      <div class="form-group1">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>
      </div>
      <div class="form-group1">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group1">
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" required>
      </div>
      <div class="form-group1">
        <label for="course">Course:</label>
        <select id="course" name="course" required>
          <option value="" disabled selected>Select a course</option>
          <option value="Web Development">Web Development</option>
          <option value="Graphic Designing">Graphic Designing</option>
          <option value="Digital Marketing">Digital Marketing</option>
          <option value="Video Editing">Video Editing</option>
        </select>
      </div>
      <div class="form-group1">
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="2" required></textarea>
      </div>
      <button type="submit" class="btn-submit1">Submit</button>
    </form>
  </div>
</main>

<?php include('inc/footer.php');
?>