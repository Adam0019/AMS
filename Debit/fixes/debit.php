<?php
//  session_start();
include('../includes/header.php');
if ($_SESSION['userAuth'] != "" && $_SESSION['userAuth'] != NULL) {
    include('../includes/sidebar.php');
    
    // Add CSRF protection
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
?>
<main class="mt-3 pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h4><i class="bi bi-cash-coin"></i> Manage Debit</h4>
            </div>
            <div class="col-md-4">
                <!-- Add Debit Modal Button -->
                <button type="button" class="btn btn-light float-end" data-bs-toggle="modal" data-bs-target="#debitModal">
                    <i class="bi bi-person-fill-add"></i>
                    Add Debit
                </button>
            </div>
        </div>

        <?php
        // Database connection using PDO
        require_once('../config/dbcon.php');
        
        try {
           $query = "SELECT * FROM debit_tbl
    INNER JOIN customer_tbl ON debit_tbl.dbt_c_id = customer_tbl.c_id
    INNER JOIN gl_tbl ON debit_tbl.dbt_gl_id = gl_tbl.gl_id
    INNER JOIN account_tbl ON debit_tbl.dbt_acc_id = account_tbl.acc_id
    ORDER BY debit_tbl.debit_id";

            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching records: " . htmlspecialchars($e->getMessage());
            $result = [];
        }
        ?>

        <div class="mt-3">
            <table id="example" class="table table-striped data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Debit_Id</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>To</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Action</th> 
                    </tr>
                </thead>
                <tbody>
                   <?php
                    foreach ($result as $row) {
                          ?>
                        <tr>
                             <td><?php echo htmlspecialchars($row['debit_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['d_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['debit_mode']); ?></td>
                            <td><?php echo htmlspecialchars($row['c_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['gl_name']); ?></td>
                            <td>
                                <a href="toggle_debit.php?id=<?php echo htmlspecialchars($row['debit_id']); ?>" class="btn btn-sm <?php echo ($row['debit_status'] == 'active') ? 'btn-success' : 'btn-secondary'; ?>">
                                    <?php echo ($row['debit_status'] == 'active') ? 'Active' : 'Inactive'; ?>
                                </a>   
                            </td>
                            <td> 
                                <button class="btn btn-info btn-sm viewDebit"
                                    data-id="<?php echo htmlspecialchars($row['debit_id']); ?>"
                                    data-d_date="<?php echo htmlspecialchars($row['d_date']); ?>"
                                    data-amount="<?php echo htmlspecialchars($row['amount']); ?>"
                                    data-debit_mode="<?php echo htmlspecialchars($row['debit_mode']); ?>"
                                    data-c_name="<?php echo htmlspecialchars($row['c_name']); ?>"
                                    data-acc_num="<?php echo htmlspecialchars($row['acc_num']); ?>"
                                    data-gl_name="<?php echo htmlspecialchars($row['gl_name']); ?>"
                                    data-dbt_c_id="<?php echo htmlspecialchars($row['dbt_c_id']); ?>"
                                    data-dbt_gl_id="<?php echo htmlspecialchars($row['dbt_gl_id']); ?>"
                                    data-dbt_acc_id="<?php echo htmlspecialchars($row['dbt_acc_id']); ?>"
                                    data-cheque_number="<?php echo isset($row['cheque_number']) ? htmlspecialchars($row['cheque_number']) : ''; ?>"
                                    data-bank_name="<?php echo isset($row['bank_name']) ? htmlspecialchars($row['bank_name']) : ''; ?>"
                                    data-cheque_date="<?php echo isset($row['cheque_date']) ? htmlspecialchars($row['cheque_date']) : ''; ?>"
                                    data-bs-toggle="modal" data-bs-target="#viewDebitModal">
                                    <i class="bi bi-receipt"></i>
                                </button>
                                
                                <button class="btn btn-warning btn-sm editDebit"
                                    data-id="<?php echo htmlspecialchars($row['debit_id']); ?>"
                                    data-d_date="<?php echo htmlspecialchars($row['d_date']); ?>"
                                    data-amount="<?php echo htmlspecialchars($row['amount']); ?>"
                                    data-debit_mode="<?php echo htmlspecialchars($row['debit_mode']); ?>"
                                     data-dbt_c_id="<?php echo htmlspecialchars($row['dbt_c_id']); ?>"
                                    data-dbt_gl_id="<?php echo htmlspecialchars($row['dbt_gl_id']); ?>"
                                    data-dbt_acc_id="<?php echo htmlspecialchars($row['dbt_acc_id']); ?>"
                                    data-cheque_number="<?php echo isset($row['cheque_number']) ? htmlspecialchars($row['cheque_number']) : ''; ?>"
                                    data-bank_name="<?php echo isset($row['bank_name']) ? htmlspecialchars($row['bank_name']) : ''; ?>"
                                    data-cheque_date="<?php echo isset($row['cheque_date']) ? htmlspecialchars($row['cheque_date']) : ''; ?>"
                                    data-bs-toggle="modal" data-bs-target="#editDebitModal">
                                    <i class="bi bi-tools"></i>
                                </button>

                                <button class="btn btn-danger btn-sm deleteDebit" 
                                    data-id="<?php echo htmlspecialchars($row['debit_id']);?>" 
                                    data-bs-toggle="modal" data-bs-target="#deleteDebitModal">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
// Get Customer data
try {
    $query1 = "SELECT * FROM customer_tbl";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute();
    $customers = $stmt1->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching customers: " . htmlspecialchars($e->getMessage());
    $customers = [];
}

// Get GL data
try {
    $query2 = "SELECT * FROM gl_tbl";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute();
    $gls = $stmt2->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching GL details: " . htmlspecialchars($e->getMessage());
    $gls = [];
}

// Get GL data
try {
    $query3 = "SELECT * FROM account_tbl";
    $stmt3 = $pdo->prepare($query3);
    $stmt3->execute();
    $accs = $stmt3->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching Accounts: " . htmlspecialchars($e->getMessage());
    $accs = [];
}
?>

<!--Add Debit Modal -->
<div class="modal fade" id="debitModal" tabindex="-1" aria-labelledby="debitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="debitModalLabel">Create Debit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="debitForm" method="POST" action="store_debit.php">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount" placeholder="Amount" required>
            </div>
            <div class="form-group mt-4">
                <label for="debit_mode">Mode</label>
                <select class="form-select" id="debit_mode" name="debit_mode" required>
                     <option value="" disabled selected>Mode</option>
                     <option value="Demand Draft">Demand Draft</option>
                     <option value="Cheque">Cheque</option>
                     <option value="NEFT/RTGS">NEFT/RTGS</option>
                </select>
            </div>
            <div class="form-group mt-4">
                <label for="d_date">Date</label>
                <input type="date" class="form-control" id="d_date" name="d_date" required>
            </div>
            <div class="form-group mt-4">
                <label for="dbt_c_id">To</label>
                <select class="form-control" id="dbt_c_id" name="dbt_c_id" required>
                    <option value="" disabled selected>To</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo htmlspecialchars($customer['c_id']); ?>">
                            <?php echo htmlspecialchars($customer['c_name']); ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="other">Add New</option>
                </select>
            </div>
<!-- 
            <div class="form-group mt-4 d-none" id="customCIDField">
                <label for="c_name">Add New Customer</label>
                <input type="text" class="form-control" id="c_name" name="c_name" placeholder="Enter Customer Name">
            </div> -->
            <!-- <div class="form-group mt-4">
                <label for="dbt_acc_id">Account</label>
                <select class="form-control" id="dbt_acc_id" name="dbt_acc_id" required>
                    <option value="" disabled selected>Account</option>
                    <?php foreach ($accs as $acc): ?>
                        <option value="<?php echo htmlspecialchars($acc['acc_id']); ?>">
                            <?php echo htmlspecialchars($acc['acc_num']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div> -->

            <!-- In debit.php, replace the existing account selection div with this: -->
<div class="form-group mt-4">
    <label for="dbt_acc_id">Account</label>
    <select class="form-control" id="dbt_acc_id" name="dbt_acc_id" required>
        <option value="" disabled selected>Account</option>
        <?php foreach ($accs as $acc): ?>
            <option value="<?php echo htmlspecialchars($acc['acc_id']); ?>">
                <?php echo htmlspecialchars($acc['acc_num']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <!-- Add this div to display account balance -->
    <div class="mt-2">
        <label for="acc_ammo">Current Balance:</label>
        <span id="debit_account_balance_display" class="fw-bold text-success"></span>
    </div>
</div>


            <div class="form-group mt-4">
                 <label for="dbt_gl_id">Purpose</label>
                 <select class="form-control" id="dbt_gl_id" name="dbt_gl_id" required>
                   <option value="" disabled selected>Purpose</option>
                 <?php foreach($gls as $gl): ?>
                    <option value="<?php echo htmlspecialchars($gl['gl_id']); ?>"><?php echo htmlspecialchars($gl['gl_name']); ?></option>
                 <?php endforeach; ?>
                  <option value="other">Add New</option>
                   </select>  
            </div>
            
              <div class="form-group mt-4 d-none" id="customGLIDField">
                <label for="gl_name">Add New Purpose</label>
                <input type="text" class="form-control" id="gl_name" name="gl_name" placeholder="Enter Purpose Name">
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

<!-- Second Modal: Cheque Details -->
<div class="modal fade" id="DchequeModal" tabindex="-1" aria-labelledby="DchequeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enter Cheque Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Cheque Number</label>
          <input type="text" class="form-control" name="cheque_number" form="debitForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Bank Name</label>
          <input type="text" class="form-control" name="bank_name" form="debitForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Cheque Date</label>
          <input type="date" class="form-control" name="cheque_date" form="debitForm">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>
<!-- Third Modal: Customer Details -->
<div class="modal fade" id="DcustomerModal" tabindex="-1" aria-labelledby="DcustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enter New Customer Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Customer Name</label>
          <input type="text" class="form-control" name="c_name" form="debitForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Customer Email</label>
          <input type="email" class="form-control" name="c_email" form="debitForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Customer Phone</label>
          <input type="text" class="form-control" name="c_phone" form="debitForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Customer Address</label>
          <input type="text" class="form-control" name="c_address" form="debitForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Type</label>
          <select class="form-select" id="c_role" name="c_role" form="debitForm">
                                <option value="Buyer" selected>Buyer</option>
                                <option value="Seller">Seller</option>
                            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>

<!-- View Debit Modal -->
<div class="modal fade" id="viewDebitModal" tabindex="-1" aria-labelledby="viewDebitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View Debit Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="view_debit_id">
        <div class="row mb-2">
          <div class="col-4"><strong>Debit ID:</strong></div>
          <div class="col-8"><span id="view_debit_id_display"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>Date:</strong></div>
          <div class="col-8"><span id="view_d_date"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>Amount:</strong></div>
          <div class="col-8"><span id="view_amount"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>Mode:</strong></div>
          <div class="col-8"><span id="view_debit_mode"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>To:</strong></div>
          <div class="col-8"><span id="view_c_name"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>Account:</strong></div>
          <div class="col-8"><span id="view_acc_num"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>Purpose:</strong></div>
          <div class="col-8"><span id="view_gl_name"></span></div>
        </div>
        <div class="row mb-2">
           <div class="col-4"><strong>Cheque Number:</strong></div>
           <div class="col-8"><span id="view_cheque_number"></span></div>
        </div>
        <div class="row mb-2">
           <div class="col-4"><strong>Bank Name:</strong></div>
           <div class="col-8"><span id="view_bank_name"></span></div>
        </div>
        <div class="row mb-2">
           <div class="col-4"><strong>Cheque Date:</strong></div>
           <div class="col-8"><span id="view_cheque_date"></span></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Debit Modal -->
<div class="modal fade" id="editDebitModal" tabindex="-1" aria-labelledby="editDebitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editDebitModalLabel">Edit Debit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editDebitForm" method="POST" action="updateDebit.php">
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
          <input type="hidden" name="debit_id" id="edit_debit_id">

          <div class="form-group">
            <label for="edit_amount">Amount</label>
            <input type="number" step="0.01" min="0.01" class="form-control" id="edit_amount" name="amount" required>
          </div>

          <div class="form-group mt-4">
            <label for="edit_debit_mode">Mode</label>
            <select class="form-select" id="edit_debit_mode" name="debit_mode" required>
              <option value="" disabled selected>Mode</option>
              <option value="Demand Draft">Demand Draft</option>
              <option value="Cheque">Cheque</option>
              <option value="NEFT/RTGS">NEFT/RTGS</option>
            </select>
          </div>

          <div class="form-group mt-4">
            <label for="edit_d_date">Date</label>
            <input type="date" class="form-control" id="edit_d_date" name="d_date" required>
          </div>

          <div class="form-group mt-4">
            <label for="edit_dbt_c_id">To</label>
            <select class="form-control" id="edit_dbt_c_id" name="dbt_c_id" required>
              <option value="" disabled selected>To</option>
              <?php foreach($customers as $customer): ?>
                <option value="<?php echo htmlspecialchars($customer['c_id']); ?>"><?php echo htmlspecialchars($customer['c_name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
         <div class="form-group mt-4">
    <label for="edit_dbt_acc_id">Account</label>
    <select class="form-control" id="edit_dbt_acc_id" name="dbt_acc_id" required>
        <option value="" disabled selected>Account</option>
        <?php foreach($accs as $acc): ?>
            <option value="<?php echo htmlspecialchars($acc['acc_id']); ?>">
                <?php echo htmlspecialchars($acc['acc_num']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <!-- Add this div to display account balance in edit form -->
    <div class="mt-2">
        <label for="acc_ammo">Current Balance:</label>
        <span id="edit_debit_account_balance_display" class="fw-bold text-success"></span>
    </div>
</div>

          <div class="form-group mt-4">
            <label for="edit_dbt_gl_id">Purpose</label>
            <select class="form-control" id="edit_dbt_gl_id" name="dbt_gl_id" required>
              <option value="" disabled selected>Purpose</option>
              <?php foreach($gls as $gl): ?>
                <option value="<?php echo htmlspecialchars($gl['gl_id']); ?>"><?php echo htmlspecialchars($gl['gl_name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Cheque Details Section - Hidden by default -->
          <div id="editChequeDetails" class="cheque-details" style="display: none;">
            <hr>
            <h6 class="text-muted">Cheque Details</h6>
            <div class="form-group mt-3">
              <label class="form-label">Cheque Number</label>
              <input type="text" class="form-control" name="cheque_number" id="edit_cheque_number">
            </div>
            <div class="form-group mt-3">
              <label class="form-label">Bank Name</label>
              <input type="text" class="form-control" name="bank_name" id="edit_bank_name">
            </div>
            <div class="form-group mt-3">
              <label class="form-label">Cheque Date</label>
              <input type="date" class="form-control" name="cheque_date" id="edit_cheque_date">
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Debit Modal -->
<div class="modal fade" id="deleteDebitModal" tabindex="-1" aria-labelledby="deleteDebitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Debit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this debit record?</p>
        <p><strong>This action cannot be undone.</strong></p>
        <input type="hidden" id="delete_debit_id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>

<?php include('../includes/footer.php'); ?>

<script>
  // Function to fetch and display account balance (same as credit.php)
function fetchAccountBalance(accId, displayElementId) {
    if (!accId || accId === '') {
        document.getElementById(displayElementId).textContent = '';
        return;
    }

    // Show loading indicator
    document.getElementById(displayElementId).textContent = 'Loading...';

    // Create FormData for POST request
    const formData = new FormData();
    formData.append('acc_id', accId);

    fetch('../Credit/get_account_balance.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const balance = parseFloat(data.balance);
            document.getElementById(displayElementId).textContent = '₹ ' + balance.toFixed(2);
            // Store balance for validation
            document.getElementById(displayElementId).setAttribute('data-balance', balance);
        } else {
            document.getElementById(displayElementId).textContent = 'Error: ' + (data.error || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById(displayElementId).textContent = 'Network error';
    });
}
document.addEventListener('DOMContentLoaded', function(){
    // // Customer selection handler
    // document.getElementById('c_id').addEventListener('change', function () {
    //     const customField = document.getElementById('customCIDField');
    //     if (this.value === 'other') {
    //         customField.classList.remove('d-none');
    //         document.getElementById('c_name').required = true;
    //     } else {
    //         customField.classList.add('d-none');
    //         document.getElementById('c_name').required = false;
    //     }
    // });
    document.getElementById('dbt_acc_id').addEventListener('change', function() {
        fetchAccountBalance(this.value, 'debit_account_balance_display');
    });

    // // Purpose selection handler
    document.getElementById('dbt_gl_id').addEventListener('change', function () {
        const customField1 = document.getElementById('customGLIDField');
        if (this.value === 'other') {
            customField1.classList.remove('d-none');
            document.getElementById('gl_name').required = true;
        } else {
            customField1.classList.add('d-none');
            document.getElementById('gl_name').required = false;
        }
    });

    // Add Credit Form Submission
   // Enhanced form validation
document.getElementById('debitForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validate amount
    const amount = parseFloat(document.getElementById('amount').value);
    if (isNaN(amount) || amount <= 0) {
        alert('Please enter a valid amount greater than 0');
        return;
    }
// Check account balance
        const balanceElement = document.getElementById('debit_account_balance_display');
        const currentBalance = parseFloat(balanceElement.getAttribute('data-balance') || '0');
        
        if (amount > currentBalance) {
            alert(`Insufficient balance! Current balance: ₹${currentBalance.toFixed(2)}, Debit amount: ₹${amount.toFixed(2)}`);
            return;
        }
    // Validate customer selection
   // Validate customer selection
const customerId = document.getElementById('dbt_c_id').value;

if (customerId === 'other') {
    const customerName = document.querySelector('input[name="c_name"]').value;
    const customerEmail = document.querySelector('input[name="c_email"]').value;
    const customerPhone = document.querySelector('input[name="c_phone"]').value;
    const customerAddress = document.querySelector('input[name="c_address"]').value;
    const customerRole = document.querySelector('select[name="c_role"]').value;
    
    if (!customerName.trim() || !customerEmail.trim() || !customerPhone.trim() || !customerAddress.trim() || !customerRole.trim()) {
        alert('Please fill all customer details');
        return;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(customerEmail)) {
        alert('Please enter a valid email address');
        return;
    }
    
    // Phone validation (basic)
    const phoneRegex = /^[0-9]{10,}$/;
    if (!phoneRegex.test(customerPhone.replace(/\D/g, ''))) {
        alert('Please enter a valid phone number');
        return;
    }
}
    
    // // Validate purpose selection
    const GLId = document.getElementById('dbt_gl_id').value;
    const GLName = document.getElementById('gl_name').value;
    
    if (GLId === 'other' && GLName.trim() === '') {
        alert('Please enter valid name for new purpose');
        return;
    }
    

    // Validate cheque details if cheque is selected
    const debitMode = document.getElementById('debit_mode').value;
    if (debitMode === 'Cheque') {
        const chequeNumber = document.querySelector('input[name="cheque_number"]').value;
        const bankName = document.querySelector('input[name="bank_name"]').value;
        const chequeDate = document.querySelector('input[name="cheque_date"]').value;
        
        if (!chequeNumber.trim() || !bankName.trim() || !chequeDate) {
            alert('Please fill all cheque details');
            return;
        }
    }

    // const customerMode = document.getElementById('c_id').value;
    // if(customerMode === "other"){
    //     const customerName = document.querySelector('input[name="c_name"]').value
    //     const customerEmail = document.querySelector('input[name="c_email"]').value
    //     const customerPhone = document.querySelector('input[name="c_phone"]').value
    //     const customerAddress = document.querySelector('input[name="c_address"]').value
    //     const customerType = document.querySelector('input[name="c_role"]').value

    //     if(!customerName.trim() || !customerEmail.trim() || !customerPhone.trim() ||!customerAddress.trim() || !customerType.trim()){
    //          alert('Please fill all customer details');
    //         return;
    //     }
    // }

    // Proceed with form submission
    const form = e.target;
    const formData = new FormData(form);

    fetch('store_debit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('Debit added successfully')) {
            alert('Debit added successfully!');
            window.location.href = "debit.php";
        } else {
            alert("Error: " + data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Network error occurred. Please try again.");
    });
});
    // Show Cheque Modal when Cheque is selected in Add Debit
    document.getElementById('debit_mode').addEventListener('change', function () {
        if (this.value === 'Cheque') {
            const DchequeModal = new bootstrap.Modal(document.getElementById('DchequeModal'));
            DchequeModal.show();
        }
    });
    // Show Cheque Modal when Cheque is selected in Add Debit
    document.getElementById('dbt_c_id').addEventListener('change', function () {
        if (this.value === 'other') {
            const DcustomerModal = new bootstrap.Modal(document.getElementById('DcustomerModal'));
            DcustomerModal.show();
        }
    });

    // View Debit Modal
    const viewButtons = document.querySelectorAll('.viewDebit');
    viewButtons.forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('view_debit_id').value = button.getAttribute('data-id');
            document.getElementById('view_debit_id_display').textContent = button.getAttribute('data-id');
            document.getElementById('view_d_date').textContent = button.getAttribute('data-d_date');
            document.getElementById('view_amount').textContent = button.getAttribute('data-amount');
            document.getElementById('view_debit_mode').textContent = button.getAttribute('data-debit_mode');
            document.getElementById('view_c_name').textContent = button.getAttribute('data-c_name');
            document.getElementById('view_gl_name').textContent = button.getAttribute('data-gl_name');
            document.getElementById('view_acc_num').textContent = button.getAttribute('data-acc_num');
            document.getElementById('view_cheque_number').textContent = button.getAttribute('data-cheque_number') || 'N/A';
            document.getElementById('view_bank_name').textContent = button.getAttribute('data-bank_name') || 'N/A';
            document.getElementById('view_cheque_date').textContent = button.getAttribute('data-cheque_date') || 'N/A';
        });
    });

    // Edit Debit Modal
    document.querySelectorAll('.editDebit').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('edit_debit_id').value = button.getAttribute('data-id');
            document.getElementById('edit_d_date').value = button.getAttribute('data-d_date');
            document.getElementById('edit_amount').value = button.getAttribute('data-amount');
             // Account balance fetch for Edit Debit Modal
   
            const debitMode = button.getAttribute('data-debit_mode');
            document.getElementById('edit_debit_mode').value = debitMode;
            document.getElementById('edit_dbt_c_id').value = button.getAttribute('data-dbt_c_id');
            document.getElementById('edit_dbt_gl_id').value = button.getAttribute('data-dbt_gl_id');
            document.getElementById('edit_dbt_acc_id').value = button.getAttribute('data-dbt_acc_id');
             document.getElementById('edit_dbt_acc_id').addEventListener('change', function() {
        fetchAccountBalance(this.value, 'edit_debit_account_balance_display');
    });
            // Set cheque details
            document.getElementById('edit_cheque_number').value = button.getAttribute('data-cheque_number') || '';
            document.getElementById('edit_bank_name').value = button.getAttribute('data-bank_name') || '';
            document.getElementById('edit_cheque_date').value = button.getAttribute('data-cheque_date') || '';
            
            // Show/hide cheque details based on debit mode
            const chequeDetails = document.getElementById('editChequeDetails');
            if (debitMode === 'Cheque') {
                chequeDetails.style.display = 'block';
            } else {
                chequeDetails.style.display = 'none';
            }
        });
    });

    // Show/hide cheque details when debit mode changes in edit modal
    document.getElementById('edit_debit_mode').addEventListener('change', function () {
        const chequeDetails = document.getElementById('editChequeDetails');
        if (this.value === 'Cheque') {
            chequeDetails.style.display = 'block';
        } else {
            chequeDetails.style.display = 'none';
            // Clear cheque fields when not needed
            document.getElementById('edit_cheque_number').value = '';
            document.getElementById('edit_bank_name').value = '';
            document.getElementById('edit_cheque_date').value = '';
        }
    });

    // Edit Debit Form Submission
    document.getElementById('editDebitForm').addEventListener('submit', function(e){
        e.preventDefault();
        
        // Validate amount
        const amount = document.getElementById('edit_amount').value;
        if (isNaN(amount) || parseFloat(amount) <= 0) {
            alert('Please enter a valid amount greater than 0');
            return;
        }
        // Check account balance for edit form
        const balanceElement = document.getElementById('edit_debit_account_balance_display');
        const currentBalance = parseFloat(balanceElement.getAttribute('data-balance') || '0');
        
        if (amount > currentBalance) {
            alert(`Insufficient balance! Current balance: ₹${currentBalance.toFixed(2)}, Debit amount: ₹${amount.toFixed(2)}`);
            return;
        }
        const form = e.target;
        const formData = new FormData(form);
        
        fetch('updateDebit.php', {
            method:'POST',
            body:formData
        }) 
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            if(data.includes('Debit updated successfully')){
                alert('Debit updated successfully!');
                window.location.href = "debit.php";
            } else {
                alert("Error: " + data);
            }
        }) 
        .catch(error => {
            console.error('Error:', error);
            alert("Network error occurred. Please try again.");
        });
    });

    // Delete Debit Modal
    document.querySelectorAll('.deleteDebit').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('delete_debit_id').value = button.getAttribute('data-id');
        });
    });

    // Confirm Delete
    document.getElementById('confirmDelete').addEventListener('click', function() {
        const debitId = document.getElementById('delete_debit_id').value;
        
        if (debitId) {
            fetch('delete_debit.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'debit_id=' + encodeURIComponent(debitId) + '&csrf_token=' + encodeURIComponent('<?php echo $_SESSION['csrf_token']; ?>')
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                if(data.includes('Debit deleted successfully')){
                    alert('Debit deleted successfully!');
                    window.location.href = "debit.php";
                } else {
                    alert("Error: " + data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Network error occurred. Please try again.");
            });
        }
    });
});
</script>

<?php    
} else {
    echo '<script>
        alert("Not Authorised!");
        window.location.href = "../index.php";
    </script>';
}
?>