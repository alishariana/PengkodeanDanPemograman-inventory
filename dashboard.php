<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
?>
<!-- ******************** THIS IS FOR HEADER ************************ -->

<?php include 'header.php'; ?>

<!-- **************************************************************** -->


<!-- ******************** THIS IS FOR SIDEBAR ************************ -->


<div class="sidebar">
  <a class="active" href="dashboard.php">Dashboard</a>
  <a href="user.php">User</a>
  <a href="inventory.php">Inventory</a>
  <a href="logout.php">Logout</a>
</div>



<!-- **************************************************************** -->


<!-- ******************** THIS IS FOR MAIN CONTENT ************************ -->

<!-- Modal for POP UP user  -->

<!-- ******************** END OF MAIN CONTENT **************************** -->

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>

