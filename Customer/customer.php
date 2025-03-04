<?php
include('../includes/header.php');
if (isset($_SESSION['userAuth']) && $_SESSION['userAuth']!="" )
{
    include('../includes/sidebar.php');
    
    ?>


<main class="mt-3 pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <h4><i class="bi bi-person-vcard"></i> Customer</h4>
                </div>

            <?php
            // Database connection using PDO
            require_once('../config/dbcon.php');
            ?>



                <!-- Add customer modal button -->
                <div class="mt-4 px-4">
                    <button type="button" class="btn btn-light float-end " data-bs-toggle="modal" data-bs-target="#customerModal">
                        <i class="bi bi-person-fill-add"></i>
                       ADD Customer
                    </button> 
                    
                </div>
                

            </div>

            <?php
            // Fetch all Customer from the database
            try{
          
            $querry = " SELECT * FROM customer_tbl ORDER BY c_id";
            $stmt = $pdo->prepare($querry);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                echo "Error fetching records:" .$e->getMessage();
                $result=[];
            }
            ?>

            <div class="mt-3">
                <table id="example" class="table table-striped data-table"style="width:100%">
                    <thead>
                        <tr>
                            <th>C_ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach($result as $row){
                        ?>
                        <tr>
                            <td ><?php echo htmlspecialchars($row['c_id']);?></td>
                            <td><?php echo htmlspecialchars($row['c_name']);?></td>
                            <td><?php echo htmlspecialchars($row['c_email']);?></td>
                            <td><?php echo htmlspecialchars($row['c_phone']);?></td>
                            <td><?php echo htmlspecialchars($row['c_role']);?></td>
                            <td>
                                <a href="toggle_customer_status.php?id=<?php echo $row['c_id']; ?>"<button type="button" class="btn btn-primary btn-sm" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        </button>
                                            <?php echo ($row['c_status'] == 'active') ? 'Inactive' : 'Activate'; ?>
                                        </a>
                            </td>
                            <td>
                            <button class="btn btn-info btn-sm viewCustomer" data-id="<?php echo $row['c_id']; ?>" data-bs-toggle="modal" data-bs-target="#viewCustomerModal"><i class="bi bi-receipt"></i></button>
                        
                            <button class="btn btn-warning btn-sm editCustomer" data-id="<?php echo $row['c_id']; ?>" data-bs-toggle="modal" data-bs-target="#editCustomerModal"><i class="bi bi-tools"></i></button>
                        </td>
                            
                        </tr>
                        <?php
                        }?>
                    </tbody>
                </table>
                        
            </div>
        </div>
        
</main>

<!--Add Customer Modal -->

<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="customerModalLabel">Create Customer</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="customerForm" method="POST" action="./store_customer.php">
             <div class="form-group">
                            <label for="c_name">Name</label>
                            <input type="text" class="form-control" id="c_name" name="c_name" placeholder="Name" required>
                        </div>
                        <div class="form-group mt-4">
                            <label for="c_email">Email</label>
                            <input type="email" class="form-control" id="c_email" name="c_email" placeholder="Email" required>
                        </div>
                        <div class="form-group mt-4">
                            <label for="c_phone">Phone</label>
                            <input type="text" class="form-control" id="c_phone" name="c_phone" placeholder="Phone" required>
                        </div>
                        <div class="form-group mt-4">
                            <label for="c_address">Address</label>
                            <input type="text" class="form-control" id="c_address" name="c_address" placeholder="Address" required>
                        </div>
                        <div class="form-group mt-4">
                            <label for="c_role">Type</label>
                            <select class="form-select" id="c_role" name="c_role" required>
                                <option value="Buyer" selected>Buyer</option>
                                <option value="Seller">Seller</option>
                            </select>
                        </div>
        </div>
        <input type="hidden" name="submit" value="submit">
        <div class="modal-footer">
            <a href="customer.php"></a>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
    </div>
  </div>
</div>


<!-- Customer Details Modal -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1" aria-labelledby="viewCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCustomerModalLabel">Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="customerDetails">
                <!-- Customer details will be loaded here dynamically -->
                 
            </div>
        </div>
    </div>
</div>


<!-- Edit Customer Modal -->
<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCustomerForm">
                    <input type="hidden" id="edit_c_id" name="c_id">
                    <div class="mb-3">
                        <label for="edit_c_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_c_name" name="c_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_c_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_c_email" name="c_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_c_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="edit_c_phone" name="c_phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_c_role" class="form-label">Role</label>
                        <select class="form-control" id="edit_c_role" name="c_role">
                            <option value="Buyer">Buyer</option>
                            <option value="Seller">Seller</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>




<?php
include('../includes/footer.php');
  }else{

echo '<script>
alert("Not Authorised!");
window.location.href = "../index.php";
</script>';

 }    
?>
