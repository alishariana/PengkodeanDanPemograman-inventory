<?php
require_once "config.php";

$id = isset($_GET['id']) ? $_GET['id'] : null;  // Handle missing ID gracefully

// Function to edit user data
function editUser($id, $mysqli) {
  if (!$id) {
    return false;  // Return false if no ID provided
  }

  // Validate form data (optional but recommended)
  // ... (implement validation logic here)

  $username = mysqli_real_escape_string($mysqli, trim($_POST['username']));
  $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
  $password = mysqli_real_escape_string($mysqli, trim($_POST['password']));  // Hash before storing

  // Hash password before storing (if not already hashed)
  // ... (implement password hashing logic here)

  try {
    //code...
    $hashedPassword = password_hash($password , PASSWORD_DEFAULT);
    $sql = "UPDATE users SET username = '$username', email = '$email', password = '$hashedPassword' WHERE id = $id";
    $result = $mysqli->query($sql);
  
    if ($result) {
      echo "User data updated successfully!";
      return true;  // Return true on success for potential redirect
    } else {
      echo "Error updating user data: " . $mysqli->error;
      return false;  // Return false on failure
    }
  } catch (\Throwable $th) {
    //throw $th;
    echo $th->getMessage();
  }
}

// Check if form is submitted and process data if so
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $editSuccess = editUser($id, $mysqli);

  // Handle edit success/failure (optional)
  if ($editSuccess) {
    header("Location: user.php");  // Redirect to user.php on success   
    exit();
  }else{

  }
}

// Retrieve user data if ID is provided
if ($id) {
  $result = $mysqli->query("SELECT * FROM users WHERE id = '$id'");

  if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
  } else {
    echo "Pengguna dengan ID tersebut tidak ditemukan.";
  }
} else {
  echo "ID pengguna tidak ditemukan.";
}

?>

<?php include 'header.php'; ?>



<div class="sidebar">
  <a class="active" href="dashboard.php">Dashboard</a>
  <a href="user.php">User</a>
  <a href="#about">Inventory</a>
  <a href="#about">Vendor List</a>
  <a href="#about">Request</a>
  <a href="#about">System Log</a>
  <a href="#about">Setting</a>
</div>

<div class="container" style="margin-left: 200px;">
  <?php if ($id): ?>  <form action="" method="post" class="form pt-2">
      <label for="">Username</label>
      <input type="text" class="form-control" name="username" value="<?= isset($data['username']) ? $data['username'] : '' ?>" required>
      <label for="">Email</label>
      <input type="email" class="form-control" name="email" value="<?= isset($data['email']) ? $data['email'] : '' ?>" required>
      <label for="">Password</label>
      <input type="password" placeholder="Enter new password" name="password" required class="form-control">
      <button class="btn btn-primary mt-3">Simpan</button>
    </form>
  <?php endif; ?>
</div>



<?php include 'footer.php'; ?>
