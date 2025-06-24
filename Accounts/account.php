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
                                    <td><?php echo htmlspecialchars( $row2['a_type']);?></td>
                                    <td><?php echo htmlspecialchars( $row2['ab_name']);?></td>
                                    <td><?php echo htmlspecialchars( $row2['u_name']);?></td>
                                    <td><?php echo htmlspecialchars( $row2['acc_num']);?></td>
                                    <td><?php echo htmlspecialchars( $row2['acc_ammo']);?></td>
                                    <td><?php echo htmlspecialchars( $row2['acc_type']);?></td>
                                    <td>
                                       
                                      <a href="toggle_acc.php?id=<?php echo htmlspecialchars($row2['acc_id']); ?>" class="btn btn-sm <?php echo ($row2['acc_status'] == 'active') ? 'btn-success' : 'btn-secondary'; ?>">
                                    <?php echo ($row2['acc_status'] == 'active') ? 'Active' : 'Inactive'; ?>
                                </a>   
                                    </td>
                                    <td>   
                    <button class="btn btn-info btn-sm viewAcc"
                     data-id="<?php echo $row2['acc_id']; ?>"
                     data-atype="<?php echo $row2['a_type']; ?>"
                     data-name="<?php echo $row2['ab_name']; ?>"
                     data-uid="<?php echo $row2['u_id']; ?>"
                     data-num="<?php echo $row2['acc_num']; ?>"
                     data-ammo="<?php echo $row2['acc_ammo']; ?>"
                     data-type="<?php echo $row2['acc_type']; ?>"
                     
                     data-bs-toggle="modal" data-bs-target="#viewAccModal"><i class="bi bi-receipt"></i></button>
                   
                     <button class="btn btn-warning btn-sm editAcc"
                      data-id="<?php echo $row2['acc_id']; ?>"
                      data-atype="<?php echo $row2['a_type']; ?>"
                     data-name="<?php echo $row2['ab_name']; ?>"
                     data-uid="<?php echo $row2['u_id']; ?>"
                     data-num="<?php echo $row2['acc_num']; ?>"
                     data-ammo="<?php echo $row2['acc_ammo']; ?>"
                     data-type="<?php echo $row2['acc_type']; ?>"
                      data-bs-toggle="modal" data-bs-target="#editAccModal"><i class="bi bi-tools"></i></button>
                     
                     <button class="btn btn-danger btn-sm deleteAcc" data-id="<?php echo $row2['acc_id'];?>" data-bs-toggle="modal" data-bs-target="#deleteAccModal"><i class="bi bi-trash"></i></button>
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
              <!-- Account Holder's Name -->
              <div class="mb-3">
                <label for="u_id">Account Holder's Name</label>
                <select class="form-control" id="u_id" name="u_id" required>
                      <option value="" disabled selected>Account Holder's Name</option>
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
                <input type="text" class="form-control" id="ab_name" name="ab_name" placeholder="Bank Name" />
              </div>

              <!-- Amount -->
              <div class="mb-3">
                <label for="acc_ammo" class="form-label">Amount</label>
                <input type="number" class="form-control" id="acc_ammo" name="acc_ammo" placeholder="Amount" required />
              </div>

              <!-- Account Number -->
              <div class="mb-3 hidden" id="acc_numField">
                <label for="acc_num" class="form-label">Account Number</label>
                <input type="text" class="form-control" id="acc_num" name="acc_num" placeholder="Amount Number" />
              </div>

              <!-- Account Type -->
             <div class="mb-3 hidden" id="acc_typeField">
  <label for="acc_type" class="form-label">Account Type</label>
  <select class="form-control" id="acc_type" name="acc_type">
    <option value="" disabled selected>Select Account Type</option>
    <option value="savings">Savings</option>
    <option value="current">Current</option>
    <option value="other">Other</option>
  </select>
</div>

<div class="mb-3 hidden" id="customAccTypeField">
  <label for="acc_type" class="form-label">Custom Account Type</label>
  <input type="text" class="form-control" id="acc_type" name="acc_type" placeholder="Enter custom account type">
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
                <h5 class="modal-title" id="viewAccModalLabel">view Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body">
                    <input type="hidden" id="view_acc_id" name="acc_id">
                    <div class="mb-3 row">
                        <label for="view_u_id" class="col-sm-2 col-form-label fw-bold">Account Holder's Name:</label>
                        <div class="col-sm-4">
                 <p class="form-control-plaintext" id="view_u_id" name="u_id" >
                  <option value = "<?php echo $row2['u_id']?>"><?php echo $row2['u_name']?> </option>
                 
                </p></div>
                    </div>
                    <div class="mb-3 row">
                        <label for="view_acc_num" class="col-sm-2 col-form-label fw-bold">Account Number:</label>
                        <div class="col-sm-4">
                        <p class="form-control-plaintext" class="form-control" id="view_acc_num" name="acc_num"></p></div>
                    </div>
                    <div class="mb-3 row">
                        <label for="view_ab_name" class="col-sm-2 col-form-label fw-bold">Account Name:</label>
                        <div class="col-sm-4">
                        <p class="form-control-plaintext" class="form-control" id="view_ab_name" name="ab_name"></p></div>
                    </div>
                    <div class="mb-3 row">
                        <label for="view_acc_ammo" class="col-sm-2 col-form-label fw-bold">Amount:</label>
                        <div class="col-sm-4">
                        <p class="form-control-plaintext" class="form-control" id="view_acc_ammo" name="acc_ammo"></p></div>
                    </div>
                    <div class="mb-3 row">
                        <label for="view_acc_type" class="col-sm-2 col-form-label fw-bold">Account Type:</label>
                        <div class="col-sm-4">
                        <p class="form-control-plaintext" class="form-control" id="view_acc_type" name="acc_type">
                            </p></div>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>                   
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
      <form action="update_account.php" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccModalLabel">Edit Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
            </div>
            <div class="modal-body">
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
                        <input type="text" class="form-control" id="edit_acc_num" name="acc_num" >
                    </div>
                    <div class="mb-3">
                        <label for="edit_ab_name" class="form-label">Bank Name</label>
                        <input type="text" class="form-control" id="edit_ab_name" name="ab_name" >
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
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Account</button>
                    </div>
                    </div>
                    
                </form>
    </div>
</div>

<!-- Delete Account Modal -->

<div class="modal fade" id="deleteAccModal" tabindex="-1" aria-labelledby="deleteAccModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title" id="deleteAccModalLabel">Delete Account</h1>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this Account?
        <input type="hidden" id="deleteACCId">
      </div>
      <div class="modal-footer">
        <button type="button" id="confirmDeleteAcc" class="btn btn-danger">Delete</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>



<?php
include('../includes/footer.php'); ?>

<script>

  document.getElementById('acc_type').addEventListener('change', function () {
    const customField = document.getElementById('customAccTypeField');
    if (this.value === 'other') {
      customField.classList.remove('hidden');
    } else {
      customField.classList.add('hidden');
    }
  });

  document.addEventListener('DOMContentLoaded', function(){
    const editButtons = document.querySelectorAll('.editAcc');
    editButtons.forEach(button =>{
      button.addEventListener('click',()=>{

        document.getElementById('edit_acc_id').value=button.getAttribute('data-id');

        const abname = button.getAttribute('data-name');
        document.getElementById('edit_ab_name').value=abname;
        
        const uid = button.getAttribute('data-uid');
        document.getElementById('edit_u_id').value=uid;
        
        const num = button.getAttribute('data-num');
        document.getElementById('edit_acc_num').value=num;
        
        const ammo = button.getAttribute('data-ammo');
        document.getElementById('edit_acc_ammo').value=ammo;
        
        const type = button.getAttribute('data-type');
        document.getElementById('edit_acc_type').value=type;

      });
    });
  });
  document.addEventListener('DOMContentLoaded', function(){
    const viewButtons = document.querySelectorAll('.viewAcc');
    viewButtons.forEach(button =>{
      button.addEventListener('click',()=>{

        document.getElementById('view_acc_id').value=button.getAttribute('data-id');

        const abname = button.getAttribute('data-name');
        document.getElementById('view_ab_name').textContent=abname;
        
        const uid = button.getAttribute('data-uid');
        document.getElementById('view_u_id').textContent=uid;
        
        const num = button.getAttribute('data-num');
        document.getElementById('view_acc_num').textContent=num;
        
        const ammo = button.getAttribute('data-ammo');
        document.getElementById('view_acc_ammo').textContent=ammo;
        
        const type = button.getAttribute('data-type');
        document.getElementById('view_acc_type').textContent=type;

      });
    });
  });


</script>

<?php
  }else{

echo '<script>
alert("Not Authorised!");
window.location.href = "../index.php";
</script>';

 }    
?>