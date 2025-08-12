<?php
// session_start();
include('../includes/header.php');
if ($_SESSION['userAuth'] != "" && $_SESSION['userAuth'] != NULL) {
    include('../includes/sidebar.php');
    
    // Add CSRF protection
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $date_sql="";
    $toDate=$fromDate="";
    if(isset($_POST['submit'])){
      $from=$_POST['from'];
      $fromDate=$from;
      $fromArr=explode("/",$from);
      $from=$fromArr['2'].'-'.$fromArr['1'].'-'.$fromArr['0'];
      //$from=$from."00:00:00";
      $to=$_POST['to'];
      $toDate=$to;
      $toArr=explode("/",$to);
      $to=$toArr['2'].'-'.$toArr['1'].'-'.$toArr['0'];
      //$to=$to."00:00:00";
      $date_sql="WHERE c_date >='$from' && c_date <= '$to' ";
    }
?>
<main class="mt-3 pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h4><i class="bi bi-cash-coin"></i> Manage Credit</h4>
            </div>
            <div class="mt-4 px-4">
                <!-- Add credit Modal Button -->
                <button type="button" class="btn btn-light float-end" data-bs-toggle="modal" data-bs-target="#creditModal">
                    <i class="bi bi-person-fill-add"></i>
                    Add Credit
                </button>
            </div>
        </div>
     
        <?php
        // Database connection using PDO
        require_once('../config/dbcon.php');
        
        try {
           $query = "SELECT * FROM credit_tbl
    INNER JOIN customer_tbl ON credit_tbl.c_id = customer_tbl.c_id
    INNER JOIN gl_tbl ON credit_tbl.gl_id = gl_tbl.gl_id
    INNER JOIN account_tbl ON credit_tbl.acc_id = account_tbl.acc_id
    $date_sql
    ORDER BY credit_tbl.credit_id";

            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching records: " . htmlspecialchars($e->getMessage());
            $result = [];
        }
        ?>
        
     
        <div class="mt-3">
           <div>
        <form method="POST">
          <label for="from">From</label>
          <input type="text" id="from" name="from" required value="<?php echo $fromDate ?>">
          <label for="to">To</label>
          <input type="text" id="to" name="to" required value="<?php echo $toDate ?>">
          <input type="submit" name="submit" value="Filter">
        </form>
      </div>
      <br><br>
            <table id="example" class="table table-striped data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Credit_Id</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>From</th>
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
                             <td><?php echo htmlspecialchars($row['credit_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['c_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['credit_mode']); ?></td>
                            <td><?php echo htmlspecialchars($row['c_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['gl_name']); ?></td>
                            <td>
                                <a href="toggle_credit.php?id=<?php echo htmlspecialchars($row['credit_id']); ?>" class="btn btn-sm <?php echo ($row['credit_status'] == 'active') ? 'btn-success' : 'btn-secondary'; ?>">
                                    <?php echo ($row['credit_status'] == 'active') ? 'Active' : 'Inactive'; ?>
                                </a>   
                            </td>
                            <td> 
                                <button class="btn btn-info btn-sm viewCredit"
                                    data-id="<?php echo htmlspecialchars($row['credit_id']); ?>"
                                    data-c_date="<?php echo htmlspecialchars($row['c_date']); ?>"
                                    data-amount="<?php echo htmlspecialchars($row['amount']); ?>"
                                    data-credit_mode="<?php echo htmlspecialchars($row['credit_mode']); ?>"
                                    data-c_name="<?php echo htmlspecialchars($row['c_name']); ?>"
                                    data-gl_name="<?php echo htmlspecialchars($row['gl_name']); ?>"
                                    data-acc_num="<?php echo htmlspecialchars($row['acc_num']); ?>"
                                    data-acc_ammo="<?php echo htmlspecialchars($row['acc_ammo']); ?>"
                                    data-c_id="<?php echo htmlspecialchars($row['c_id']); ?>"
                                    data-gl_id="<?php echo htmlspecialchars($row['gl_id']); ?>"
                                    data-acc_id="<?php echo htmlspecialchars($row['acc_id']); ?>"
                                    data-cheque_number="<?php echo isset($row['cheque_number']) ? htmlspecialchars($row['cheque_number']) : ''; ?>"
                                    data-bank_name="<?php echo isset($row['bank_name']) ? htmlspecialchars($row['bank_name']) : ''; ?>"
                                    data-cheque_date="<?php echo isset($row['cheque_date']) ? htmlspecialchars($row['cheque_date']) : ''; ?>"
                                    data-bs-toggle="modal" data-bs-target="#viewCreditModal">
                                    <i class="bi bi-receipt"></i>
                                </button>
                                
                                <button class="btn btn-warning btn-sm editCredit"
                                    data-id="<?php echo htmlspecialchars($row['credit_id']); ?>"
                                    data-c_date="<?php echo htmlspecialchars($row['c_date']); ?>"
                                    data-amount="<?php echo htmlspecialchars($row['amount']); ?>"
                                    data-credit_mode="<?php echo htmlspecialchars($row['credit_mode']); ?>"
                                    data-c_id="<?php echo htmlspecialchars($row['c_id']); ?>"
                                    data-gl_id="<?php echo htmlspecialchars($row['gl_id']); ?>"
                                     data-acc_id="<?php echo htmlspecialchars($row['acc_id']); ?>"
                                    data-cheque_number="<?php echo isset($row['cheque_number']) ? htmlspecialchars($row['cheque_number']) : ''; ?>"
                                    data-bank_name="<?php echo isset($row['bank_name']) ? htmlspecialchars($row['bank_name']) : ''; ?>"
                                    data-cheque_date="<?php echo isset($row['cheque_date']) ? htmlspecialchars($row['cheque_date']) : ''; ?>"
                                    data-bs-toggle="modal" data-bs-target="#editCreditModal">
                                    <i class="bi bi-tools"></i>
                                </button>

                                <button class="btn btn-danger btn-sm deleteCredit" 
                                    data-id="<?php echo htmlspecialchars($row['credit_id']);?>" 
                                    data-bs-toggle="modal" data-bs-target="#deleteCreditModal">
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
    echo "Error fetching GL Details: " . htmlspecialchars($e->getMessage());
    $gls = [];
}

// Get Account data
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

<!--Add Credit Modal -->
<div class="modal fade" id="creditModal" tabindex="-1" aria-labelledby="creditModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="creditModalLabel">Create Credit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="creditForm" method="POST" action="store_credit.php">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount" placeholder="Amount" required>
            </div>
            <div class="form-group mt-4">
                <label for="credit_mode">Mode</label>
                <select class="form-select" id="credit_mode" name="credit_mode" required>
                     <option value="" disabled selected>Mode</option>
                     <option value="Demand Draft">Demand Draft</option>
                     <option value="Cheque">Cheque</option>
                     <option value="NEFT/RTGS">NEFT/RTGS</option>
                </select>
            </div>
            <div class="form-group mt-4">
                <label for="c_date">Date</label>
                <input type="date" class="form-control" id="c_date" name="c_date" required>
            </div>
            <div class="form-group mt-4">
                <label for="c_id">From</label>
                <select class="form-control" id="c_id" name="c_id" required>
                    <option value="" disabled selected>From</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo htmlspecialchars($customer['c_id']); ?>">
                            <?php echo htmlspecialchars($customer['c_name']); ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="other">Add New</option>
                </select>
            </div>
            <!-- <div class="form-group mt-4">
                <label for="acc_id">Account</label>
                <select class="form-control" id="acc_id" name="acc_id" required>
                    <option value="" disabled selected>Account</option>
                    <?php foreach ($accs as $acc): ?>
                        <option value="<?php echo htmlspecialchars($acc['acc_id']); ?>">
                            <?php echo htmlspecialchars($acc['acc_num'].' ₹ '.$acc['acc_ammo']); ?>
                           
                        </option>

                    <?php endforeach; ?>
                </select>
            </div> -->
           <div class="form-group mt-4">
    <label for="acc_id">Account</label>
    <select class="form-control" id="acc_id" name="acc_id" required>
        <option value="" disabled selected>Account</option>
        <?php foreach ($accs as $acc): ?>
            <option value="<?php echo htmlspecialchars($acc['acc_id']); ?>">
                <?php echo htmlspecialchars($acc['acc_num']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <!-- Add this div to display account balance -->
    <div class="mt-4">
        <!-- <small class="text-muted"> </small> -->
        <label for="acc_ammo">Current Balance:</label>
        <span id="account_balance_display" class="fw-bold text-success"></span>
    </div>
</div>
<!-- 
            <div class="form-group mt-4 d-none" id="customCIDField">
                <label for="c_name">Add New Customer</label>
                <input type="text" class="form-control" id="c_name" name="c_name" placeholder="Enter Customer Name">
            </div> -->

            <div class="form-group mt-4">
                 <label for="gl_id">Purpose</label>
                 <select class="form-control" id="gl_id" name="gl_id" required>
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
<div class="modal fade" id="chequeModal" tabindex="-1" aria-labelledby="chequeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enter Cheque Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Cheque Number</label>
          <input type="text" class="form-control" name="cheque_number" form="creditForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Bank Name</label>
          <input type="text" class="form-control" name="bank_name" form="creditForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Cheque Date</label>
          <input type="date" class="form-control" name="cheque_date" form="creditForm">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>
<!-- Third Modal: Customer Details -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enter New Customer Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Customer Name</label>
          <input type="text" class="form-control" name="c_name" form="creditForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Customer Email</label>
          <input type="email" class="form-control" name="c_email" form="creditForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Customer Phone</label>
          <input type="text" class="form-control" name="c_phone" form="creditForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Customer Address</label>
          <input type="text" class="form-control" name="c_address" form="creditForm">
        </div>
        <div class="mb-3">
          <label class="form-label">Type</label>
          <select class="form-select" id="c_role" name="c_role" form="creditForm">
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

<!-- View Credit Modal -->
<div class="modal fade" id="viewCreditModal" tabindex="-1" aria-labelledby="viewCreditModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View Credit Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="view_credit_id">
        <div class="row mb-2">
          <div class="col-4"><strong>Credit ID:</strong></div>
          <div class="col-8"><span id="view_credit_id_display"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>Date:</strong></div>
          <div class="col-8"><span id="view_c_date"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>Amount:</strong></div>
          <div class="col-8"><span id="view_amount"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>Mode:</strong></div>
          <div class="col-8"><span id="view_credit_mode"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>Account No:</strong></div>
          <div class="col-8"><span id="view_acc_num"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>Account Balance:</strong></div>
          <div class="col-8"><span id="view_acc_ammo"></span></div>
        </div>
        <div class="row mb-2">
          <div class="col-4"><strong>From:</strong></div>
          <div class="col-8"><span id="view_c_name"></span></div>
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

<!-- Edit Credit Modal -->
<div class="modal fade" id="editCreditModal" tabindex="-1" aria-labelledby="editCreditModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editCreditModalLabel">Edit Credit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editCreditForm" method="POST" action="updateCredit.php">
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
          <input type="hidden" name="credit_id" id="edit_credit_id">

          <div class="form-group">
            <label for="edit_amount">Amount</label>
            <input type="number" step="0.01" min="0.01" class="form-control" id="edit_amount" name="amount" required>
          </div>

          <div class="form-group mt-4">
            <label for="edit_credit_mode">Mode</label>
            <select class="form-select" id="edit_credit_mode" name="credit_mode" required>
              <option value="" disabled selected>Mode</option>
              <option value="Demand Draft">Demand Draft</option>
              <option value="Cheque">Cheque</option>
              <option value="NEFT/RTGS">NEFT/RTGS</option>
            </select>
          </div>

          <div class="form-group mt-4">
            <label for="edit_c_date">Date</label>
            <input type="date" class="form-control" id="edit_c_date" name="c_date" required>
          </div>

          <div class="form-group mt-4">
            <label for="edit_c_id">From</label>
            <select class="form-control" id="edit_c_id" name="c_id" required>
              <option value="" disabled selected>From</option>
              <?php foreach($customers as $customer): ?>
                <option value="<?php echo htmlspecialchars($customer['c_id']); ?>"><?php echo htmlspecialchars($customer['c_name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <!-- <div class="form-group mt-4">
            <label for="edit_acc_id">Account</label>
            <select class="form-control" id="edit_acc_id" name="acc_id" required>
              <option value="" disabled selected>Account</option>
              <?php foreach($accs as $acc): ?>
                <option value="<?php echo htmlspecialchars($acc['acc_id']); ?>">
                <?php echo htmlspecialchars($acc['acc_num']); ?></option>
                <?php echo htmlspecialchars($acc['acc_ammo']); ?></option>
              <?php endforeach; ?>
            </select>
          </div> -->
          <div class="form-group mt-4">
    <label for="edit_acc_id">Account</label>
    <select class="form-control" id="edit_acc_id" name="acc_id" required>
        <option value="" disabled selected>Account</option>
        <?php foreach($accs as $acc): ?>
            <option value="<?php echo htmlspecialchars($acc['acc_id']); ?>">
                <?php echo htmlspecialchars($acc['acc_num']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <!-- Add this div to display account balance -->
    <div class="mt-2">
        <label for="acc_ammo">Current Balance:</label>
        <span id="edit_account_balance_display" class="fw-bold text-success"></span>
    </div>
</div>

          <div class="form-group mt-4">
            <label for="edit_gl_id">Purpose</label>
            <select class="form-control" id="edit_gl_id" name="gl_id" required>
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

<!-- Delete Credit Modal -->
<div class="modal fade" id="deleteCreditModal" tabindex="-1" aria-labelledby="deleteCreditModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Credit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this credit record?</p>
        <p><strong>This action cannot be undone.</strong></p>
        <input type="hidden" id="delete_credit_id">
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
   $( function() {
    var dateFormat = "dd/mm/yy",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 2,
          dateFormat:"dd/mm/yy"
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2,
        dateFormat:"dd/mm/yy"
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
  // Function to fetch and display account balance
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

    fetch('get_account_balance.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(displayElementId).textContent = '₹ ' + parseFloat(data.balance).toFixed(2);
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
    // // Purpose selection handler
    document.getElementById('gl_id').addEventListener('change', function () {
        const customField1 = document.getElementById('customGLIDField');
        if (this.value === 'other') {
            customField1.classList.remove('d-none');
            document.getElementById('gl_name').required = true;
        } else {
            customField1.classList.add('d-none');
            document.getElementById('gl_name').required = false;
        }
    });

 // Account balance fetch for Add Credit Modal
    document.getElementById('acc_id').addEventListener('change', function() {
        fetchAccountBalance(this.value, 'account_balance_display');
    });
    // Add Credit Form Submission
   // Enhanced form validation
document.getElementById('creditForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validate amount
    const amount = parseFloat(document.getElementById('amount').value);
    if (isNaN(amount) || amount <= 0) {
        alert('Please enter a valid amount greater than 0');
        return;
    }

    // Validate customer selection
   // Validate customer selection
const customerId = document.getElementById('c_id').value;

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
    const GLId = document.getElementById('gl_id').value;
    const GLName = document.getElementById('gl_name').value;
    
    if (GLId === 'other' && GLName.trim() === '') {
        alert('Please enter valid name for new purpose');
        return;
    }
    

    // Validate cheque details if cheque is selected
    const creditMode = document.getElementById('credit_mode').value;
    if (creditMode === 'Cheque') {
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

    fetch('store_credit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('Credit added successfully')) {
            alert('Credit added successfully!');
            window.location.href = "credit.php";
        } else {
            alert("Error: " + data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Network error occurred. Please try again.");
    });
});
    // Show Cheque Modal when Cheque is selected in Add Credit
    document.getElementById('credit_mode').addEventListener('change', function () {
        if (this.value === 'Cheque') {
            const chequeModal = new bootstrap.Modal(document.getElementById('chequeModal'));
            chequeModal.show();
        }
    });
    // Show Cheque Modal when Cheque is selected in Add Credit
    document.getElementById('c_id').addEventListener('change', function () {
        if (this.value === 'other') {
            const customerModal = new bootstrap.Modal(document.getElementById('customerModal'));
            customerModal.show();
        }
    });

    // View Credit Modal
    const viewButtons = document.querySelectorAll('.viewCredit');
    viewButtons.forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('view_credit_id').value = button.getAttribute('data-id');
            document.getElementById('view_credit_id_display').textContent = button.getAttribute('data-id');
            document.getElementById('view_c_date').textContent = button.getAttribute('data-c_date');
            document.getElementById('view_amount').textContent = button.getAttribute('data-amount');
            document.getElementById('view_credit_mode').textContent = button.getAttribute('data-credit_mode');
            document.getElementById('view_c_name').textContent = button.getAttribute('data-c_name');
            document.getElementById('view_gl_name').textContent = button.getAttribute('data-gl_name');
            document.getElementById('view_acc_num').textContent = button.getAttribute('data-acc_num');
            document.getElementById('view_acc_ammo').textContent = button.getAttribute('data-acc_ammo');
            document.getElementById('view_cheque_number').textContent = button.getAttribute('data-cheque_number') || 'N/A';
            document.getElementById('view_bank_name').textContent = button.getAttribute('data-bank_name') || 'N/A';
            document.getElementById('view_cheque_date').textContent = button.getAttribute('data-cheque_date') || 'N/A';
        });
    });

    // Edit Credit Modal
    document.querySelectorAll('.editCredit').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('edit_credit_id').value = button.getAttribute('data-id');
            document.getElementById('edit_c_date').value = button.getAttribute('data-c_date');
            document.getElementById('edit_amount').value = button.getAttribute('data-amount');
            
            const creditMode = button.getAttribute('data-credit_mode');
            document.getElementById('edit_credit_mode').value = creditMode;
            document.getElementById('edit_c_id').value = button.getAttribute('data-c_id');
            document.getElementById('edit_gl_id').value = button.getAttribute('data-gl_id');
            // document.getElementById('edit_acc_id').value = button.getAttribute('data-acc_id');
          // Add this to your existing JavaScript in credit.php
            document.getElementById('edit_acc_id').addEventListener('change', function() {
            fetchAccountBalance(this.value, 'edit_account_balance_display');
});
            
            // Set cheque details
            document.getElementById('edit_cheque_number').value = button.getAttribute('data-cheque_number') || '';
            document.getElementById('edit_bank_name').value = button.getAttribute('data-bank_name') || '';
            document.getElementById('edit_cheque_date').value = button.getAttribute('data-cheque_date') || '';
            
            // Show/hide cheque details based on credit mode
            const chequeDetails = document.getElementById('editChequeDetails');
            if (creditMode === 'Cheque') {
                chequeDetails.style.display = 'block';
            } else {
                chequeDetails.style.display = 'none';
            }
        });
    });

    // Show/hide cheque details when credit mode changes in edit modal
    document.getElementById('edit_credit_mode').addEventListener('change', function () {
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

    // Edit Credit Form Submission
    document.getElementById('editCreditForm').addEventListener('submit', function(e){
        e.preventDefault();
        
        // Validate amount
        const amount = document.getElementById('edit_amount').value;
        if (isNaN(amount) || parseFloat(amount) <= 0) {
            alert('Please enter a valid amount greater than 0');
            return;
        }
        
        const form = e.target;
        const formData = new FormData(form);
        
        fetch('updateCredit.php', {
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
            if(data.includes('Credit updated successfully')){
                alert('Credit updated successfully!');
                window.location.href = "credit.php";
            } else {
                alert("Error: " + data);
            }
        }) 
        .catch(error => {
            console.error('Error:', error);
            alert("Network error occurred. Please try again.");
        });
    });

    // Delete Credit Modal
    document.querySelectorAll('.deleteCredit').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('delete_credit_id').value = button.getAttribute('data-id');
        });
    });

    // Confirm Delete
    document.getElementById('confirmDelete').addEventListener('click', function() {
        const creditId = document.getElementById('delete_credit_id').value;
        
        if (creditId) {
            fetch('delete_credit.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'credit_id=' + encodeURIComponent(creditId) + '&csrf_token=' + encodeURIComponent('<?php echo $_SESSION['csrf_token']; ?>')
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                if(data.includes('Credit deleted successfully')){
                    alert('Credit deleted successfully!');
                    window.location.href = "credit.php";
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