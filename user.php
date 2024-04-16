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
require_once "createuser.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();

                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_username, $param_password);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: index.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
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
        <h5 class="modal-title" id="exampleModalLabel">User Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
<!-- Create POP UP add user -->
      <div class="modal-body">
      <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create a New User</h2>
                    </div>
                    <p>Please fill this form and submit to add user to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                      <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                          <label>Username</label>
                          <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                          <span class="help-block"><?php echo $username_err; ?></span>
                      </div>
                      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                          <label>Password</label>
                          <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                          <span class="help-block"><?php echo $password_err; ?></span>
                      </div>
                      <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                          <label>Confirm Password</label>
                          <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                          <span class="help-block"><?php echo $confirm_password_err; ?></span>
                      </div>
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>Email</label>
                            <textarea name="email" class="form-control"><?php echo $email; ?></textarea>
                            <span class="help-block"><?php echo $email_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="user.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">Add User</button>
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
                           $sql = "SELECT * FROM users ORDER BY username LIMIT $limit";
                           if($result = $mysqli->query($sql)){
                               if($result->num_rows > 0){
                                   echo "<table class='table table-bordered table-striped'>";
                                       echo "<thead>";
                                           echo "<tr>";
                                               echo "<th>No</th>";
                                               echo "<th>Username</th>";
                                               echo "<th>Email</th>";
                                               echo "<th>Action</th>";
                                           echo "</tr>";
                                       echo "</thead>";
                                       echo "<tbody>";
                                       while($row = $result->fetch_array()){
                                           echo "<tr>";
                                               echo "<td>" . $i . "</td>";
                                               echo "<td>" . $row['username'] . "</td>";
                                               echo "<td>" . $row['email'] . "</td>";
                                               echo "<td>";
                                                   echo "<a href='edit-user.php?id=". $row['id'] ."'> Edit </a>";
                                                   echo "<a href='delete-user.php?id=". $row['id'] ."'> Delete </a>";
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
