<?php
require_once "config.php";

$id = isset($_GET['id']) ? $_GET['id'] : null;  // Handle missing ID gracefully

// Function to delete user (optional for modularity)
function deleteUser($id, $mysqli) {
  if (!$id) {
    return false;  // Return false if no ID provided
  }

  $sql = "DELETE FROM users WHERE id = $id";
  $result = $mysqli->query($sql);

  if ($result) {
    echo "<script>alert('User deleted successfully!');</script>";
    return true;  // Return true on success for potential redirect
  } else {
    echo "Error deleting user: " . $mysqli->error;
    return false;  // Return false on failure
  }
}

// Check if a delete request is made (optional, can use a form)
if (isset($_GET['id']) && $id) {  // Check for 'delete' parameter and ID
  $deleteSuccess = deleteUser($id, $mysqli);

  // Handle deletion success/failure (optional)
  if ($deleteSuccess) {
    header("Location: user.php");  // Redirect to user.php on success
    exit();  // Exit script after redirect
  }
}


?>