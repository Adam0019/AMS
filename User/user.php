<?php
include('../includes/header.php');
if(isset($_SESSION['userAuth'])&& $_SESSION['userAuth']!="")

{
    
    include('../includes/sidebar.php');
?>
<main class="mt-3 pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h4><i class="bi bi-person-square"></i> User Profile</h4>
            </div>
        

        <?php
        // Database connection using PDO
        require_once('../config/dbcon.php');
        ?>

        <!-- Add User modal button -->
         <div class="mt-4 px-4">
            <button type="button" class="btn btn-light float-end" data-bs-toggle="modal" data-bs-target="#userModal">
                <i class="bi bi-person-fill-add"></i>
                ADD User
            </button>
         </div>
         
    </div>
    
    <!-- User Table -->
     <?php 
     try{
        $querry = "SELECT * FROM user_tbl ORDER BY u_id";
        $stmt = $pdo->prepare($querry);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
     }catch (PDOException $e){
        echo "Error fetching records:" .$e->getMessage();
        $result=[];
     }
     ?>

    <div class="mt-3">
        <table id="example" class="table table-striped data-table" style="width:100%">
            <thead>
                <tr>
                    <th>U_ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($result as $row){?>
                <tr>
                    <td><?php echo $row['u_id'];?></td>
                    <td><?php echo $row['u_name'];?></td>
                    <td><?php echo $row['u_email'];?></td>
                    <td><?php echo $row['u_phone'];?></td>
                    <td><?php echo $row['username'];?></td>
                    <td><?php echo $row['role'];?></td>
                    <td>
                       <a href="toggle_user.php?id=<?php echo $row['u_id']; ?>"<button type="button" class="btn btn-primary btn-sm" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        </button>
                                            <?php echo ($row['u_status'] == 'active') ? 'Inactive' : 'Activate'; ?>
                                        </a>
                    </td>
                    <td>   
                    <button class="btn btn-info btn-sm viewUser" data-id="<?php echo $row['u_id']; ?>" data-bs-toggle="modal" data-bs-target="#viewUserModal"><i class="bi bi-receipt"></i></button>
                   
                     <button class="btn btn-warning btn-sm editUser" data-id="<?php echo $row['u_id']; ?>" data-bs-toggle="modal" data-bs-target="#editUserModal"><i class="bi bi-tools"></i></button>

                     <button class="btn btn-danger btn-sm deleteUser" data-id="<?php echo $row['u_id'];?>" data-bs-toggle="modal" data-bs-target="#deleteUserModal"><i class="bi bi-trash"></i></button>
                    </td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    </div>


</main>

<!-- Add User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="userModalLabel">Create User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="userForm">
             <div class="form-group">
                            <label for="u_name">Name</label>
                            <input type="text" class="form-control" id="u_name" name="u_name" placeholder="Name" required>
                        </div>
                        <div class="form-group mt-4">
                            <label for="u_email">Email</label>
                            <input type="email" class="form-control" id="u_email" name="u_email" placeholder="Email" required>
                        </div>
                        <div class="form-group mt-4">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"  data-type="password" placeholder="Password" required>
                        </div>
                        <div class="form-group mt-4">
                            <label for="u_phone">Phone</label>
                            <input type="text" class="form-control" id="u_phone" name="u_phone" placeholder="Phone" required>
                        </div>
                        <div class="form-group mt-4">
                            <label for="role">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="User"selected>User</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div> 
        </div>
        <input type="hidden" name="submit" value="submit">
        <input type="hidden" name="fromUser" value="fromUser">
        <div class="modal-footer">
            <a href="user.php">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
    </div>
  </div>
</div>

<!-- View User Modal -->

<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userDetails">
                 <!-- User details will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>




<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="edit_u_id" name="u_id">
                    <div class="mb-3">
                        <label for="edit_u_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_u_name" name="u_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_u_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_u_email" name="u_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_u_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="edit_u_phone" name="u_phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="edit_password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-control" id="edit_role" name="role">
                            <option value="User" selected>User</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title" id="deleteUserModalLabel">Delete User</h1>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this User?
        <input type="hidden" id="deleteUserId">
      </div>
      <div class="modal-footer">
        <button type="button" id="confirmDeleteUser" class="btn btn-danger">Delete</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


<?php
include('../includes/footer.php');
}
else{
   echo '<script>
alert("Not Authorised!");
window.location.href = "../index.php";
</script>';
}