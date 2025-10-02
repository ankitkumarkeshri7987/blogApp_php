<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/php/db.php';

include __DIR__ . '/includes/header.php';
?>

<div class="container">

  <h2>Contact Us</h2>

  <p>If you have any questions, feel free to contact us using the form below:</p>

  <form method="post" action="/blogApp/contact.php">

    <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">

    <label>Your Name</label>

    <input type="text" name="name" required>

    <label>Your Email</label>

    <input type="email" name="email" required>

    <label>Message</label>

    <textarea name="message" rows="6" required></textarea>

    <button type="submit">Send Message</button>

  </form>

  <?php

  if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
      if (!verify_csrf($_POST['csrf'] ?? '')) 
        {
          echo "<p>Error: Invalid CSRF token.</p>";
      } 
      else 
        {
          $name = e(trim($_POST['name']));
          $email = e(trim($_POST['email']));
          $message = e(trim($_POST['message']));
          
      try 
      {
        $sql = "INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([
          ':name' => $name,
          ':email' => $email,
          ':message' => $message
        ]);

        if ($success)
          {
          $_SESSION['contact_success'] = "Thank you, $name. We received your message.";
          header("Location: /blogApp/contact.php");
    exit;
        
         } 
        else 
        {
          echo "<p>Sorry, there was a problem saving your message. Please try again later.</p>";
        }
      } 
      catch (PDOException $e)
       {
        echo "<p>Error saving message: " . htmlspecialchars($e->getMessage()) . "</p>";
     
       }  
         
      }
  }
  ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>








