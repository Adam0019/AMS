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
                 // Database connection using PDO
                require_once('../config/dbcon.php');
                 try {
                 $query = "SELECT * FROM account_tbl inner join user_tbl on account_tbl.u_id=user_tbl.u_id ORDER BY acc_id";
                 $stmt = $pdo->prepare($query);
                 $stmt->execute();
                 $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
              } catch(PDOException $e) {
                  error_log("Database error: " . $e->getMessage());
                 $result = [];
                 // Display user-friendly message
                 echo "<div class='alert alert-danger'>Unable to load accounts. Please try              again later.</div>";

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
                                    <td><?php echo htmlspecialchars ($row2['acc_id']);?></td>
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
                        data-id="<?php echo htmlspecialchars($row2['acc_id']); ?>"
                        data-atype="<?php echo htmlspecialchars($row2['a_type']); ?>"
                        data-name="<?php echo htmlspecialchars($row2['ab_name']); ?>"
                        data-uid="<?php echo htmlspecialchars($row2['u_id']); ?>"
                        data-uname="<?php echo htmlspecialchars($row2['u_name']); ?>"
                        data-num="<?php echo htmlspecialchars($row2['acc_num']); ?>"
                        data-ammo="<?php echo htmlspecialchars($row2['acc_ammo']); ?>"
                        data-type="<?php echo htmlspecialchars($row2['acc_type']); ?>"
                        data-bs-toggle="modal" data-bs-target="#viewAccModal"><i class="bi bi-receipt"></i></button>
                   
                     <button class="btn btn-warning btn-sm editAcc"
                      data-id="<?php echo htmlspecialchars ($row2['acc_id']); ?>"
                      data-ammo="<?php echo htmlspecialchars ($row2['acc_ammo']); ?>"
                    data-type="<?php echo htmlspecialchars ($row2['acc_type']); ?>"
                    data-uid="<?php echo htmlspecialchars ($row2['u_id']); ?>"
                      data-atype="<?php echo htmlspecialchars ($row2['a_type']); ?>"
                     data-num="<?php echo isset($row2['acc_num'])? htmlspecialchars ($row2['acc_num']):''; ?>"
                     data-name="<?php echo isset($row2['ab_name']) ? htmlspecialchars ($row2['ab_name']): ''; ?>"
                      data-bs-toggle="modal" data-bs-target="#editAccModal"><i class="bi bi-tools"></i></button>
                     
                     <button class="btn btn-danger btn-sm deleteAcc" data-id="<?php echo htmlspecialchars ($row2['acc_id']);?>" data-bs-toggle="modal" data-bs-target="#deleteAccModal"><i class="bi bi-trash"></i></button>
                    </td>
                     
                                </tr>
                                <?php }?>
                        </tbody>
                        </table>
                 </div>
    </div>
    </div>
</main>
<?php
try{   $query1 = "SELECT * FROM user_tbl";
       $stmt = $pdo->prepare($query1);
       $stmt->execute();
       $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
    echo "Error fetching customers: " . htmlspecialchars($e->getMessage());
    $users=[];
  }
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
        <form id="accountForm" method="POST" action="store_account.php">
              <!-- Account Holder's Name -->
              <div class="form-group">
                <label for="u_id">Account Holder's Name</label>
                <select class="form-control" id="u_id" name="u_id" required>
                      <option value="" disabled selected>Account Holder's Name</option>
                  <?php
                 foreach($users as $user):?>
                 <option value="<?php echo htmlspecialchars($user['u_id'])?>">
                <?php echo htmlspecialchars($user['u_name']);?>
                </option>
                 <?php endforeach; ?>
                </select>  
              </div>
               <div class="form-group mt-4">
          <label class="form-label">Amount</label>
          <input type="number" class="form-control" id="acc_ammo" name="acc_ammo" placeholder="Amount" required>
        </div>
              <!-- Bank / Cash Selection -->
              <div class="form-group mt-4">
                <label class="form-label">Cash/Bank</label>
               <select class="form-select" id="a_type" name="a_type" required>
                 <option value="" disabled selected>Mode</option>
                 <option value="Cash">Cash</option>
                 <option value="Bank">Bank</option>
               </select>
              </div>
          <!-- In the main modal, add these fields conditionally -->
<div class="form-group mt-4" id="bank_fields" style="display: none;">
    <label class="form-label">Account Number</label>
    <input type="text" class="form-control" id="acc_num" name="acc_num" placeholder="Account Number">
    
    <label class="form-label">Bank Name</label>
    <input type="text" class="form-control" id="ab_name" name="ab_name" placeholder="Bank Name">
</div>
               <div class="form-group mt-4">
          <label class="form-label">Account Type</label>
           <select class="form-control" id="acc_type" name="acc_type">
           <option value="" disabled selected>Select Account Type</option>
           <option value="capital">Capital</option>
           <option value="savings">Savings</option>
           <option value="current">Current</option>
           <option value="other">Other</option>
          </select>
        </div>
              <input type="hidden" name="submit" value="submit">
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                <h5 class="modal-title">View Account Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="view_acc_id" >
                <div class="row mb-2">
                    <div class="col-4"><strong>Account Holder:</strong></div>
                    <div class="col-8"><span id="view_u_id"></span></div>
                    </div>
              
                <div class="row mb-2">
                     <div class="col-4"><strong>Account Number:</strong></div>
                    <div class="col-8"><span id="view_acc_num"></span></div>
                    </div>
              
                <div class="row mb-2">
                   <div class="col-4"><strong>Bank Name/Cash:</strong></div>
                    <div class="col-8"><span id="view_ab_name"></span></div>
                    </div>
              
                <div class="row mb-2">
                    <div class="col-4"><strong>Amount:</strong></div>
                    <div class="col-8"><span id="view_acc_ammo"></span></div>
                    </div>
              
                <div class="row mb-2">
                    <div class="col-4"><strong>Account Type:</strong></div>
                    <div class="col-8"><span id="view_acc_type"></span></div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<!-- Edit Account Modal -->
 <div class="modal fade" id="editAccModal" tabindex="-1" aria-labelledby="editAccModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="editAccModalLabel">Edit Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
            </div>
            <div class="modal-body">
                <form id="editAccForm" action="update_account.php" method="POST">
                    <input type="hidden" id="edit_acc_id" name="acc_id">
                    <div class="form-group ">
                        <label for="edit_u_id">Account Holder's Name</label>
                <select class="form-control" id="edit_u_id" name="u_id" required>
                    <option value="" disabled selected>Account Holder's Name</option>
                     <?php foreach($users as $user): ?>
                   <option value="<?php echo htmlspecialchars($user['u_id'])?>">
                <?php echo htmlspecialchars($user['u_name']);?>
                </option>
                 <?php endforeach; ?>
                </select>  
                    </div>
                     <!-- Bank / Cash Selection -->
              <div class="form-group mt-4">
                <label class="form-label" for="edit_a_type">Cash/Bank</label>
              <select class="form-select" id="edit_a_type" name="a_type" required>
                 <option value="" disabled selected>Mode</option>
                 <option value="Cash">Cash</option>
                 <option value="Bank">Bank</option>
               </select>
              </div>
                    <div class="form-group mt-4" id="editbank_fields" style="display: none;">
                        <label for="edit_acc_num" class="form-label">Account Number</label>
                        <input type="text" class="form-control" id="edit_acc_num" name="acc_num" >
                        <br>
                        <label for="edit_ab_name" class="form-label">Bank Name</label>
                        <input type="text" class="form-control" id="edit_ab_name" name="ab_name" >
                    </div>
                    <div class="form-group mt-4">
                        <label for="edit_acc_ammo" class="form-label">Amount</label>
                        <input type="text" class="form-control" id="edit_acc_ammo" name="acc_ammo" required>
                    </div>
                    <div class="form-group mt-4">
                        <label for="edit_acc_type" class="form-label">Account Type</label>
                       <select class="form-control" id="edit_acc_type" name="acc_type">
                       <option value=""disabled selected>Select Account Type</option>
                       <option value="capital">Capital</option>
                       <option value="savings">Savings</option>
                       <option value="current">Current</option>
                       <option value="other">Other</option>
                    </select>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Account</button>
                    </div>
                </form>
                    </div>
    </div>
    </div>
</div>

<!-- Delete Account Modal -->

<div class="modal fade" id="deleteAccModal" tabindex="-1" aria-labelledby="deleteAccModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="deleteAccModalLabel">Delete Account</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Account?
                <input type="hidden" id="deleteACCId">
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmDeleteAcc" class="btn btn-danger">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>



<?php
include('../includes/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Bank modal trigger
    // document.getElementById('a_type').addEventListener('change', function () {
    //     const bankFields = document.getElementById('bank_fields');
    //     if (this.value === 'Bank') {
    //         bankFields.style.display = 'block';
    //     } else {
    //         bankFields.style.display = 'none';
    //     }
    // });

    
    // // Form submission
    // document.getElementById('accountForm').addEventListener('submit', function(e) {
    //     e.preventDefault();
        
    //     const aType = document.getElementById('a_type').value;
        
    //     if(aType === 'Bank'){
    //         const accNum = document.querySelector('#acc_num').value;
    //         const abName = document.querySelector('#ab_name').value;
            
    //         if(!accNum.trim() || !abName.trim()){
    //             alert('Please fill all bank details');
    //             return;
    //         }
    //     }
        
    //     // Submit form
    //     const formData = new FormData(this);
    //     fetch('store_account.php', {
    //         method: 'POST',
    //         body: formData
    //     })
    //     .then(response => response.text())
    //     .then(data => {
    //         if (data.includes('account added successfully')) {
    //             alert('Account added successfully!');
    //             window.location.href = "account.php";
    //         } else {
    //             alert("Error: " + data);
    //         }
    //     })
    //     .catch(error => {
    //         console.error('Error:', error);
    //         alert("Network error occurred. Please try again.");
    //     });
    // });
    //  // View button functionality
    // const viewButtons = document.querySelectorAll('.viewAcc');
    // viewButtons.forEach(button => {
    //     button.addEventListener('click', () => {
    //         document.getElementById('view_acc_id').value = button.getAttribute('data-id');
    //         document.getElementById('view_ab_name').textContent = button.getAttribute('data-name') || "Cash";
    //         document.getElementById('view_u_id').textContent = button.getAttribute('data-uname');
    //         document.getElementById('view_acc_num').textContent = button.getAttribute('data-num') || "N/A";
    //         document.getElementById('view_acc_ammo').textContent = button.getAttribute('data-ammo');
    //         document.getElementById('view_acc_type').textContent = button.getAttribute('data-type');
    //     });
    // });
    // // Edit button functionality
    //  document.querySelectorAll('.editAcc').forEach(button => {
    //     button.addEventListener('click', () => {
    //         document.getElementById('edit_acc_id').value = button.getAttribute('data-id');
    //         document.getElementById('edit_acc_type').value = button.getAttribute('data-type');
    //         document.getElementById('edit_u_id').value = button.getAttribute('data-uid');
    //         document.getElementById('edit_acc_ammo').value = button.getAttribute('data-ammo');
    //         const aType=button.getAttribute('data-atype');
    //         document.getElementById('edit_a_type').value = aType;
    //         document.getElementById('edit_acc_num').value = button.getAttribute('data-num')||'';
    //         document.getElementById('edit_ab_name').value = button.getAttribute('data-name')||'';

    //     const bankFields = document.getElementById('editbank_fields');
    //     if (aType === 'Bank') {
    //         bankFields.style.display = 'block';
    //     } else {
    //         bankFields.style.display = 'none';
    //     }
    //     });
    // });
    //  document.getElementById('edit_a_type').addEventListener('change', function () {
    //     const bankFields = document.getElementById('editbank_fields');
    //     if (aType === 'Bank') {
    //         bankFields.style.display = 'block';
    //     } else {
    //         bankFields.style.display = 'none';
    //         document.getElementById('edit_acc_num').value ="";
    //         document.getElementById('edit_ab_name').value ="";
    //     }
    //        });
    // // Edit Credit Form Submission
    // document.getElementById('editAccForm').addEventListener('submit', function(e){
    //     e.preventDefault();
        
    //      const form = e.target;
    //     const formData = new FormData(form);
        
    //     fetch('update_account.php', {
    //         method:'POST',
    //         body:formData
    //     }) 
    //     .then(response => {
    //         if (!response.ok) {
    //             throw new Error('Network response was not ok');
    //         }
    //         return response.text();
    //     })
    //     .then(data => {
    //         if(data.includes('account updated successfully')){
    //             alert('account updated successfully!');
    //             window.location.href = "account.php";
    //         } else {
    //             alert("Error: " + data);
    //         }
    //     }) 
    //     .catch(error => {
    //         console.error('Error:', error);
    //         alert("Network error occurred. Please try again.");
    //     });
    // });

   
    // Replace the existing a_type change event listener with this:
// document.getElementById('a_type').addEventListener('change', function () {
//     const bankFields = document.getElementById('bank_fields');
//     const accNumField = document.getElementById('acc_num');
//     const abNameField = document.getElementById('ab_name');
    
//     if (this.value === 'Bank') {
//         bankFields.style.display = 'block';
//         // Clear the fields when switching to Bank
//         accNumField.value = '';
//         abNameField.value = '';
//     } else if (this.value === 'Cash') {
//         bankFields.style.display = 'none';
//         // Set acc_num to "Cash" and ab_name to "Cash" when Cash is selected
//         accNumField.value = 'Cash';
//         abNameField.value = 'Cash';
//     } else {
//         bankFields.style.display = 'none';
//         accNumField.value = '';
//         abNameField.value = '';
//     }
// });

// // Also update the form submission validation:
// document.getElementById('accountForm').addEventListener('submit', function(e) {
//     e.preventDefault();
    
//     const aType = document.getElementById('a_type').value;
    
//     if(aType === 'Bank'){
//         const accNum = document.querySelector('#acc_num').value;
//         const abName = document.querySelector('#ab_name').value;
        
//         if(!accNum.trim() || !abName.trim()){
//             alert('Please fill all bank details');
//             return;
//         }
//     }
//     // No need to validate Cash accounts since values are auto-set
    
//     // Submit form
//     const formData = new FormData(this);
//     fetch('store_account.php', {
//         method: 'POST',
//         body: formData
//     })
//     .then(response => response.text())
//     .then(data => {
//         if (data.includes('account added successfully')) {
//             alert('Account added successfully!');
//             window.location.href = "account.php";
//         } else {
//             alert("Error: " + data);
//         }
//     })
//     .catch(error => {
//         console.error('Error:', error);
//         alert("Network error occurred. Please try again.");
//     });
// });

// // For the Edit Modal, fix the existing buggy event listener:
// document.getElementById('edit_a_type').addEventListener('change', function () {
//     const bankFields = document.getElementById('editbank_fields');
//     const editAccNum = document.getElementById('edit_acc_num');
//     const editAbName = document.getElementById('edit_ab_name');
    
//     if (this.value === 'Bank') {
//         bankFields.style.display = 'block';
//     } else if (this.value === 'Cash') {
//         bankFields.style.display = 'none';
//         // Set values to "Cash" when Cash is selected
//         editAccNum.value = "Cash";
//         editAbName.value = "Cash";
//     } else {
//         bankFields.style.display = 'none';
//         editAccNum.value = "";
//         editAbName.value = "";
//     }
// });

// // Also update the edit button click handler to handle Cash accounts:
// document.querySelectorAll('.editAcc').forEach(button => {
//     button.addEventListener('click', () => {
//         document.getElementById('edit_acc_id').value = button.getAttribute('data-id');
//         document.getElementById('edit_acc_type').value = button.getAttribute('data-type');
//         document.getElementById('edit_u_id').value = button.getAttribute('data-uid');
//         document.getElementById('edit_acc_ammo').value = button.getAttribute('data-ammo');
        
//         const aType = button.getAttribute('data-atype');
//         document.getElementById('edit_a_type').value = aType;
        
//         const bankFields = document.getElementById('editbank_fields');
//         if (aType === 'Bank') {
//             bankFields.style.display = 'block';
//             document.getElementById('edit_acc_num').value = button.getAttribute('data-num') || '';
//             document.getElementById('edit_ab_name').value = button.getAttribute('data-name') || '';
//         } else {
//             bankFields.style.display = 'none';
//             // For Cash accounts, set the values to "Cash"
//             document.getElementById('edit_acc_num').value = "Cash";
//             document.getElementById('edit_ab_name').value = "Cash";
//         }
//     });
// });
    
//     // Delete button functionality
//     const deleteButtons = document.querySelectorAll('.deleteAcc');
//     deleteButtons.forEach(button => {
//         button.addEventListener('click', () => {
//             document.getElementById('deleteACCId').value = button.getAttribute('data-id');
//         });
//     });
    
//     // Confirm delete
//     document.getElementById('confirmDeleteAcc').addEventListener('click', function() {
//         const accId = document.getElementById('deleteACCId').value;
        
//         fetch('delete_account.php', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded',
//             },
//             body: 'acc_id=' + accId
//         })
//         .then(response => response.text())
//         .then(data => {
//             if (data.includes('success')) {
//                 alert('Account deleted successfully!');
//                 window.location.href = "account.php";
//             } else {
//                 alert("Error: " + data);
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             alert("Network error occurred. Please try again.");
//         });
//     });
// });

// document.addEventListener('DOMContentLoaded', function(){
    
    // View button functionality
    const viewButtons = document.querySelectorAll('.viewAcc');
    viewButtons.forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('view_acc_id').value = button.getAttribute('data-id');
            document.getElementById('view_ab_name').textContent = button.getAttribute('data-name') || "Cash";
            document.getElementById('view_u_id').textContent = button.getAttribute('data-uname');
            document.getElementById('view_acc_num').textContent = button.getAttribute('data-num') || "N/A";
            document.getElementById('view_acc_ammo').textContent = button.getAttribute('data-ammo');
            document.getElementById('view_acc_type').textContent = button.getAttribute('data-type');
        });
    });

    // Add Account Modal - Bank/Cash toggle
    document.getElementById('a_type').addEventListener('change', function () {
        const bankFields = document.getElementById('bank_fields');
        const accNumField = document.getElementById('acc_num');
        const abNameField = document.getElementById('ab_name');
        
        if (this.value === 'Bank') {
            bankFields.style.display = 'block';
            accNumField.value = '';
            abNameField.value = '';
        } else if (this.value === 'Cash') {
            bankFields.style.display = 'none';
            accNumField.value = 'Cash';
            abNameField.value = 'Cash';
        } else {
            bankFields.style.display = 'none';
            accNumField.value = '';
            abNameField.value = '';
        }
    });

    // Add Account Form Submission
    document.getElementById('accountForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const aType = document.getElementById('a_type').value;
        
        if(aType === 'Bank'){
            const accNum = document.querySelector('#acc_num').value;
            const abName = document.querySelector('#ab_name').value;
            
            if(!accNum.trim() || !abName.trim()){
                alert('Please fill all bank details');
                return;
            }
        }
        
        // Submit form
        const formData = new FormData(this);
        fetch('store_account.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes('account added successfully')) {
                alert('Account added successfully!');
                window.location.href = "account.php";
            } else {
                alert("Error: " + data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Network error occurred. Please try again.");
        });
    });

    // Edit button functionality
    document.querySelectorAll('.editAcc').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('edit_acc_id').value = button.getAttribute('data-id');
            document.getElementById('edit_acc_type').value = button.getAttribute('data-type');
            document.getElementById('edit_u_id').value = button.getAttribute('data-uid');
            document.getElementById('edit_acc_ammo').value = button.getAttribute('data-ammo');
            
            const aType = button.getAttribute('data-atype');
            document.getElementById('edit_a_type').value = aType;
            
            const bankFields = document.getElementById('editbank_fields');
            if (aType === 'Bank') {
                bankFields.style.display = 'block';
                document.getElementById('edit_acc_num').value = button.getAttribute('data-num') || '';
                document.getElementById('edit_ab_name').value = button.getAttribute('data-name') || '';
            } else {
                bankFields.style.display = 'none';
                document.getElementById('edit_acc_num').value = "Cash";
                document.getElementById('edit_ab_name').value = "Cash";
            }
        });
    });

    // Edit Modal - Bank/Cash toggle
    document.getElementById('edit_a_type').addEventListener('change', function () {
        const bankFields = document.getElementById('editbank_fields');
        const editAccNum = document.getElementById('edit_acc_num');
        const editAbName = document.getElementById('edit_ab_name');
        
        if (this.value === 'Bank') {
            bankFields.style.display = 'block';
        } else if (this.value === 'Cash') {
            bankFields.style.display = 'none';
            editAccNum.value = "Cash";
            editAbName.value = "Cash";
        } else {
            bankFields.style.display = 'none';
            editAccNum.value = "";
            editAbName.value = "";
        }
    });

    // IMPORTANT: Edit Account Form Submission (This was commented out in your code)
    document.getElementById('editAccForm').addEventListener('submit', function(e){
        e.preventDefault();
        
        const aType = document.getElementById('edit_a_type').value;
        
        // Validate bank details if Bank is selected
        if(aType === 'Bank'){
            const accNum = document.getElementById('edit_acc_num').value;
            const abName = document.getElementById('edit_ab_name').value;
            
            if(!accNum.trim() || !abName.trim()){
                alert('Please fill all bank details');
                return;
            }
        }
        
        const formData = new FormData(this);
        // Fix: Change edit_a_type to a_type to match PHP expectation
        formData.set('a_type', aType);
        
        fetch('update_account.php', {
            method:'POST',
            body:formData
        }) 
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success'){
                alert('Account updated successfully!');
                window.location.href = "account.php";
            } else {
                alert("Error: " + data.message);
            }
        }) 
        .catch(error => {
            console.error('Error:', error);
            alert("Network error occurred. Please try again.");
        });
    });
    
    // Delete button functionality
    const deleteButtons = document.querySelectorAll('.deleteAcc');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('deleteACCId').value = button.getAttribute('data-id');
        });
    });
    
    // Confirm delete
    document.getElementById('confirmDeleteAcc').addEventListener('click', function() {
        const accId = document.getElementById('deleteACCId').value;
        
        fetch('delete_account.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'acc_id=' + accId
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes('success')) {
                alert('Account deleted successfully!');
                window.location.href = "account.php";
            } else {
                alert("Error: " + data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Network error occurred. Please try again.");
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