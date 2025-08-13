<?php
// session_start();
include('../includes/header.php');
if ($_SESSION['userAuth'] != "" && $_SESSION['userAuth'] != NULL) {
    include('../includes/sidebar.php');
    
    // Add CSRF protection
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    $date_sql = "";
    $toDate = $fromDate = "";
    $credit_total = $debit_total = 0;
    
    if(isset($_POST['submit'])){
        $from = $_POST['from'];
        $fromDate = $from;
        $fromArr = explode("/", $from);
        $from = $fromArr['2'].'-'.$fromArr['1'].'-'.$fromArr['0'];
        
        $to = $_POST['to'];
        $toDate = $to;
        $toArr = explode("/", $to);
        $to = $toArr['2'].'-'.$toArr['1'].'-'.$toArr['0'];
        
        $date_sql = "WHERE c_date >= '$from' AND c_date <= '$to'";
        $date_sql_debit = "WHERE d_date >= '$from' AND d_date <= '$to'";
    } else {
        $date_sql_debit = "";
    }
?>
<main class="mt-3 pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
              <h4><i class="bi bi-file-earmark-bar-graph"></i> Balance Sheet</h4>
                <p class="text-muted">Credit / Debit</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-success" onclick="printBalanceSheet()">
                    <i class="bi bi-printer"></i> 
                </button>
                <button class="btn btn-info" onclick="exportToExcel()">
                    <i class="bi bi-filetype-xls"></i>
                </button>
                  <br><br>
                 <!-- Add Debit Modal Button -->
                <button type="button" class="btn btn-light " data-bs-toggle="modal" data-bs-target="#debitModal">
                    <i class="bi bi-person-fill-add"></i>
                    Add Debit
                </button> 
                <!-- Add credit Modal Button -->
               <button type="button" class="btn btn-light " data-bs-toggle="modal" data-bs-target="#creditModal">
                   <i class="bi bi-person-fill-add"></i>
                   Add Credit
               </button>
            </div>
        </div>
          <!-- Date Filter Form -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" class="row g-3">
                            <div class="col-md-3">
                                <label for="from" class="form-label">From</label>
                                <input type="text" class="form-control" id="from" name="from" 
                                       value="<?php echo $fromDate; ?>" placeholder="dd/mm/yyyy">
                            </div>
                            <div class="col-md-3">
                                <label for="to" class="form-label">To</label>
                                <input type="text" class="form-control" id="to" name="to" 
                                       value="<?php echo $toDate; ?>" placeholder="dd/mm/yyyy">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" name="submit" class="btn btn-primary d-block">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <a href="balance_sheet.php" class="btn btn-outline-secondary d-block">
                                    <i class="bi bi-arrow-clockwise"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

          <?php
        // Database connection using PDO
        require_once('../config/dbcon.php');
        try{
              $credit_query = "SELECT credit_tbl.*, customer_tbl.c_name, gl_tbl.gl_name, account_tbl.acc_num 
                           FROM credit_tbl
                           INNER JOIN customer_tbl ON credit_tbl.c_id = customer_tbl.c_id
                           INNER JOIN gl_tbl ON credit_tbl.gl_id = gl_tbl.gl_id
                           INNER JOIN account_tbl ON credit_tbl.acc_id = account_tbl.acc_id
                           $date_sql
                           ORDER BY credit_tbl.c_date ASC";
            
            $credit_stmt = $pdo->prepare($credit_query);
            $credit_stmt->execute();
            $credit_result = $credit_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate credit total
            foreach($credit_result as $credit_row) {
                $credit_total += $credit_row['amount'];
            }
        }
        catch(PDOException $e){
             echo "Error fetching records: " . htmlspecialchars($e->getMessage());
              $credit_result = [];
        }
         try {
            $debit_query = "SELECT debit_tbl.*, customer_tbl.c_name, gl_tbl.gl_name, account_tbl.acc_num 
                          FROM debit_tbl
                          INNER JOIN customer_tbl ON debit_tbl.dbt_c_id = customer_tbl.c_id
                          INNER JOIN gl_tbl ON debit_tbl.dbt_gl_id = gl_tbl.gl_id
                          INNER JOIN account_tbl ON debit_tbl.dbt_acc_id = account_tbl.acc_id
                          $date_sql_debit
                          ORDER BY debit_tbl.d_date ASC";
            
            $debit_stmt = $pdo->prepare($debit_query);
            $debit_stmt->execute();
            $debit_result = $debit_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate debit total
            foreach($debit_result as $debit_row) {
                $debit_total += $debit_row['amount'];
            }
            
        } catch (PDOException $e) {
            echo "Error fetching debit records: " . htmlspecialchars($e->getMessage());
            $debit_result = [];
        }

        $balance = $credit_total - $debit_total;
        ?>
    </div>
  <!-- Balance Sheet Table -->
        <div class="row mt-4" id="balance-sheet-content">
            <!-- Credit Section -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Credit</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>From</th>
                                    <th>Purpose</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($credit_result)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No credit transactions found
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($credit_result as $credit_row): ?>
                                    <tr>
                                        <td><?php echo date('d M Y', strtotime($credit_row['c_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($credit_row['c_name']); ?></td>
                                        <td><?php echo htmlspecialchars($credit_row['gl_name']); ?></td>
                                        <td class="text-end">₹ <?php echo number_format($credit_row['amount'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="table-info">
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th class="text-end">₹ <?php echo number_format($credit_total, 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Debit Section -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Debit</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>To</th>
                                    <th>Purpose</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($debit_result)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No debit transactions found
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($debit_result as $debit_row): ?>
                                    <tr>
                                        <td><?php echo date('d M Y', strtotime($debit_row['d_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($debit_row['c_name']); ?></td>
                                        <td><?php echo htmlspecialchars($debit_row['gl_name']); ?></td>
                                        <td class="text-end">₹ <?php echo number_format($debit_row['amount'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="table-danger">
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th class="text-end">₹ <?php echo number_format($debit_total, 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Summary -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Net Profit / Loss: 
                            <span class="<?php echo $balance >= 0 ? 'text-success' : 'text-danger'; ?>">
                                ₹ <?php echo number_format($balance, 2); ?>
                            </span>
                        </h5>
                        
                        <p class="text-muted">
                            <?php if($balance > 0): ?>
                                You have a Profit (Credits exceed Debits)
                            <?php elseif($balance < 0): ?>
                                You have a Loss (Debits exceed Credits)
                            <?php else: ?>
                                Your accounts are balanced
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        <form id="creditForm" method="POST" action="../Credit/store_credit.php">
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

<!--Add Debit Modal -->
<div class="modal fade" id="debitModal" tabindex="-1" aria-labelledby="debitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="debitModalLabel">Create Debit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="debitForm" method="POST" action="../Debit/store_debit.php">
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

</main>

<?php
include('../includes/footer.php');?>
<script>
    // Date picker initialization
$(function() {
    var dateFormat = "dd/mm/yy",
        from = $("#from")
            .datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 2,
                dateFormat: "dd/mm/yy"
            })
            .on("change", function() {
                to.datepicker("option", "minDate", getDate(this));
            }),
        to = $("#to").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 2,
            dateFormat: "dd/mm/yy"
        })
        .on("change", function() {
            from.datepicker("option", "maxDate", getDate(this));
        });

    function getDate(element) {
        var date;
        try {
            date = $.datepicker.parseDate(dateFormat, element.value);
        } catch(error) {
            date = null;
        }
        return date;
    }
});

// Print function
function printBalanceSheet() {
    var printContents = document.getElementById('balance-sheet-content').innerHTML;
    var originalContents = document.body.innerHTML;
    
    var printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>Balance Sheet</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @media print {
                    .card { page-break-inside: avoid; }
                    body { font-size: 12px; }
                }
            </style>
        </head>
        <body>
            <div class="container-fluid">
                <h2 class="text-center mb-4">Balance Sheet</h2>
                <p class="text-center text-muted">Generated on: ${new Date().toLocaleDateString()}</p>
                ${printContents}
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Export to Excel function
function exportToExcel() {
    // Create a simple CSV export
    let csvContent = "data:text/csv;charset=utf-8,";
    
    // Add headers
    csvContent += "Type,Date,Party,Purpose,Amount\n";
    
    // Add credit data
    <?php foreach($credit_result as $credit_row): ?>
    csvContent += "Credit,<?php echo $credit_row['c_date']; ?>,<?php echo addslashes($credit_row['c_name']); ?>,<?php echo addslashes($credit_row['gl_name']); ?>,<?php echo $credit_row['amount']; ?>\n";
    <?php endforeach; ?>
    
    // Add debit data
    <?php foreach($debit_result as $debit_row): ?>
    csvContent += "Debit,<?php echo $debit_row['d_date']; ?>,<?php echo addslashes($debit_row['c_name']); ?>,<?php echo addslashes($debit_row['gl_name']); ?>,<?php echo $debit_row['amount']; ?>\n";
    <?php endforeach; ?>
    
    // Add totals
    csvContent += "TOTAL CREDITS,,,Total,<?php echo $credit_total; ?>\n";
    csvContent += "TOTAL DEBITS,,,Total,<?php echo $debit_total; ?>\n";
    csvContent += "NET BALANCE,,,Balance,<?php echo $balance; ?>\n";
    
    var encodedUri = encodeURI(csvContent);
    var link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "balance_sheet_" + new Date().toISOString().slice(0,10) + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
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

    fetch('../Credit/get_account_balance.php', {
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

    fetch('../Credit/store_credit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('Credit added successfully')) {
            alert('Credit added successfully!');
            window.location.href = "balance_sheet.php";
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

    fetch('../Debit/store_debit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('Debit added successfully')) {
            alert('Debit added successfully!');
            window.location.href = "balance_sheet.php";
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