<?php
require_once "config.php";

$id = isset($_GET['id']) ? $_GET['id'] : null;  // Handle missing ID gracefully

// Function to edit user data
function editInventory($id, $mysqli) {
  if (!$id) {
    return false;  // Return false if no ID provided
  }

  // Validate form data (optional but recommended)
  // ... (implement validation logic here)

  $product_name = mysqli_real_escape_string($mysqli, trim($_POST['product_name']));
  $unit_price = mysqli_real_escape_string($mysqli, trim($_POST['unit_price']));
  $description = mysqli_real_escape_string($mysqli, trim($_POST['description']));  // Hash before storing
  $qty = mysqli_real_escape_string($mysqli, trim($_POST['qty']));  // Hash before storing

  // Hash password before storing (if not already hashed)
  // ... (implement password hashing logic here)

  try {
    //code...

    $sql = "UPDATE inventory SET product_name = '$product_name', unit_price = '$unit_price', description = '$description' , quantity = '$qty' WHERE id = $id";
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
  try {
    //code...
    $editSuccess = editInventory($id, $mysqli);

  // Handle edit success/failure (optional)
  if ($editSuccess) {
    header("Location: inventory.php");  // Redirect to user.php on success   
    exit();
  }else{
    
  }
  } catch (\Throwable $th) {
    //throw $th;
    echo $th->getMessage();
  }
}

// Retrieve user data if ID is provided
if ($id) {
  $result = $mysqli->query("SELECT * FROM inventory WHERE id = '$id'");

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
      <label for="">Product Name</label>
      <input type="text" class="form-control" name="product_name" value="<?= isset($data['product_name']) ? $data['product_name'] : '' ?>" required>
      <label for="">Stok</label>
      <input type="number" class="form-control" name="qty" value="<?= isset($data['quantity']) ? $data['quantity'] : '0' ?>" required>
      <label for="">Unit Price</label>
      <input type="number" placeholder="Enter Unit Price" name="unit_price" value="<?= isset($data['unit_price']) ? $data['unit_price'] : '0' ?>"  required class="form-control">
      <label for="">Description</label>
      <input type="text" placeholder="Enter new Description" name="description" value="<?= isset($data['description']) ? $data['description'] : '' ?>"  required class="form-control">
      <button class="btn btn-primary mt-3">Simpan</button>
    </form>
  <?php endif; ?>
</div>



<?php include 'footer.php'; ?>
