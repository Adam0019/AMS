<?php
include('../includes/header.php');
if ($_SESSION['userAuth'] != "" && $_SESSION['userAuth'] != NULL) {
    include('../includes/sidebar.php');
?>
<main class="mt-3 pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h4><i class="bi bi-cash-coin"></i> Manage Credit</h4>
                </div>

                <?php
                // Database connection using PDO
                require_once('../config/dbcon.php');
                
                ?>
                <!-- Add credit Modal Button -->
                <div class="mt-4 px-4">
                    <button type="button" class="btn btn-light float-end" data-bs-toggle="modal" data-bs-target="#creditModal">
                        <i class="bi bi-person-fill-add"></i>
                        Add Credit
                    </button>

                </div>
              
                </div>

        <?php
        try {
           $query = "SELECT * FROM credit_tbl
    INNER JOIN customer_tbl ON credit_tbl.c_id = customer_tbl.c_id
    INNER JOIN gl_tbl ON credit_tbl.gl_id = gl_tbl.gl_id
    ORDER BY credit_tbl.credit_id
";

            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching records: " . $e->getMessage();
            $result = [];
        }
        ?>

        <div class="mt-3">
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
                            <td> <button class="btn btn-info btn-sm viewCredit"
                     data-id="<?php echo $row['credit_id']; ?>"
                     data-c_date="<?php echo $row['c_date']; ?>"
                     data-amount="<?php echo $row['amount']; ?>"
                     data-credit_mode="<?php echo $row['credit_mode']; ?>"
                     data-c_id="<?php echo $row['c_id']; ?>"
                     data-gl_id="<?php echo $row['gl_id']; ?>"
                    
                     
                     data-bs-toggle="modal" data-bs-target="#viewCreditModal"><i class="bi bi-receipt"></i></button>
                   
                     
                      <button class="btn btn-warning btn-sm editCredit"
              data-id="<?php echo $row['credit_id']; ?>"
                data-c_date="<?php echo $row['c_date']; ?>"
                data-amount="<?php echo $row['amount']; ?>"
                data-credit_mode="<?php echo $row['credit_mode']; ?>"
                data-c_id="<?php echo $row['c_id']; ?>"
                data-gl_id="<?php echo $row['gl_id']; ?>"
                data-cheque_number="<?php echo $row['cheque_number']; ?>"
                data-bank_name="<?php echo $row['bank_name']; ?>"
                data-cheque_date="<?php echo $row['cheque_date']; ?>"
                data-bs-toggle="modal" data-bs-target="#editCreditModal">
                <i class="bi bi-tools"></i>
            </button>

                     <button class="btn btn-danger btn-sm deleteCredit" data-id="<?php echo $row['credit_id'];?>" data-bs-toggle="modal" data-bs-target="#deleteCreditModal"><i class="bi bi-trash"></i></button>
                    </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php

// Get Customer number
$query1 ="SELECT * FROM customer_tbl";
              $stmt1 = $pdo->prepare($query1);
            $customers= $stmt1->execute();
// Get Customer number
$query2 ="SELECT * FROM gl_tbl";
              $stmt2 = $pdo->prepare($query2);
           $gls=  $stmt2->execute();
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
            <div class="form-group ">
                <label for="amount">Amount</label>
                <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount" required>
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
                 <?php
                    $stmt1->execute();
              while($customer = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="'.$customer['c_id'].'">'.$customer['c_name'].'</option>';
              }
                   ?>
                   </select>  
                  </div>
            <div class="form-group mt-4">
                 <label for="gl_id">Purpose</label>
                 <select class="form-control" id="gl_id" name="gl_id" required>
                   <option value="" disabled selected>Purpose</option>
                 <?php
                  // Reload purpose list
              $stmt2->execute();
              while($gl = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="'.$gl['gl_id'].'">'.$gl['gl_name'].'</option>';
              }
                   ?>
                   </select>  
                  </div>
        </div>
        <input type="hidden" name="submit" value="submit">
        <div class="modal-footer">
            <a href="credit.php"></a>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
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
          <input type="hidden" name="credit_id" id="edit_credit_id">

          <div class="form-group">
            <label for="edit_amount">Amount</label>
            <input type="text" class="form-control" id="edit_amount" name="amount" required>
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
              <?php
              // Reload customer list
              $stmt1->execute();
              while($customer = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="'.$customer['c_id'].'">'.$customer['c_name'].'</option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group mt-4">
            <label for="edit_gl_id">Purpose</label>
            <select class="form-control" id="edit_gl_id" name="gl_id" required>
              <option value="" disabled selected>Purpose</option>
              <?php
              // Reload purpose list
              $stmt2->execute();
              while($gl = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="'.$gl['gl_id'].'">'.$gl['gl_name'].'</option>';
              }
              ?>
            </select>
          </div>
           <div class="form-group mt-4">
            <label class="form-label">Cheque Number</label>
            <input type="text" class="form-control" name="edit_cheque_number" id="edit_cheque_number">
          </div>
          <div class="form-group mt-4">
            <label class="form-label">Bank Name</label>
            <input type="text" class="form-control" name="edit_bank_name" id="edit_bank_name">
          </div>
          <div class="form-group mt-4">
            <label class="form-label">Cheque Date</label>
            <input type="date" class="form-control" name="edit_cheque_date" id="edit_cheque_date">
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
<!-- Edit Cheque Modal -->
<!-- <div class="modal fade" id="editChequeModal" tabindex="-1" aria-labelledby="editChequeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Cheque Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editChequeForm">
          <div class="mb-3">
            <label class="form-label">Cheque Number</label>
            <input type="text" class="form-control" name="edit_cheque_number" id="edit_cheque_number">
          </div>
          <div class="mb-3">
            <label class="form-label">Bank Name</label>
            <input type="text" class="form-control" name="edit_bank_name" id="edit_bank_name">
          </div>
          <div class="mb-3">
            <label class="form-label">Cheque Date</label>
            <input type="date" class="form-control" name="edit_cheque_date" id="edit_cheque_date">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div> -->



<?php
    include('../includes/footer.php'); ?>
<script>
  document.getElementById('creditForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch('store_credit.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(response => {
      if (response.includes('Credit added successfully')) {
        alert('Credit added successfully!');
        window.location.href = "credit.php";
      } else {
        alert("Something went wrong:\n" + response);
        // Keep modal open
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert("Error submitting form.");
    });
  });

  document.getElementById('credit_mode').addEventListener('change', function () {
    if (this.value === 'Cheque') {
      const chequeModal = new bootstrap.Modal(document.getElementById('chequeModal'));
      chequeModal.show();
    }
  });
  
 
  


 
document.querySelectorAll('.editCredit').forEach(button => {
  button.addEventListener('click', () => {
    document.getElementById('edit_credit_id').value = button.getAttribute('data-id');
    document.getElementById('edit_c_date').value = button.getAttribute('data-c_date');
    document.getElementById('edit_amount').value = button.getAttribute('data-amount');
    document.getElementById('edit_credit_mode').value = button.getAttribute('data-credit_mode');
    document.getElementById('edit_c_id').value = button.getAttribute('data-c_id');
    document.getElementById('edit_gl_id').value = button.getAttribute('data-gl_id');
  });
   
});
 document.getElementById('edit_credit_mode').addEventListener('change', function () {
//     if (this.value === 'Cheque') {
      document.getElementById('edit_cheque_number').value = button.getAttribute('data-cheque_number').show();
      document.getElementById('edit_bank_name').value = button.getAttribute('data-bank_name').show();
      document.getElementById('edit_cheque_date').value = button.getAttribute('data-cheque_date').show();

    //   const editChequeModal = new bootstrap.Modal(document.getElementById('editChequeModal'));
    //   editChequeModal.show();
    // }
  });

document.getElementById('editCreditForm').addEventListener('submit', function(e){
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  fetch('updateCredit.php', {
    method:'POST',
    body:formData
  }) 
 .then(res => res.text())
 .then(response=>{
  if(response.includes('Credit updated successfully')){
    alert('Credit updated successfully!');
    window.location.href ="credit.php";
  } else {
        alert("Something went wrong:\n" + response);
        // Keep modal open
      }
 }) .catch(error => {
      console.error('Error:', error);
      alert("Error submitting form.");
    });
})
//  document.getElementById('edit_credit_mode').addEventListener('change', function () {
//     if (this.value === 'Cheque') {
//       const editChequeModal = new bootstrap.Modal(document.getElementById('editChequeModal'));
//       editChequeModal.show();
//     }
//   });


  document.addEventListener('DOMContentLoaded', function(){
    const viewButtons = document.querySelectorAll('.viewCredit');
    viewButtons.forEach(button =>{
      button.addEventListener('click',()=>{

        document.getElementById('view_credit_id').value=button.getAttribute('data-id');

        const c_date = button.getAttribute('data-c_date');
        document.getElementById('view_c_date').textContent=c_date;
        
        const amount = button.getAttribute('data-amount');
        document.getElementById('view_amount').textContent=amount;
        
        const credit_mode = button.getAttribute('data-credit_mode');
        document.getElementById('view_credit_mode').textContent=credit_mode;
        
        const c_id = button.getAttribute('data-c_id');
        document.getElementById('view_c_id').textContent=c_id;
        
        const gl_id = button.getAttribute('data-gl_id');
        document.getElementById('view_gl_id').textContent=gl_id;

      });
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