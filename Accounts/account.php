<?php
include('../includes/header.php');
if(isset($_SESSION['userAuth'])&& $_SESSION['userAuth']!=""){
    include('../includes/sidebar.php');
?>

<main class="mt-3 pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h4><i class="bi bi-cash-coin"></i> Manage Account</h4>
                </div>

                <?php
                // Database connection using PDO
                require_once('../config/dbcon.php');
                
                ?>
                <!-- Add Account Modal Button -->
                <div class="mt-4 px-4">
                    <button type="button" class="btn btn-light float-end" data-bs-toggle="modal" data-bs-target="#accountModal">
                        <i class="bi bi-person-fill-add"></i>
                        Add Account
                    </button>
                </div>
                </div>

                <!-- Account Table -->
                 <?php
                 try{
                    $query = "SELECT * FROM account_tbl inner join user_tbl on account_tbl.u_id=user_tbl.u_id ORDER BY acc_id";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                 }catch(PDOException $e){
                    echo "Error fetching records:" .$e->getMessage();
                    $result=[];
                 }?>

                 <div class="mt-3">
                    <table id="example" class="table table-striped data-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>A_ID</th>
                                <th>Bank/Cash</th>
                                <th>Name</th>
                                <th>Acc_Holder</th>
                                <th>Acc_No</th>
                                <th>Balance</th>
                                <th>Acc_Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($result as $row2){?>
                                <tr>
                                    <td><?php echo $row2['acc_id'];?></td>
                                    <td><?php echo $row2['a_type'];?></td>
                                    <td><?php echo $row2['ab_name'];?></td>
                                    <td><?php echo $row2['u_name'];?></td>
                                    <td><?php echo $row2['acc_num'];?></td>
                                    <td><?php echo $row2['acc_ammo'];?></td>
                                    <td><?php echo $row2['acc_type'];?></td>
                                    <td>
                                        <a href="toggle_acc.php?id=<?php echo $row2['acc_id']; ?>"<button type="button" class="btn btn-primary btn-sm" data-toggle="button" aria-pressed="false" autocomplete="off">
                                        </button>
                                            <?php echo ($row2['acc_status'] == 'active') ? 'Inactive' : 'Activate'; ?>
                                        </a>
                                    </td>
                                    <td>   
                    <button class="btn btn-info btn-sm viewAcc" data-id="<?php echo $row2['acc_id']; ?>" data-bs-toggle="modal" data-bs-target="#viewAccModal"><i class="bi bi-receipt"></i></button>
                   
                     <button class="btn btn-warning btn-sm editAcc" data-id="<?php echo $row2['acc_id']; ?>" data-bs-toggle="modal" data-bs-target="#editAccModal"><i class="bi bi-tools"></i></button>
                    </td>

                                </tr>
                                <?php }?>
                        </tbody>
                        </table>
                 </div>
    </div>
</main>
<?php
$query1 = "SELECT * FROM user_tbl";
                $stmt = $pdo->prepare($query1);
                $row1 = $stmt->execute();
?>
<!-- Add Account Modal -->
<div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="accountModalLabel">Create Account</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="accountForm">
              <!-- Name -->
              <div class="mb-3">
                <label for="u_id">Account Holder's Name</label>
                <select class="form-control" id="u_id" name="u_id" required>
                  <?php
                  while($row1 = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      echo '<option value="'.$row1['u_id'].'">'.$row1['u_name'].'</option>';
                  }
                  ?>
                </select>  
              </div>

              <!-- Bank / Cash Selection -->
              <div class="mb-3">
                <label class="form-label">Payment Method</label><br />
                <input type="radio" name="a_type" value="Bank" id="Bank" class="toggle-fields" />
                <label for="Bank">Bank</label>
                <input type="radio" name="a_type" value="Cash" id="Cash" class="toggle-fields" checked />
                <label for="Cash">Cash</label>
              </div>

              <!-- Bank Name -->
              <div class="mb-3 hidden" id="ab_nameField">
                <label for="ab_name" class="form-label">Bank Name</label>
                <input type="text" class="form-control" id="ab_name" name="ab_name" />
              </div>

              <!-- Amount -->
              <div class="mb-3">
                <label for="acc_ammo" class="form-label">Amount</label>
                <input type="number" class="form-control" id="acc_ammo" name="acc_ammo" required />
              </div>

              <!-- Account Number -->
              <div class="mb-3 hidden" id="acc_numField">
                <label for="acc_num" class="form-label">Account Number</label>
                <input type="text" class="form-control" id="acc_num" name="acc_num" />
              </div>

              <!-- Account Type -->
              <div class="mb-3 hidden" id="acc_typeField">
                <label for="acc_type" class="form-label">Account Type</label>
                <select class="form-control" id="acc_type" name="acc_type">
                  <option value="savings">Savings</option>
                  <option value="current">Current</option>
                </select>
              </div>
              <input type="hidden" name="submit" value="submit">
              <input type="hidden" name="fromAccount" value="fromAccount">
              <div class="modal-footer">
                <a href="account.php">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </a>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


<!-- View Account Modal -->
    <div class="modal fade" id="viewAccModal" tabindex="-1" aria-labelledby="viewAccModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAccModalLabel">Account Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="accountDetails">
                 <!-- Account details will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>
<?php
$query3 = "SELECT * FROM user_tbl";
                $stmt = $pdo->prepare($query1);
                $row3 = $stmt->execute();
?>
<!-- Edit Account Modal -->
 <div class="modal fade" id="editAccModal" tabindex="-1" aria-labelledby="editAccModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccModalLabel">Edit Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAccForm">
                    <input type="hidden" id="edit_acc_id" name="acc_id">
                    <div class="mb-3">
                        <label for="edit_u_id">Account Holder's Name</label>
                <select class="form-control" id="edit_u_id" name="u_id" required>
                  <option value = "<?php echo $row2['u_id']?>"><?php echo $row2['u_name']?> </option>
                  <?php
                  while($row3 = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      echo '<option value="'.$row3['u_id'].'">'.$row3['u_name'].'</option>';
                  }
                  ?>
                </select>  
                    </div>
                    <div class="mb-3">
                        <label for="edit_acc_num" class="form-label">Account Number</label>
                        <input type="text" class="form-control" id="edit_acc_num" name="acc_num" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_ab_name" class="form-label">Account Name</label>
                        <input type="text" class="form-control" id="edit_ab_name" name="ab_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_acc_ammo" class="form-label">Amount</label>
                        <input type="text" class="form-control" id="edit_acc_ammo" name="acc_ammo" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_acc_type" class="form-label">Account Type</label>
                        <select class="form-control" id="edit_acc_type" name="acc_type">
                             <option value="savings">Savings</option>
                             <option value="current">Current</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php

include('../includes/footer.php');
}
?>