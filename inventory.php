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

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Prepare an insert statement
  $sql = "INSERT INTO inventory (product_name, quantity , unit_price , description) VALUES (?, ?  , ? , ?)";

  if($stmt = $mysqli->prepare($sql)){
      // Bind variables to the prepared statement as parameters
     try {
        //code...
        $stmt->bind_param("ssss", $product_name, $quantity , $product_price , $description );
        // Set parameters
        $product_name = $_POST['product_name'];
        $quantity = $_POST['quantity'];
        $product_price = $_POST['product_price'];
        $description = $_POST['description'];
        // Attempt to execute the prepared statement
        if($stmt->execute()){    
            // Redirect to login page
            header("location: inventory.php");
        } else{
            echo "Something went wrong. Please try again later.";
        }
     } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
        header("location: inventory.php");
     }
      // Close statement
      $stmt->close();
  }

    // Close connection
    $mysqli->close();
}
?>
<!-- ******************** THIS IS FOR HEADER ************************ -->



<!-- **************************************************************** -->

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
<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Asset details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
<!-- Create POP UP add user -->
      <div class="modal-body">
      <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create new asset</h2>
                    </div>
                    <p>Please fill this form and submit to add asset to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                      <div class="form-group">
                          <label>Product Name</label>
                          <input type="text" name="product_name" class="form-control">
                         
                      </div>
                      <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                          <label>Quantity</label>
                          <input type="text" name="quantity" class="form-control">
                        
                      </div>
                      <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                          <label>Product Price</label>
                          <input type="text" name="product_price" class="form-control">
                     
                      </div>
                      <div class="form-group">
                          <label>Description</label>
                          <input type="text" name="description" class="form-control">
                         
                      </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="user.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
  </div>
</div>

<!-- ********   THIS IS FOR LISTING THE USERS ************ -->

<div class="content-title">
  <div class="sub-content-title">
    <div class="tajuk">
      <h2>List of Users</h2>
    </div>
    <div class="tambah-user">
      <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">Add Assets</button>
    </div>
  </div>
</div>
<div class="content-table">
  <table id="example" class="table table-striped table-bordered" style="width:100%">
    <?php
                            // Include config file
                           require_once "config.php";

                           // Declare variables
                           $i = 1;
                           $limit = 7;
                           // Attempt select query execution
                           $sql = "SELECT * FROM inventory ORDER BY product_name LIMIT $limit";
                           if($result = $mysqli->query($sql)){
                               if($result->num_rows > 0){
                                   echo "<table class='table table-bordered table-striped'>";
                                       echo "<thead>";
                                           echo "<tr>";
                                               echo "<th>No</th>";
                                               echo "<th>Product Name</th>";
                                               echo "<th>Stok</th>";
                                               echo "<th>Unit Price</th>";
                                               echo "<th>Description</th>";
                                               echo "<th>Action</th>";
                                           echo "</tr>";
                                       echo "</thead>";
                                       echo "<tbody>";
                                       while($row = $result->fetch_array()){
                                           echo "<tr>";
                                               echo "<td>" . $i . "</td>";
                                               echo "<td>" . $row['product_name'] . "</td>";
                                               echo "<td>" . $row['quantity'] . "</td>";
                                               echo "<td>" . $row['unit_price'] . "</td>";
                                               echo "<td>" . $row['description'] . "</td>";
                                               echo "<td>";
                                                   echo "<a href='edit-inventory.php?id=". $row['id'] ."'> Edit </a>";
                                                   echo "<a href='delete-inventory.php?id=". $row['id'] ."'> Delete </a>";
                                               echo "</td>";
                                           echo "</tr>";
                                           $i++;
                                       }
                                       echo "</tbody>";
                                   echo "</table>";
                                   // Free result set
                                   $result->free();
                               } else{
                                   echo "<p class='lead'><em>No records were found.</em></p>";
                               }
                           } else{
                               echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
                           }

                           // Close connection
                           $mysqli->close();
                           ?>
  </table>
  <!-- <nav aria-label="Page navigation example">
  <ul class="pagination justify-content-end">
    <li class="page-item disabled">
      <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
    </li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#">Next</a>
    </li>
  </ul>
</nav> -->
<div class="clearfix">
    <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
    <ul class="pagination justify-content-end">
      <li class="page-item disabled">
        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
      </li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item">
        <a class="page-link" href="#">Next</a>
      </li>
    </ul>
</div


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
